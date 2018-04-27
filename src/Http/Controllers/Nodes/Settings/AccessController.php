<?php

namespace Runsite\CMF\Http\Controllers\Nodes\Settings;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\User\Access\AccessNode;
use Runsite\CMF\Models\User\Group;

class AccessController extends BaseAdminController
{

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Node $node)
	{
		$groups = Group::get();
		$breadcrumbs = $node->breadcrumbs();

		return view('runsite::nodes.settings.access.edit', compact('node', 'groups', 'breadcrumbs'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Model  $model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Node $node)
	{
		AccessNode::where('node_id', $node->id)->update([
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

				AccessNode::where('node_id', $node->id)->where('group_id', $group_id)->update([
					'access' => $totalAccess,
				]);

				if(isset($access['subnodes']))
				{
					AccessNode::assignRecursively($node->id, $group_id, $totalAccess);
				}
			}
		}

		return redirect()->route('admin.nodes.settings.access.edit', ['node'=>$node])
				->with('success', trans('runsite::nodes.access.Access is updated'));
	}
}
