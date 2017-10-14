<?php

namespace Runsite\CMF\Http\Controllers\Model;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\User\Access\AccessModel;
use Runsite\CMF\Models\User\Group;

class AccessController extends BaseAdminController
{

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Model $model)
	{
		$groups = Group::get();

		return view('runsite::models.access.edit', compact('model', 'groups'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Model $model)
	{
		AccessModel::where('model_id', $model->id)->update([
			'access' => 0,
		]);

		if(isset($request->groups))
		{
			foreach($request->groups as $group_id=>$access)
			{
				$totalAccess = 0;
				if(isset($access['read']))
				{
					$totalAccess = 1;
				}

				if(isset($access['edit']))
				{
					$totalAccess = 2;
				}

				AccessModel::where('model_id', $model->id)->where('group_id', $group_id)->update([
					'access' => $totalAccess,
				]);
			}
		}

		return redirect()->route('admin.models.access.edit', ['model'=>$model])
				->with('success', trans('runsite::models.access.Access is updated'));
	}
}
