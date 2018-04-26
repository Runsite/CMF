<?php

namespace Runsite\CMF\Http\Controllers\Nodes;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Application;
use Runsite\CMF\Models\Notification;
use Auth;
use LaravelLocalization;
use Artisan;
use DB;
use ResponseCache;

class NodesController extends BaseAdminController
{
	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Model $model, int $parent_id): View
	{
		$node = Node::findOrFail($parent_id);
		
		if(! Auth::user()->access()->model($model)->edit or ! Auth::user()->access()->node($node)->edit)
		{
			return view('runsite::errors.forbidden');
		}

		$modelsApplication = Application::where('name', 'models')->first();
		$userCanReadModels = Auth::user()->access()->application($modelsApplication)->read;

		$languages = Language::get();
		$defaultLanguage = $languages->where('is_main', true)->first();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = config('app.locale');
		$prev_node = null;
		$next_node = null;
		return view('runsite::nodes.create', compact('model', 'node', 'languages', 'breadcrumbs', 'active_language_tab', 'prev_node', 'next_node', 'defaultLanguage', 'userCanReadModels'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 */
	public function store(Request $request, Model $model, Node $parent_node)
	{
		if(! Auth::user()->access()->model($model)->edit or ! Auth::user()->access()->node($parent_node)->edit)
		{
			return view('runsite::errors.forbidden');
		}

		if($model->settings->max_nodes_count !== null)
		{
			$nodes_count = Node::where('model_id', $model->id)->count();
			if($nodes_count >= $model->settings->max_nodes_count)
			{
				return redirect()->back();
			}
		}


		$languages = Language::get();
		$defaultLanguage = $languages->where('is_main', true)->first();

		// Custom validation
		$validation = [];
		$messages = [];
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

					$messages = array_merge($messages, [
						$field->name.'.'.$language->id.'.'.$custom_validation_rules->value => trans('runsite::validation.'.$custom_validation_rules->value),
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
				if($request->has($field->name.'.'.$language->id) or ($field->is_common and $request->has($field->name.'.'.$defaultLanguage->id)))
				{
					if($field->is_common and $request->has($field->name.'.'.$defaultLanguage->id))
					{
						$field_value = $data[$field->name][$defaultLanguage->id];
					}
					else
					{
						$field_value = $data[$field->name][$language->id];
					}

					$field_type = $field->type();
					$field_value = $field_type::beforeCreating($field_value, $node->baseNode, $field, $language);

					if($field->type()::$needField)
					{
						$node->{$language->locale}->{$field->name} = $field_value;
					}
				}
			}
			// Saving locale
			$node->{$language->locale}->save();
		}

		ResponseCache::clear();

		if($request->has('create_next_one'))
		{
			return redirect()->route('admin.nodes.create', ['model_id'=>$model->id, 'parent_id'=>$node->baseNode->parent_id])
				->with('success', trans('runsite::nodes.The node is created'))->with('create_next_one', true);
		}

		if($model->settings->redirect_to_node_after_creation)
		{
			return redirect()->route('admin.nodes.edit', ['node'=>$node->baseNode])
			->with('success', trans('runsite::nodes.The node is created')); 
		}

		// Redirecting with success message
		return redirect()->route('admin.nodes.edit', ['node'=>$parent_node, 'depended_model_id'=>$model->id])
			->with('success', trans('runsite::nodes.The node is created'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $depended_model_id
	 */
	public function edit(Request $request, Node $node, $depended_model_id=null): View
	{
		if(! Auth::user()->access()->node($node)->read or ! Auth::user()->access()->model($node->model)->read)
		{
			return view('runsite::errors.forbidden');
		}

		Notification::where('user_id', Auth::id())->where('is_reviewed', false)->where('node_id', $node->id)->update(['is_reviewed'=>true]);

		$modelsApplication = Application::where('name', 'models')->first();

		$userCanReadModels = Auth::user()->access()->application($modelsApplication)->read;

		$dynamic = $node->dynamic()->get();
		$model = $node->model;
		$languages = Language::get();
		$defaultLanguage = $languages->where('is_main', true)->first();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = config('app.locale');

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
			->where('parent_id', $node->parent_id)
			->orderBy($order_column, $direction_left == 'desc' ? 'desc' : 'asc')
			->first();

		$next_node = M($model->name, false, $active_language_tab)
			->where($order_column, $direction_right == 'desc' ? '>' : '<', $current_position_value)
			->where('parent_id', $node->parent_id)
			->orderBy($order_column, $direction_right == 'desc' ? 'asc' : 'desc')
			->first();
		// [end] Left/Right node navigation

		$depended_models = [];
		$depended_models_create = [];

		$dependencies = $node->dependencies()
		->join('rs_group_model_access', 'rs_group_model_access.model_id', '=', 'rs_node_dependencies.depended_model_id')
		->whereIn('rs_group_model_access.group_id', Auth::user()->groups->pluck('id'))
		->where('rs_group_model_access.access', '>=', 1)
		->get();

		foreach($dependencies as $k=>$dependency)
		{
			$depended_models[$dependency->id] = $dependency;
			if(!$depended_model_id and !$k)
			{
				$depended_model_id = $dependency->id;
			}
		}

		$dependencies = $model->dependencies()
		->join('rs_group_model_access', 'rs_group_model_access.model_id', '=', 'rs_model_dependencies.depended_model_id')
		->whereIn('rs_group_model_access.group_id', Auth::user()->groups->pluck('id'))
		->where('rs_group_model_access.access', '>=', 1)
		->get();

		foreach($dependencies as $k=>$dependency)
		{
			$depended_models[$dependency->id] = $dependency;
			if(!$depended_model_id and !$k)
			{
				$depended_model_id = $dependency->id;
			}
		}

		$depended_model = $model->dependencies->where('id', $depended_model_id)->first();
		if(! $depended_model)
		{
			$depended_model = $node->dependencies->where('id', $depended_model_id)->first();
		}

		$children = [];
		$children_total_count = 0;
		if($depended_model)
		{
			$ordering = explode(' ', $depended_model->settings->nodes_ordering);

			$orderField = $ordering[0];
			$orderDirection = $ordering[1];

			if($request->orderby and $request->direct and ($request->orderby == 'position' or ($model->hasField($request->orderby) and $model->isRealField($request->orderby))))
			{
				$orderField = $request->orderby;
				$orderDirection = $request->direct;
			}

			$children = M($depended_model->tableName(), false, $active_language_tab)->where('parent_id', $node->id)
			->join('rs_group_node_access', 'rs_group_node_access.node_id', '=', 'rs_nodes.id')
			->whereIn('rs_group_node_access.group_id', Auth::user()->groups->pluck('id'))
			->where('rs_group_node_access.access', '>=', 1)
			->join('rs_group_model_access', 'rs_group_model_access.model_id', '=', 'rs_nodes.model_id')
			->whereIn('rs_group_model_access.group_id', Auth::user()->groups->pluck('id'))
			->where('rs_group_model_access.access', '>=', 1)
			->orderBy($orderField, $orderDirection);
			$children_total_count = $children->count();
			$children = $children->groupBy('rs_nodes.id')->paginate();
		}


		// Depended models to creating
		foreach($depended_models as $depended_model_item)
		{
			if(Auth::user()->access()->model($depended_model_item)->edit)
			{
				// Checking nodes count limit
				if($depended_model_item->settings->max_nodes_count !== null)
				{
					$nodes_count = Node::where('model_id', $depended_model_item->id)->count();
					if($nodes_count < $depended_model_item->settings->max_nodes_count)
					{
						$depended_models_create[] = $depended_model_item;
					}
				}
				else
				{
					$depended_models_create[] = $depended_model_item;
				}
			}
		}

		return view('runsite::nodes.edit', compact('node', 'dynamic', 'depended_model', 'model', 'languages', 'breadcrumbs', 'depended_models', 'depended_models_create', 'children', 'active_language_tab', 'children_total_count', 'prev_node', 'next_node', 'defaultLanguage', 'userCanReadModels', 'orderField', 'orderDirection'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 */
	public function update(Request $request, Node $node)
	{
		if(! Auth::user()->access()->node($node)->edit)
		{
			return view('runsite::errors.forbidden');
		}

		$fields = $node->model->fields;
		$languages = Language::get();
		$defaultLanguage = $languages->where('is_main', true)->first();

		// Custom validation
		$validation = [];
		$messages = [];
		foreach($fields as $field)
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

					$messages = array_merge($messages, [
						$field->name.'.'.$language->id.'.'.$custom_validation_rules->value => trans('runsite::validation.'.$custom_validation_rules->value),
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
		$data = $request->validate($validation, $messages);

		foreach($languages as $language)
		{
			$dynamic = $node->dynamic()->where('language_id', $language->id)->first();

			if(! $dynamic)
			{
				DB::table($node->model->tableName())->insert([
					'node_id' => $node->id,
					'language_id' => $language->id,
					'created_at' => $node->created_at,
					'updated_at' => $node->updated_at,
				]);

				$dynamic = $node->dynamic()->where('language_id', $language->id)->first();
			}

			foreach($fields as $field)
			{
				if($request->has($field->name.'.'.$language->id) or ($field->is_common and $request->has($field->name.'.'.$defaultLanguage->id)))
				{
					if($field->is_common and $request->has($field->name.'.'.$defaultLanguage->id))
					{
						$field_value = $data[$field->name][$defaultLanguage->id];
					}
					else
					{
						$field_value = $data[$field->name][$language->id];
					}
					
					$field_type = $field->type();
					$field_value = $field_type::beforeUpdating($field_value, $dynamic->{$field->name}, $node, $field, $language);
					
					if($field->type()::$needField)
					{
						$dynamic->{$field->name} = $field_value;
					}
				}
			}

			$dynamic->save();
		}

		ResponseCache::clear();

		return redirect(route('admin.nodes.edit', $node->id))
			->with('success', trans('runsite::nodes.The node is updated'));
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Node $node): RedirectResponse
	{
		if(! $node->canBeRemoved())
		{
			return redirect()->back()->with('error', trans('runsite::nodes.Node can not be removed'));
		}

		$parent_node = $node->parent;

		$node->delete();

		ResponseCache::clear();

		// Redirecting with success message
		return redirect(route('admin.nodes.edit', ['node'=>$parent_node]))
			->with('succcess', trans('runsite::nodes.The node is deleted'));
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

		ResponseCache::clear();

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

		ResponseCache::clear();

		return redirect()
			->route('admin.nodes.edit', ['node'=>$node->parent, 'depended_model_id'=>$depended_model_id])
			->with('highlight', $node->id);
	}

	public function qrCode(Node $node, Language $language)
	{
		$breadcrumbs = $node->breadcrumbs();
		return view('runsite::nodes.qr-code', compact('node', 'language', 'breadcrumbs'));
	}
}
