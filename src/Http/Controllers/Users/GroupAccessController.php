<?php

namespace Runsite\CMF\Http\Controllers\Users;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Models\Application;
use Runsite\CMF\Models\User\Access\AccessApplication;

class GroupAccessController extends BaseAdminController
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $applications = Application::get();
        return view('runsite::groups.access', compact('applications', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        AccessApplication::where('group_id', $group->id)->update([
            'access' => 0,
        ]);

        if(isset($request->applications))
        {
            foreach($request->applications as $application_id=>$access)
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

                AccessApplication::where('group_id', $group->id)->where('application_id', $application_id)->update([
                    'access' => $totalAccess,
                ]);
            }
        }

        return redirect()->route('admin.groups.access.edit', ['group'=>$group])
                ->with('success', trans('runsite::groups.access.Access is updated'));
    }
}
