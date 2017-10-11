@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::open(['route'=>'admin.models.store', 'method'=>'post']) !!}
            @include('runsite::models.form')
            <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.Create') }}</button>
        {!! Form::close() !!}
    </div>
@endsection