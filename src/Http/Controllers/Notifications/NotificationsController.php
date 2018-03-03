<?php

namespace Runsite\CMF\Http\Controllers\Notifications;

use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Notification;
use Auth;

class NotificationsController extends BaseAdminController
{
	public function index()
	{
		$notificationItems = Notification::where('user_id', Auth::id())->orderBy('is_reviewed', 'asc')->orderBy('created_at', 'asc')->paginate(30);
		return view('runsite::notifications.index', compact('notificationItems'));
	}

	public function show(Notification $notification)
	{
		if($notification->user_id != Auth::id())
		{
			return view('runsite::errors.forbidden');
		}

		$notification->is_reviewed = true;
		$notification->save();

		if($notification->node)
		{
			return redirect()->route('admin.nodes.edit', ['node'=>$notification->node]);
		}

		return view('runsite::notifications.show', compact('notification'));
	}
}
