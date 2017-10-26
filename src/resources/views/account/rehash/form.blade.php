@extends('runsite::layouts.auth')
@section('app')
	<div class="text-success">
		<p><b>{{ trans('runsite::auth.You are successfully logged in') }}. {{ trans('runsite::auth.But it\'s time to change your password') }}.</b></p>
		<p><b>{{ trans('runsite::auth.You can specify your current password') }}</b></p>
	</div>
	{!! Form::open(['route'=>'admin.account.rehash.rehash', 'method'=>'patch']) !!}

		<div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
			<input autofocus id="password" type="password" class="form-control input-sm" name="password" required placeholder="{{ trans('runsite::auth.New password') }}">
			@if ($errors->has('password'))
			<span class="help-block">
				{{ $errors->first('password') }}
			</span>
			@endif
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
			<input id="password_confirmation" type="password" class="form-control input-sm" name="password_confirmation" required placeholder="{{ trans('runsite::auth.New password confirmation') }}">
			@if ($errors->has('password_confirmation'))
			<span class="help-block">
				{{ $errors->first('password_confirmation') }}
			</span>
			@endif
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-xs-6">
					<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::auth.Save password') }}</a>
				</div>
				<div class="col-xs-6 text-right">
					<a href="{{ route('admin.boot') }}" class="btn btn-default btn-sm ripple">{{ trans('runsite::auth.Remind me later') }}</a>
				</div>
			</div>
		</div>
		
	{!! Form::close() !!}
@endsection