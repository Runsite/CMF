@extends('runsite::layouts.resources')
@section('body-class', 'hold-transition login-page')
@section('content')
<div class="login-box">
	<div class="login-logo">
		<a href="{{ route('login') }}">{{ config('app.name') }}</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		
		@yield('app')
	</div>
	<!-- /.login-box-body -->
</div>

@endsection