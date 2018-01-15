@extends('runsite::layouts.translations')

@section('translation')
<div class="xs-p-15 xs-pb-15">
    {!! Form::model($language, ['url'=>route('admin.languages.store'), 'method'=>'post']) !!}
        @include('runsite::languages.form')
        <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::languages.Create') }}</button>
    {!! Form::close() !!}
</div>
@endsection
