@extends('runsite::layouts.resources')
@section('body-class', '')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-7">
			<div class="col-sm-6 col-sm-push-3 auth-body">
				<div class="auth-box">
					<h1 class="h2"><a href="{{ route('login') }}">{{ config('app.name') }}</a></h1>
					@yield('app')
				</div>
			</div>
		</div>
		<div class="col-md-5 hidden-xs hidden-sm auth-image"></div>
	</div>
</div>
@endsection
