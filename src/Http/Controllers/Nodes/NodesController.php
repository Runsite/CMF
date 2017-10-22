<?php

namespace Runsite\CMF\Http\Controllers\nodes;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Dynamic\Language;
use Auth;
use LaravelLocalization;

class NodesController extends BaseAdminController
{
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Model $model, $parent_id)
	{
		$node = Node::findOrFail($parent_id);
		$languages = Language::get();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = LaravelLocalization::setLocale();
		return view('runsite::nodes.create', compact('model', 'node', 'languages', 'breadcrumbs', 'active_language_tab'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Model $model, Node $parent_node)
	{
		$languages = Language::get();
		// Custom validation
		$validation = [];
		foreach($model->fields as $field)
		{
			$custom_validation_rules = $field->findSettings('custom_validation_rules');
			foreach($languages as $language)
			{
				if($custom_validation_rules and $custom_validation_rules->value and  
					(!$this->ruleHasRequired($custom_validation_rules->value) or $request->input('is_active.'.$language->id))
				)
				{
					// If this field has validation rules
					$validation = array_merge($validation, [
						$field->name.'.'.$language->id => $custom_validation_rules->value,
					]);
				}
				else
				{
					// Else rule will be empty
					$validation = array_merge($validation, [
						$field->name.'.'.$language->id => '',
					]);
				}
			}
		}

		// Gedding data from validator.
		$data = $request->validate($validation);

		// Node name is needly for generation path. But not required.
		// Getting main language by app.locale
		$main_language = $languages->where('locale', config('app.locale'))->first();
		$node_name = isset($data['name']) ? $data['name'][$main_language->id] : null;

		// Creating node
		$node = Node::create([
			'parent_id' => $parent_node->id,
			'model_id' => $model->id,
		], $node_name);

		// Saving fields values
		foreach($languages as $language)
		{
			foreach($model->fields as $field)
			{
				// TODO: must be middleware for fields like image etc
				$field_value = $data[$field->name][$language->id];
				$node->{$language->locale}->{$field->name} = $field_value;
			}
			// Saving locale
			$node->{$language->locale}->save();
		}

		return redirect()->route('admin.nodes.edit', ['mode'=>$parent_node])->with('succcess', trans('runsite::nodes.The node is created'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Node $node, $depended_model_id=null)
	{
		if(! Auth::user()->access()->node($node)->read)
		{
			return view('runsite::errors.forbidden');
		}

		$dynamic = $node->dynamic()->get();
		$model = $node->model;
		$languages = Language::get();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = LaravelLocalization::setLocale();

		$depended_models = [];

		foreach($model->dependencies as $k=>$dependency)
		{
			$depended_models[$dependency->id] = $dependency;
			if(!$depended_model_id and !$k)
			{
				$depended_model_id = $dependency->id;
			}
		}

		$depended_model = $model->dependencies->where('id', $depended_model_id)->first();
		$children = [];
		if($depended_model)
		{
			$children = M($depended_model->tableName())->where('parent_id', $node->id)->paginate();
		}
		

		// debug($children);

		return view('runsite::nodes.edit', compact('node', 'dynamic', 'depended_model', 'model', 'languages', 'breadcrumbs', 'depended_models', 'children', 'active_language_tab'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Node $node)
	{
		if(! Auth::user()->access()->node($node)->edit)
		{
			return view('runsite::errors.forbidden');
		}

		$fields = $node->model->fields;
		$languages = Language::get();

		foreach($languages as $language)
		{
			$dynamic = $node->dynamic()->where('language_id', $language->id)->first();
			foreach($fields as $field)
			{
				if(isset($request->{$field->name}[$language->id]))
				{
					$dynamic->{$field->name} = $request->{$field->name}[$language->id];
				}
			}

			$dynamic->save();
		}

		return redirect(route('admin.nodes.edit', $node->id))->with('success', trans('runsite::nodes.The node is updated'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Node $node)
	{
		//
	}

	protected function ruleHasRequired($rule)
	{
		return str_is('required', $rule) or str_is('*required', $rule) or str_is('required*', $rule) or str_is('*required*', $rule);
	}
}
