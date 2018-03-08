<?php

namespace Runsite\CMF\Http\Controllers\Model;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Traits\Applicable;

class ModelsController extends BaseAdminController
{
    use Applicable;

    protected $application_name = 'models';

    public function __boot()
    {
        $this->middleware('application-access:models:edit')->only(['update', 'store', 'create']);
        $this->middleware('application-access:models:delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Model::orderBy('name', 'asc')->get();
        return view('runsite::models.index', compact('models'))->withApplication($this->application);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('runsite::models.create')->withApplication($this->application);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:rs_models',
            'display_name' => 'required|string|max:255',
            'display_name_plural' => 'required|string|max:255',
        ]);

        Model::create($data);
        return redirect()->route('admin.models.index')->with('success', trans('runsite::models.The model is created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function show(Model $model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $model)
    {
        return view('runsite::models.edit', compact('model'))->withApplication($this->application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:rs_models,name,'.$model->id.',id',
            'display_name' => 'required|string|max:255',
            'display_name_plural' => 'required|string|max:255',
        ]);

        $model->update($data);
        return redirect()->route('admin.models.edit', $model->id)->with('success', trans('runsite::models.The model is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $model)
    {
        if($model->id == 1)
        {
            return redirect()->back();
        }

        foreach($model->fields as $field)
        {
            $field->delete();
        }

        $model->delete();

        return redirect()->route('admin.models.index');
    }
}
