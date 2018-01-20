@extends('runsite::layouts.analytics')

@section('analytic')
	<div class="xs-p-15">
		{!! $chartjs->render() !!}
	</div>
@endsection
