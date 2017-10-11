@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($field, ['url'=>route('admin.models.fields.update', ['model_id'=>$model->id, 'field_id'=>$field->id]), 'method'=>'patch']) !!}
            @include('runsite::models.fields.form')
            @if(Auth::user()->access()->application($application)->edit)
                <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.fields.Update') }}</button>
            @endif
        {!! Form::close() !!}
    </div>
@endsection