@extends('runsite::layouts.resources')

@section('body-class') forbidden @endsection

@section('content')
	<div class="center text-center">
		<h1 class="text-uppercase">{{ trans('runsite::errors.Forbidden') }}</h1>
		<p>{{ trans('runsite::errors.You have not access to this section or function') }}</p>
	</div>
@endsection