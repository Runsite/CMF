@extends('runsite::layouts.app')
@section('app')
<div class="content">
	<div class="box box-warning">
		<div class="box-header with-border">
			<h3 class="box-title">{{ trans('runsite::app.Notifications') }}</h3>
		</div>
		<div class="box-body">
			{{ $notification->message }}
		</div>
	</div>
</div>

@endsection
