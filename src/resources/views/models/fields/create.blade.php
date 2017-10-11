@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($field, ['url'=>route('admin.models.fields.store', $model->id), 'method'=>'post']) !!}
            <input type="hidden" name="model_id" value="{{ $model->id }}">
            @include('runsite::models.fields.form')
            <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.fields.Create') }}</button>
        {!! Form::close() !!}
    </div>
@endsection