<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\User\Access\AccessField;
use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Traits\Applicable;

class AccessController extends BaseAdminController
{
	use Applicable;

	protected $application_name = 'models';

	public function __boot()
	{
		$this->middleware('application-access:models:edit')->only('update');
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($model_id, $field_id)
	{
		$field = Field::findOrFail($field_id);
		$access = $field->access;
		$model = $field->model;
		$groups = Group::get();

		return view('runsite::models.fields.access.edit', compact('field', 'access', 'model', 'groups'))->withApplication($this->application);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $model_id, $field_id)
	{
		AccessField::where('field_id', $field_id)->update([
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

				AccessField::where('field_id', $field_id)->where('group_id', $group_id)->update([
					'access' => $totalAccess,
				]);
			}
		}

		return redirect()->route('admin.models.fields.access.edit', ['model_id'=>$model_id, 'field_id'=>$field_id])
				->with('success', trans('runsite::models.fields.access.Access is updated'));
	}
}
