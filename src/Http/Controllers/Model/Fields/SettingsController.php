<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Model\Field\Setting;
use Runsite\CMF\Traits\Applicable;

class SettingsController extends BaseAdminController
{
    use Applicable;

    protected $application_name = 'models';

    public function __boot()
    {
        $this->middleware('application-access:models:edit')->only('update');
    }
    
    /**
     * Get column info
     *
     * @return Doctrine\DBAL\Schema\Column
     */
    protected function getColumn($table_name, $field_name)
    {
        return DB::connection()->getDoctrineColumn($table_name, $field_name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $model, Field $field)
    {
        $settings = $field->settings;
        $column = null;

        if($field->type()::$needField)
        {
            $column = $this->getColumn($model->tableName(), $field->name);
        }

        $fields = $model->fields()->orderBy('position', 'asc')->get();

        return view('runsite::models.fields.settings.edit', compact('field', 'settings', 'model', 'column', 'fields'))->withApplication($this->application);
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
        $data = $request->except(['_method', '_token', 'field_length']);

        if($request->has('related_model_name'))
        {
            $this->validate($request, [
                'related_model_name' => 'required|exists:rs_models,name',
            ]);

            // Verification existing "name" field
            $relatedModel = Model::where('name', $request->related_model_name)->first();
            if(! Field::where('model_id', $relatedModel->id)->where('name', 'name')->count())
            {
                return redirect()->back()->withInput()->withErrors([
                    'related_model_name' => trans('runsite::models.fields.errors.The model should contain a field "name"'),
                ]);
            }
        }

        Setting::where('field_id', $field->id)->delete();

        foreach($data as $parameter=>$value)
        {
            Setting::create(['field_id'=>$field->id, 'parameter'=>$parameter, 'value'=>$value]);
        }

        // Change column size
        if($field->type()::$needField)
        {
            $column = $this->getColumn($model->tableName(), $field->name);
            $field_length['actual'] = $column->getLength();
            if(!$field_length['actual'])
            {
                $field_length['actual'] = $column->getPrecision().','.$column->getScale();
            }
            $field_length['new'] = $request->field_length;
            $field_type = $field->types[$field->type_id];

            if($field_length['actual'] != $field_length['new'])
            {
                if(str_is('*,*', $field_length['new']))
                {
                    list($base, $extra) = explode(',', $field_length['new']);
                }
                else
                {
                    $base = $field_length['new'];
                    $extra = null;
                }
                Schema::table($model->tableName(), function($table) use($field_type, $field, $base, $extra) {
                    $table->{$field_type::$name}($field->name, $base, $extra)->change();
                });
            }
        }
        

        return redirect()->route('admin.models.fields.settings.edit', ['model'=>$model, 'field'=>$field])->with('success', trans('runsite::models.fields.settings.The model field settings are updated'));
    }
}
