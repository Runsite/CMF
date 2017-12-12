<?php

namespace Runsite\CMF\Http\Controllers\Model;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Dependency;

class DependenciesController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Model $model)
    {
        $depended_models = Dependency::where('model_id', $model->id)->with('model')->orderBy('position', 'asc')->get();
        $available_models = Model::whereNotIn('id', $depended_models->pluck('depended_model_id'))->where('id', '!=', 1)->latest()->get();
        return view('runsite::models.dependencies.index', compact('available_models', 'depended_models', 'model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Model $model)
    {
        $data = $request->validate([
            'depended_model_id' => 'required|integer|exists:rs_models,id',
        ]);

        $data['model_id'] = $model->id;

        Dependency::create($data);
        return redirect()->route('admin.models.dependencies.index', $model)->with('success', trans('runsite::models.dependencies.The model dependency is created'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Model $model)
    {
        $request->validate([
            'depended_model_id' => 'required|integer|exists:rs_models,id',
        ]);

        $dependency = Dependency::where('model_id', $model->id)->where('depended_model_id', $request->depended_model_id)->first();

        $dependency->delete();
        return redirect()->route('admin.models.dependencies.index', $model)->with('success', trans('runsite::models.dependencies.The model dependency is deleted'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp(Model $model, $id)
    {
        Dependency::findOrFail($id)->moveUp();
        return redirect()->route('admin.models.dependencies.index', $model)->with('highlight', $id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown(Model $model, $id)
    {
        Dependency::findOrFail($id)->moveDown();
        return redirect()->route('admin.models.dependencies.index', $model)->with('highlight', $id);
    }
}
