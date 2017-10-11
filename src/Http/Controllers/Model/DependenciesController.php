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
    public function index($model_id)
    {
        $model = Model::findOrFail($model_id);
        $depended_models = Dependency::where('model_id', $model_id)->with('model')->orderBy('position', 'asc')->get();
        $available_models = Model::whereNotIn('id', $depended_models->pluck('depended_model_id'))->latest()->get();
        return view('runsite::models.dependencies.index', compact('available_models', 'depended_models', 'model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $model_id)
    {
        $data = $request->validate([
            'depended_model_id' => 'required|integer|exists:rs_models,id',
        ]);

        $data['model_id'] = $model_id;

        Dependency::create($data);
        return redirect()->route('admin.models.dependencies.index', $model_id)->with('success', trans('runsite::models.dependencies.The model dependency is created'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $model_id)
    {
        $request->validate([
            'depended_model_id' => 'required|integer|exists:rs_models,id',
        ]);

        $dependency = Dependency::where('model_id', $model_id)->where('depended_model_id', $request->depended_model_id)->first();

        $dependency->delete();
        return redirect()->route('admin.models.dependencies.index', $model_id)->with('success', trans('runsite::models.dependencies.The model dependency is deleted'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp($model_id, $dependent_model_id)
    {
        Dependency::findOrFail($dependent_model_id)->moveUp();
        return redirect()->route('admin.models.dependencies.index', $model_id)->with('highlight', $dependent_model_id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown($model_id, $dependent_model_id)
    {
        Dependency::findOrFail($dependent_model_id)->moveDown();
        return redirect()->route('admin.models.dependencies.index', $model_id)->with('highlight', $dependent_model_id);
    }
}
