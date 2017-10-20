<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use Illuminate\Http\Request;
use Runsite\CMF\{
    Http\Controllers\BaseAdminController,
    Models\Model\Model,
    Models\Model\Field\FieldGroup
};

class FieldGroupsController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Model $model)
    {
        $groups = $model->groups()->orderBy('position', 'asc')->get();
        return view('runsite::models.groups.index', compact('model', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Model $model)
    {
        $group = new FieldGroup;
        return view('runsite::models.groups.create', compact('model', 'group'));
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
            'name' => 'required|string|max:255',
            'model_id' => 'required|integer|exists:rs_models,id',
        ]);

        FieldGroup::create($data);
        return redirect()->route('admin.models.groups.index', $model)->with('success', trans('runsite::models.The model group is created'));
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
    public function edit(Model $model, FieldGroup $group)
    {
        return view('runsite::models.groups.edit', compact('model', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model, FieldGroup $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group->update($data);
        return redirect()->route('admin.models.groups.edit', ['model'=>$model, 'group'=>$group])->with('success', trans('runsite::models.groups.The group is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $model, FieldGroup $group)
    {
        $group->delete();
        return redirect()->route('admin.models.groups.index', $model)->with('success', trans('runsite::models.groups.The group is deleted'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp(Model $model, FieldGroup $group)
    {
        $group->moveUp();
        return redirect()->route('admin.models.groups.index', $model)->with('highlight', $group->id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown(Model $model, FieldGroup $group)
    {
        $group->moveDown();
        return redirect()->route('admin.models.groups.index', $model)->with('highlight', $group->id);
    }
}
