@extends('runsite::layouts.auth')
@section('app')
<p>{{ trans('runsite::auth.Sign in to start your session') }}</p>
<form method="POST" action="{{ route('login') }}">
		{{ csrf_field() }}

		{!! app('captcha')->render(); !!}

		<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
			<input id="email" type="email" class="form-control input-sm" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ trans('runsite::auth.Email') }}">
			@if ($errors->has('email'))
			<span class="help-block">
				{{ $errors->first('email') }}
			</span>
			@endif
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		</div>
		<div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
			<input id="password" type="password" class="form-control input-sm" name="password" required placeholder="{{ trans('runsite::auth.Password') }}">
			@if ($errors->has('password'))
			<span class="help-block">
				{{ $errors->first('password') }}
			</span>
			@endif
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		</div>
		<div class="row">
			<div class="col-xs-8">
				<div class="checkbox icheck">
					<label>
						<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ trans('runsite::auth.Remember Me') }}
					</label>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-xs-4 xs-pt-10">
				<button type="submit" class="btn btn-primary btn-sm ripple btn-block">{{ trans('runsite::auth.Sign In') }}</button>
			</div>
			<!-- /.col -->
		</div>
</form>
<a href="{{ route('password.request') }}">{{ trans('runsite::auth.I forgot my password') }}</a>
@endsection
