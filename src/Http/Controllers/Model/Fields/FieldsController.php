<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Auth;
use Runsite\CMF\Traits\Applicable;

class FieldsController extends BaseAdminController
{
    use Applicable;

    protected $application_name = 'models';

    public function __boot()
    {
        $this->middleware('application-access:models:edit')->only(['update', 'store', 'moveUp', 'moveDown']);
        $this->middleware('application-access:models:delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($model_id)
    {
        $model = Model::findOrFail($model_id);
        $fields = $model->fields()->orderBy('position', 'asc')->get();
        return view('runsite::models.fields.index', compact('model', 'fields'))->withApplication($this->application);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($model_id)
    {
        $field = new Field;
        $model = Model::findOrFail($model_id);
        return view('runsite::models.fields.create', compact('model', 'field'))->withApplication($this->application);
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
            'display_name' => 'required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'type_id' => 'required|integer',
            'model_id' => 'required|integer|exists:rs_models,id',
            'group_id' => 'nullable|integer|exists:rs_field_groups,id',
            'is_common' => '',
            'is_visible_in_nodes_list' => '',
        ]);

        Field::create($data);
        return redirect()->route('admin.models.fields.index', $model_id)->with('success', trans('runsite::models.The model field is created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function show(Model $model)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($model_id, $id)
    {
        $model = Model::findOrFail($model_id);
        $field = $model->fields()->where('id', $id)->first();
        return view('runsite::models.fields.edit', compact('model', 'field'))->withApplication($this->application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $model_id, $field_id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'type_id' => 'required|integer',
            'group_id' => 'nullable|integer|exists:rs_field_groups,id',
            'is_common' => '',
            'is_visible_in_nodes_list' => '',
        ]);

        $field = Field::findOrFail($field_id)->update($data);
        return redirect()->route('admin.models.fields.edit', ['model_id'=>$model_id, 'field_id'=>$field_id])->with('success', trans('runsite::models.fields.The field is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy($model_id, $field_id)
    {
        $field = Field::findOrFail($field_id);
        $field->delete();

        return redirect()->route('admin.models.fields.index', $model_id)->with('success', trans('runsite::models.fields.The field is removed'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp($model_id, $field_id)
    {
        Field::findOrFail($field_id)->moveUp();
        return redirect()->route('admin.models.fields.index', $model_id)->with('highlight', $field_id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown($model_id, $field_id)
    {
        Field::findOrFail($field_id)->moveDown();
        return redirect()->route('admin.models.fields.index', $model_id)->with('highlight', $field_id);
    }
}
