<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Auth;
use Runsite\CMF\Traits\Applicable;
use Runsite\CMF\Models\Model\Field\Setting;

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
    public function index(Model $model)
    {
        $fields = $model->fields()->orderBy('position', 'asc')->get();
        $fieldTemplates = $model->getAvailableFieldTemplates();
        return view('runsite::models.fields.index', compact('model', 'fields', 'fieldTemplates'))->withApplication($this->application);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Model $model, Field $field)
    {
        return view('runsite::models.fields.create', compact('model', 'field'))->withApplication($this->application);
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
            'display_name' => 'required|string|max:255',
            'hint' => 'nullable|string|max:255',
            'type_id' => 'required|integer',
            'model_id' => 'required|integer|exists:rs_models,id',
            'group_id' => 'nullable|integer|exists:rs_field_groups,id',
            'is_common' => '',
            'is_visible_in_nodes_list' => '',
        ]);

        Field::create($data);
        return redirect()->route('admin.models.fields.index', $model->id)->with('success', trans('runsite::models.The model field is created'));
    }

    public function storeByTemplate(Request $request, Model $model, int $template_id)
    {
        $template = new $model->fieldTemplates[$template_id];

        $template->install($model);

        return redirect()->route('admin.models.fields.index', $model->id)->with('success', trans('runsite::models.The model field is created'));
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
    public function edit(Model $model, Field $field)
    {
        $fields = $model->fields()->orderBy('position', 'asc')->get();
        return view('runsite::models.fields.edit', compact('model', 'field', 'fields'))->withApplication($this->application);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model, Field $field)
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

        // Check whether or not other models are referenced
        if($field->name == 'name' and $field->name != $data['name'] and Setting::where('parameter', 'related_model_name')->where('value', $model->name)->count())
        {
            return redirect()->back()->withInput()->withErrors([
                'name' => trans('runsite::models.fields.errors.Can not change name because another model uses it')
            ]);
        }

        $field->update($data);
        return redirect()->route('admin.models.fields.edit', ['model_id'=>$model->id, 'field_id'=>$field->id])->with('success', trans('runsite::models.fields.The field is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $model, Field $field)
    {
        $field->delete();

        return redirect()->route('admin.models.fields.index', $model->id)->with('success', trans('runsite::models.fields.The field is removed'));
    }

    /**
     * Move up the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveUp(Model $model, Field $field)
    {
        $field->moveUp();
        return redirect()->route('admin.models.fields.index', $model->id)->with('highlight', $field->id);
    }

    /**
     * Move down the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function moveDown(Model $model, Field $field)
    {
        $field->moveDown();
        return redirect()->route('admin.models.fields.index', $model->id)->with('highlight', $field->id);
    }
}
