@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::open(['url'=>route('admin.nodes.update', ['id' => $node->id]), 'method'=>'patch', 'class'=>'form-horizontal', 'files'=>true]) !!}
@endsection

@section('node')
	@include('runsite::nodes.form')

	@if(Auth::user()->access()->node($node)->edit)
		<div class="form-group xs-mb-0">
			<div class="col-sm-10 col-sm-push-2">
				<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Update') }}</button>
			</div>
		</div>
	@endif

@endsection

@section('node_model')
	@if(Auth::user()->access()->application($modelsApplication)->read)
		<li>
			<a class="ripple" href="{{ route('admin.models.edit', $model->id) }}">
				<small><i class="fa fa-circle-o text-red"></i>&nbsp;{{ $model->name }}</small>
			</a>
		</li>
	@endif

@endsection
