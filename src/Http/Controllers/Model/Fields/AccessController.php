<?php

namespace Runsite\CMF\Http\Controllers\Model\Fields;

use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Runsite\CMF\{
	Http\Controllers\BaseAdminController,
	Models\Model\Model,
	Models\Model\Field\Field,
	Models\User\Access\AccessField,
	Models\User\Group,
	Traits\Applicable
};

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
	public function edit(Model $model, Field $field)
	{
		$access = $field->access;
		$groups = Group::get();
		$fields = $model->fields()->orderBy('position', 'asc')->get();

		return view('runsite::models.fields.access.edit', compact('field', 'access', 'model', 'groups', 'fields'))->withApplication($this->application);
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
		AccessField::where('field_id', $field->id)->update([
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

				AccessField::where('field_id', $field->id)->where('group_id', $group_id)->update([
					'access' => $totalAccess,
				]);
			}
		}

		return redirect()->route('admin.models.fields.access.edit', ['model'=>$model, 'field'=>$field])
				->with('success', trans('runsite::models.fields.access.Access is updated'));
	}
}
