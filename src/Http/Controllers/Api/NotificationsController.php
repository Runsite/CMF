<?php

namespace Runsite\CMF\Http\Controllers\Api;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\Notification;
use Auth;
use Session;

class NotificationsController extends BaseAdminController
{
	public function soundNotificationsCount()
	{
		$notifications = Notification::where('user_id', Auth::id())->orderBy('is_reviewed', 'asc')->orderBy('created_at', 'desc')->take(10)->get();

		$unsoundedNotificationsCount = Notification::where('user_id', Auth::id())->where('is_sounded', false)->count();

		Notification::where('user_id', Auth::id())->where('is_sounded', false)->update([
			'is_sounded' => true,
		]);

		$notificationsHtml = '';
		foreach($notifications as $notification)
		{
			$notificationsHtml .= '
				<li>
					<a href="'.route('admin.notifications.show', $notification).'" style="white-space: normal;">
						<i class="fa fa-'. ($notification->icon_name ?: 'flag') .'  '.(!$notification->is_reviewed ? 'text-orange' : null).'"></i>
						'.$notification->message.'
					</a>
				</li>';
		}

		$nodes = [];

		foreach($notifications as $notification)
		{
			if(!$notification->is_reviewed)
			{
				if(!isset($nodes[$notification->node_id]))
				{
					$nodes[$notification->node_id] = 1;
				}
				else
				{
					$nodes[$notification->node_id]++;
				}
			}
		}

		$nodes = collect($nodes);

		// dd($nodes);

		return response()->json([
			'totalCount' => count($notifications->where('is_reviewed', false)),
			'notificationsHtml' => $notificationsHtml,
			'playSound' => $unsoundedNotificationsCount ? true : false,
			'nodes' => $nodes,
		]);
	}

	public function enableNotificationsSound()
	{
		Session::forget('notificationSoundsMuted');
	}

	public function disableNotificationsSound()
	{
		Session::put('notificationSoundsMuted', true);
	}
}
