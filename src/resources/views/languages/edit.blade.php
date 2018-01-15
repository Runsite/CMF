@extends('runsite::layouts.translations')

@section('translation')
<div class="xs-p-15 xs-pb-15">
    {!! Form::model($language, ['url'=>route('admin.languages.update', $language), 'method'=>'patch']) !!}
        @include('runsite::languages.form')
        <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::languages.Update') }}</button>
    {!! Form::close() !!}
</div>
@endsection
