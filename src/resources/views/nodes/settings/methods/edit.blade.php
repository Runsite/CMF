@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::model($methods, ['url'=>route('admin.nodes.settings.methods.update', ['node'=>$node]), 'method'=>'patch']) !!}
@endsection

@section('node')
	<div class="form-group">
		<i class="fa fa-info-circle text-info" aria-hidden="true"></i>
		{{ trans('runsite::models.methods.You can configure 4 default methods for sections of this model') }}
	</div>

	<div class="form-group {{ $errors->has('get') ? ' has-error' : '' }}">
		{{ Form::label('get', trans('runsite::models.methods.GET')) }}
		@if($methods->validMethod('get') !== true)
			&nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('get') }}</strong>
		@endif
		{{ Form::text('get', null, ['class'=>'form-control input-sm', 'autofocus'=>'true', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
		@if ($errors->has('get'))
			<span class="help-block">
				<strong>{{ $errors->first('get') }}</strong>
			</span>
		@endif

		@if(!$methods->get and $node->model->methods->get)
			<i class="fa fa-cog text-green"></i> {{ trans('runsite::models.methods.The model method is used') }}: <span class="text-green">{{ $node->model->methods->get }}</span>
		@endif
	</div>

	<div class="form-group {{ $errors->has('post') ? ' has-error' : '' }}">
		{{ Form::label('post', trans('runsite::models.methods.POST')) }}
		@if($methods->validMethod('post') !== true)
			&nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('post') }}</strong>
		@endif
		{{ Form::text('post', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
		@if ($errors->has('post'))
			<span class="help-block">
				<strong>{{ $errors->first('post') }}</strong>
			</span>
		@endif

		@if(!$methods->post and $node->model->methods->post)
			<i class="fa fa-cog text-green"></i> {{ trans('runsite::models.methods.The model method is used') }}: <span class="text-green">{{ $node->model->methods->post }}</span>
		@endif
	</div>

	<div class="form-group {{ $errors->has('patch') ? ' has-error' : '' }}">
		{{ Form::label('patch', trans('runsite::models.methods.PATCH')) }}
		@if($methods->validMethod('patch') !== true)
			&nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('patch') }}</strong>
		@endif
		{{ Form::text('patch', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
		@if ($errors->has('patch'))
			<span class="help-block">
				<strong>{{ $errors->first('patch') }}</strong>
			</span>
		@endif

		@if(!$methods->patch and $node->model->methods->patch)
			<i class="fa fa-cog text-green"></i> {{ trans('runsite::models.methods.The model method is used') }}: <span class="text-green">{{ $node->model->methods->patch }}</span>
		@endif
	</div>

	<div class="form-group {{ $errors->has('delete') ? ' has-error' : '' }}">
		{{ Form::label('delete', trans('runsite::models.methods.DELETE')) }}
		@if($methods->validMethod('delete') !== true)
			&nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('delete') }}</strong>
		@endif
		{{ Form::text('delete', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
		@if ($errors->has('delete'))
			<span class="help-block">
				<strong>{{ $errors->first('delete') }}</strong>
			</span>
		@endif

		@if(!$methods->delete and $node->model->methods->delete)
			<i class="fa fa-cog text-green"></i> {{ trans('runsite::models.methods.The model method is used') }}: <span class="text-green">{{ $node->model->methods->delete }}</span>
		@endif
	</div>


	@if(Auth::user()->access()->application($application)->edit)
		<div class="form-group xs-mb-0">
			<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Update') }}</button>
		</div>
	@endif
@endsection
