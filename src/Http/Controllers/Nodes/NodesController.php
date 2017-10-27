<?php

namespace Runsite\CMF\Http\Controllers\nodes;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
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
	 */
	public function create(Model $model, int $parent_id): View
	{
		$node = Node::findOrFail($parent_id);
		$languages = Language::get();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = config('app.fallback_locale');
		$prev_node = null;
		$next_node = null;
		return view('runsite::nodes.create', compact('model', 'node', 'languages', 'breadcrumbs', 'active_language_tab', 'prev_node', 'next_node'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 */
	public function store(Request $request, Model $model, Node $parent_node): RedirectResponse
	{
		$languages = Language::get();

		// Custom validation
		$validation = [];
		foreach($model->fields as $field)
		{
			// Loading rules from field settings
			$custom_validation_rules = $field->findSettings('custom_validation_rules');
			foreach($languages as $language)
			{
				if($custom_validation_rules and $custom_validation_rules->value and 

					// If rule is "required" and language group "is_active" is not checked, then we can not validate this rule
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
		$main_language = $languages->where('locale', config('app.fallback_locale'))->first();
		$node_name = isset($data['name']) ? $data['name'][$main_language->id] : null;
		
		$node_names = null;

		foreach($languages as $language)
		{
			// Gedding nodenames for all languages
			$node_names[$language->locale] = (isset($data['name']) and $data['name'][$language->id]) ? $data['name'][$language->id] : $node_name;
		}

		// Creating node
		$node = Node::create([
			'parent_id' => $parent_node->id,
			'model_id' => $model->id,
		], $node_names);

		// Saving fields values
		foreach($languages as $language)
		{
			foreach($model->fields as $field)
			{
				$field_value = $data[$field->name][$language->id];
				$field_type = $field->type();
				$field_value = $field_type::beforeCreating($field_value, $node->baseNode);
				$node->{$language->locale}->{$field->name} = $field_value;
			}
			// Saving locale
			$node->{$language->locale}->save();
		}

		// Redirecting with success message
		return redirect()->route('admin.nodes.edit', ['node'=>$parent_node, 'depended_model_id'=>$model->id])
			->with('succcess', trans('runsite::nodes.The node is created'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $depended_model_id
	 */
	public function edit(Node $node, $depended_model_id=null): View
	{
		if(! Auth::user()->access()->node($node)->read)
		{
			return view('runsite::errors.forbidden');
		}

		$dynamic = $node->dynamic()->get();
		$model = $node->model;
		$languages = Language::get();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = config('app.fallback_locale');

		// Left/Right node navigation
		$node_ordering = explode(' ', $model->settings->nodes_ordering);
		$order_column = $node_ordering[0];
		$order_direcction = $node_ordering[1];

		// Current position
		$current_position_value = isset($dynamic->first()->{$order_column}) ? $dynamic->first()->{$order_column} : $node->{$order_column};
		$direction_left = $order_direcction == 'asc' ? 'desc' : 'asc';
		$direction_right = $order_direcction == 'desc' ? 'asc' : 'desc';

		$prev_node = M($model->name, false, $active_language_tab)
			->where($order_column, $direction_left == 'asc' ? '>' : '<', $current_position_value)
			->orderBy($order_column, $direction_left == 'desc' ? 'desc' : 'asc')
			->first();

		$next_node = M($model->name, false, $active_language_tab)
			->where($order_column, $direction_right == 'desc' ? '>' : '<', $current_position_value)
			->orderBy($order_column, $direction_right == 'desc' ? 'asc' : 'desc')
			->first();
		// [end] Left/Right node navigation

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
		$children_total_count = 0;
		if($depended_model)
		{
			$ordering = explode(' ', $depended_model->settings->nodes_ordering);
			$children = M($depended_model->tableName(), false, $active_language_tab)->where('parent_id', $node->id)->orderBy($ordering[0], $ordering[1]);
			$children_total_count = $children->count();
			$children = $children->paginate();
		}

		return view('runsite::nodes.edit', compact('node', 'dynamic', 'depended_model', 'model', 'languages', 'breadcrumbs', 'depended_models', 'children', 'active_language_tab', 'children_total_count', 'prev_node', 'next_node'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 */
	public function update(Request $request, Node $node): RedirectResponse
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

		return redirect(route('admin.nodes.edit', $node->id))
			->with('success', trans('runsite::nodes.The node is updated'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Node $node): RedirectResponse
	{
		//
	}

	protected function ruleHasRequired($rule)
	{
		return str_is('required', $rule) or str_is('*required', $rule) or str_is('required*', $rule) or str_is('*required*', $rule);
	}

	/**
	 * Move up the specified resource from storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function moveUp(Node $node, int $depended_model_id)
	{
		$node->moveUp();
		return redirect()
			->route('admin.nodes.edit', ['node'=>$node->parent, 'depended_model_id'=>$depended_model_id])
			->with('highlight', $node->id);
	}

	/**
	 * Move down the specified resource from storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function moveDown(Node $node, int $depended_model_id)
	{
		$node->moveDown();
		return redirect()
			->route('admin.nodes.edit', ['node'=>$node->parent, 'depended_model_id'=>$depended_model_id])
			->with('highlight', $node->id);
	}
}
