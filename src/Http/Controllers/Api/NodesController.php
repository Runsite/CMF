<?php

namespace Runsite\CMF\Http\Controllers\Api;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;

class NodesController extends BaseAdminController
{

	public function findByName(Request $request)
	{
		$key = $request->input('q')['term'] ?? null;
		$related_model_name = $request->input('related_model_name');
		$related_parent_node_id = $request->input('related_parent_node_id');
		$results = [];

		if(! $request->multiple)
		{
			$results[] = [
				'id' => '',
				'text' => '---',
			];
		}

		$nodes = M($related_model_name, true, config('app.locale'));

		if($key)
		{
			$nodes = $nodes->where('name', 'like', '%'.$key.'%');
		}
		else
		{
			$nodes = $nodes->take(15);
		}

		$nodes = $nodes->orderBy($related_model_name.'.created_at', 'desc')->get();
		

		foreach($nodes as $node)
		{
			$results[] = [
				'id' => $node->node_id,
				'text' => $node->name,
			];
		}

		if(((count($results) == 1 and !$request->multiple) or (count($results) == 0 and $request->multiple)) and $related_parent_node_id)
		{
			$results[] = [
				'id' => '@#-create-' . $key,
				'text' => trans('runsite::nodes.Create').': "'.$key.'"',
			];
		}

		return response()->json([
			'results' => $results
		]);
	}

	public function innerLink(Request $request)
	{
		$key = $request->input('q')['term'] ?? null;
		
		$results[] = [
			'id' => '',
			'text' => '---',
		];

		if($key)
		{
			$searchableModels = Model::whereHas('settings', function($w) {
				$w->where('is_searchable', true);
			})->get();

			foreach($searchableModels as $searchableModel)
			{
				$nodes = M($searchableModel->name)->where('name', 'like', '%'.$key.'%')->orderBy('rs_nodes.created_at', 'desc')->take(30)->get();

				foreach($nodes as $node)
				{
					if($node->node->hasMethod or $searchableModel->hasMethod)
					{
						$results[] = [
							'id' => $node->node_id,
							'text' => '[' . $searchableModel->display_name_plural . '] - ' . $node->name,
						];
					}
				}
			}
		}

		return response()->json([
			'results' => $results
		]);
	}
}
