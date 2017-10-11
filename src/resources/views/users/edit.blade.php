@extends('runsite::layouts.users')

@section('user')
<div class="xs-p-15 xs-pb-15">
    {!! Form::model($user, ['url'=>route('admin.users.update', $user->id), 'method'=>'patch']) !!}
        @include('runsite::users.form')
        <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::users.Update') }}</button>
    {!! Form::close() !!}
</div>
@endsection