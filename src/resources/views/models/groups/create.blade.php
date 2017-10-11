@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($group, ['url'=>route('admin.models.groups.store', $model->id), 'method'=>'post']) !!}
            <input type="hidden" name="model_id" value="{{ $model->id }}">
            @include('runsite::models.groups.form')
            <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.groups.Create') }}</button>
        {!! Form::close() !!}
    </div>
@endsection