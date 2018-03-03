@extends('runsite::layouts.app')
@section('app')
<div class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">{{ trans('runsite::app.Notifications') }}</h3>
		</div>

		<div class="table-responsive">
			<table class="table">
				@foreach($notificationItems as $notification)
					<tr>
						<td>
							<a href="{{ route('admin.notifications.show', $notification) }}" style="white-space: normal;">
								<i class="xs-mr-10 fa fa-{{ $notification->icon_name ?: 'flag' }} {{ !$notification->is_reviewed ? 'text-orange' : null }}"></i>
								{{ $notification->message }}
							</a>
						</td>
					</tr>
				@endforeach
			</table>
		</div>

		<div class="xs-pl-15">
			{!! $notificationItems->links() !!}
		</div>
	</div>
</div>

@endsection
