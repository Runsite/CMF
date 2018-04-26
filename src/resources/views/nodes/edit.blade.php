@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::open(['url'=>route('admin.nodes.update', ['id' => $node->id]), 'method'=>'patch', 'class'=>'form-horizontal', 'files'=>true]) !!}
@endsection

@section('node')
	@include('runsite::nodes.form')

	@if(Auth::user()->access()->node($node)->edit and Auth::user()->access()->model($node->model)->edit)
		<div class="form-group xs-mb-0">
			<div class="col-sm-10 col-sm-push-2">
				<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Update') }}</button>
			</div>
		</div>
	@endif

@endsection

@section('node_model')
	@if($userCanReadModels)
		<li class="dropdown">
			<a class="ripple" data-toggle="dropdown" href="#">
				<small><i class="fa fa-circle-o text-red"></i>&nbsp;{{ $model->name }}</small>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href="{{ route('admin.models.edit', $model->id) }}"><i class="fa fa-pencil-square-o"></i>&nbsp;{{ trans('runsite::models.Edit') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.models.settings.edit', $model->id) }}"><i class="fa fa-cog"></i>&nbsp;{{ trans('runsite::models.Settings') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.models.methods.edit', $model->id) }}"><i class="fa fa-window-restore"></i>&nbsp;{{ trans('runsite::models.Methods') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.models.fields.index', $model->id) }}"><i class="fa fa-th-list"></i>&nbsp;{{ trans('runsite::models.Fields') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.models.groups.index', $model->id) }}"><i class="fa fa-object-group"></i>&nbsp;{{ trans('runsite::models.Groups') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.models.dependencies.index', $model->id) }}"><i class="fa fa-sitemap"></i>&nbsp;{{ trans('runsite::models.Dependencies') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.models.access.edit', $model->id) }}"><i class="fa fa-lock"></i>&nbsp;{{ trans('runsite::models.Access') }}</a>
				</li>
			</ul>
		</li>
	@endif

@endsection
