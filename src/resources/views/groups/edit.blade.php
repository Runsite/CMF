@extends('runsite::layouts.users')

@section('user')

<div class="xs-p-15 xs-pb-15">
	{!! Form::model($group, ['url'=>route('admin.groups.update', $group), 'method'=>'patch']) !!}
		@include('runsite::groups.form')
		<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::groups.Update') }}</button>
	{!! Form::close() !!}
</div>
@endsection
