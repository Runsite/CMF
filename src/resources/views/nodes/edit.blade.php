@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::open(['url'=>route('admin.nodes.update', ['id' => $node->id]), 'method'=>'patch', 'class'=>'form-horizontal']) !!}
@endsection

@section('node')
	@include('runsite::nodes.form')
	<div class="form-group xs-mb-0">
		<div class="col-sm-10 col-sm-push-2">
			<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Update') }}</button>
		</div>
	</div>
@endsection