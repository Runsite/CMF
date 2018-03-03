<?php

namespace Runsite\CMF\Http\Controllers\Notifications;

use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Notification;
use Auth;

class NotificationsController extends BaseAdminController
{
	public function index()
	{
		$notifications = Notification::where('user_id', Auth::id())->paginate();
		return view('runsite::notifications.index', compact('notifications'));
	}

	public function show(Notification $notification)
	{
		$notification->is_reviewed = true;
		$notification->save();

		if($notification->node)
		{
			return redirect()->route('admin.nodes.edit', ['node'=>$notification->node]);
		}

		return view('runsite::notifications.show', compact('notification'));
	}
}
