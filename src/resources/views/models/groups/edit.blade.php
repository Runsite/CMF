@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($group, ['url'=>route('admin.models.groups.update', ['model_id'=>$model->id, 'field_id'=>$group->id]), 'method'=>'patch']) !!}
            @include('runsite::models.groups.form')
            <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.groups.Update') }}</button>
        {!! Form::close() !!}
    </div>
@endsection