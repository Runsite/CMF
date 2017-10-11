@extends('runsite::layouts.users')

@section('user')
<div class="xs-p-15 xs-pb-15">
    {!! Form::model($user, ['url'=>route('admin.users.store'), 'method'=>'post']) !!}
        @include('runsite::users.form')
        <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::users.Create') }}</button>
    {!! Form::close() !!}
</div>
@endsection