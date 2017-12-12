<?php

namespace Runsite\CMF\Http\Controllers\Nodes\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Node\Path;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Dynamic\Language;
use Auth;
use LaravelLocalization;

class PathsController extends BaseAdminController
{
	public function index(Node $node) : View
	{
		$paths = $node->paths;
		$languages = Language::get();
		$breadcrumbs = $node->breadcrumbs();
		$active_language_tab = config('app.fallback_locale');
		$model = $node->model;

		return view('runsite::nodes.settings.paths.index', compact('paths', 'languages', 'node', 'breadcrumbs', 'active_language_tab', 'model'));
	}

	public function update(Node $node) : RedirectResponse
	{
		if(! Auth::user()->canEdit($node))
		{
			return redirect()->back()->with('error', trans('runsite::nodes.settings.paths.Access denied'));
		}

		foreach(request()->path as $language_id=>$language_paths)
		{
			foreach($language_paths as $id=>$name)
			{
				if($name)
				{
					Path::find($id)->update([
						'name' => $name,
					]);
				}
				elseif(count($language_paths) > 1)
				{
					Path::find($id)->delete();
				}
			}
			
		}

		return redirect()->route('admin.nodes.settings.paths.index', ['node'=>$node])
		->with('success', trans('runsite::nodes.paths.Updated successfully'));
	}
}
