@extends('runsite::layouts.auth')
@section('app')
<p>{{ trans('runsite::auth.invite.You have the invite for this admin area') }}. {{ trans('runsite::auth.invite.Fill the form, please and click Register button') }}</p>
	{!! Form::open(['url'=>route('admin.invite.register', $invite->token), 'method'=>'patch']) !!}
		{!! app('captcha')->render(); !!}
		<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
			<input id="email" type="email" class="form-control input-sm" name="email" value="{{ $invite->user->email }}" disabled placeholder="{{ trans('runsite::auth.Email') }}">
			@if ($errors->has('email'))
			<span class="help-block">
				{{ $errors->first('email') }}
			</span>
			@endif
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		</div>

		<div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
			<input id="password" type="password" class="form-control input-sm" name="password" required placeholder="{{ trans('runsite::auth.New password') }}" autofocus>
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
		
		<button type="submit" class="btn btn-primary btn-sm ripple btn-block">{{ trans('runsite::auth.invite.Register') }}</button>
	{!! Form::close() !!}
@endsection
