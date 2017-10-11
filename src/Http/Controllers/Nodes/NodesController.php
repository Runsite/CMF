<?php

namespace Runsite\CMF\Http\Controllers\nodes;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Dynamic\Language;
use Auth;

class NodesController extends BaseAdminController
{
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($model_id, $parent_id)
	{
		$model = Model::findOrFail($model_id);
		$parentNode = Node::findOrFail($parent_id);
		$languages = Language::get();
		return view('runsite::nodes.create', compact('model', 'parentNode'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, $depended_model_id=null)
	{
		$node = Node::findOrFail($id);

		if(! Auth::user()->access()->node($node)->read)
		{
			return view('runsite::errors.forbidden');
		}

		$dynamic = $node->dynamic()->get();
		$model = $node->model;
		$languages = Language::get();

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
		$children = M($depended_model->tableName())->where('parent_id', $node->id)->paginate();

		// debug($children);

		return view('runsite::nodes.edit', compact('node', 'dynamic', 'depended_model', 'model', 'languages', 'depended_models', 'children'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$node = Node::findOrFail($id);

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
	public function destroy($id)
	{
		//
	}
}
