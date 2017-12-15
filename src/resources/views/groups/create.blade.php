@extends('runsite::layouts.users')

@section('user')
<div class="xs-p-15 xs-pb-15">
    {!! Form::model($group, ['url'=>route('admin.groups.store'), 'method'=>'post']) !!}
        @include('runsite::groups.form')
        <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::groups.Create') }}</button>
    {!! Form::close() !!}
</div>
@endsection