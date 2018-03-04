<?php

namespace Runsite\CMF\Http\Controllers\Search;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\SearchHistory;
use Auth;

class SearchController extends BaseAdminController
{
	protected function saveSearchRequest($search_key)
	{
		SearchHistory::where('user_id', Auth::id())->where('search_key', $search_key)->delete();

		SearchHistory::create([
			'user_id' => Auth::id(),
			'search_key' => $search_key,
		]);
	}

	public function findModel($search_key)
	{
		$searchableModels = Model::whereHas('settings', function($w) {
			$w->where('is_searchable', true);
		})->get();

		foreach($searchableModels as $k=>$searchableModel)
		{
			if(M($searchableModel->name, false)->where('name', 'like', '%'.$search_key.'%')->count())
			{
				return redirect()->route('admin.search.show', ['search_key'=>$search_key, 'model'=>$searchableModel]);
			}
		}

		return redirect()->route('admin.search.show', ['search_key'=>$search_key, 'model'=>$searchableModels->first()]);
	}

	public function show($search_key, Model $model)
	{
		$this->saveSearchRequest($search_key);
		
		$searchableModels = Model::whereHas('settings', function($w) {
			$w->where('is_searchable', true);
		})->get();

		$results = M($model->name, false)->where('name', 'like', '%'.$search_key.'%')->orderBy('rs_nodes.created_at', 'desc')->paginate();

		foreach($searchableModels as $k=>$searchableModel)
		{
			$searchableModels[$k]->resultsCount = M($searchableModel->name, false)->where('name', 'like', '%'.$search_key.'%')->count();
		}

		return view('runsite::search.show', compact('search_key', 'results', 'searchableModels', 'model'));
	}
}
