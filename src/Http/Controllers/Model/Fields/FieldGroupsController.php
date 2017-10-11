<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\FieldGroup;

class FieldGroupsController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($model_id)
    {
        $model = Model::findOrFail($model_id);
        $groups = $model->groups()->orderBy('position', 'asc')->get();
        return view('runsite::models.groups.index', compact('model', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($model_id)
    {
        $model = Model::findOrFail($model_id);
        $group = new FieldGroup;
        return view('runsite::models.groups.create', compact('model', 'group'));
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
            'name' => 'required|string|max:255',
            'model_id' => 'required|integer|exists:rs_models,id',
        ]);

        FieldGroup::create($data);
        return redirect()->route('admin.models.groups.index', $model_id)->with('success', trans('runsite::models.The model group is created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function show(Model $model)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($model_id, $id)
    {
        $model = Model::findOrFail($model_id);
        $group = $model->groups()->where('id', $id)->first();
        return view('runsite::models.groups.edit', compact('model', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $model_id, $group_id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = FieldGroup::findOrFail($group_id)->update($data);
        return redirect()->route('admin.models.groups.edit', ['model_id'=>$model_id, 'group_id'=>$group_id])->with('success', trans('runsite::models.groups.The group is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $model, $model_id, $id)
    {
        FieldGroup::findOrFail($id)->delete();
        return redirect()->route('admin.models.groups.index', $model_id)->with('success', trans('runsite::models.groups.The group is deleted'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp($model_id, $group_id)
    {
        FieldGroup::findOrFail($group_id)->moveUp();
        return redirect()->route('admin.models.groups.index', $model_id)->with('highlight', $group_id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown($model_id, $group_id)
    {
        FieldGroup::findOrFail($group_id)->moveDown();
        return redirect()->route('admin.models.groups.index', $model_id)->with('highlight', $group_id);
    }
}
