@extends('runsite::layouts.resources')

@section('body-class') forbidden @endsection

@section('content')
	<div class="center text-center">
		<h1 class="text-uppercase">{{ trans('runsite::auth.invite.Expired') }}</h1>
		<p>{{ trans('runsite::auth.invite.Your invite is already expired') }}</p>
	</div>
@endsection
