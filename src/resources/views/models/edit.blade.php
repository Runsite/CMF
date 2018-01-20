@extends('runsite::layouts.models')

@section('model')
	<div class="xs-p-15 xs-pb-15">
		{!! Form::model($model, ['url'=>route('admin.models.update', $model->id), 'method'=>'patch']) !!}
			@include('runsite::models.form')
			@if(Auth::user()->access()->application($application)->edit)
				<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.Update') }}</button>
			@endif
		{!! Form::close() !!}
	</div>
@endsection
