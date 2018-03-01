@extends('runsite::layouts.auth')
@section('app')
<p>{{ trans('runsite::auth.Reset Password') }}</p>
<form method="POST" action="{{ route('password.email') }}">
	{{ csrf_field() }}

	{!! app('captcha')->render(); !!}

	<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
		<input id="email" type="email" class="form-control input-sm" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ trans('runsite::auth.Email') }}">
		@if ($errors->has('email'))
			<span class="help-block">
				<strong>{{ $errors->first('email') }}</strong>
			</span>
		@endif
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	</div>
	
	<button type="submit" class="btn btn-primary btn-block btn-sm ripple">{{ trans('runsite::auth.Send Password Reset Link') }}</button>
</form>
@endsection
