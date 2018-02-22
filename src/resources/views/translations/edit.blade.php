@extends('runsite::layouts.app')
@section('app')
<div class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-language"></i> {{ trans('runsite::translations.Translations') }}</h3>
        </div>
        <div class="box-body">
            
            {!! Form::open(['url'=>route('admin.translations.update', $translation), 'method'=>'patch']) !!}
            @foreach($languages as $k=>$language)
                <div class="form-group">
                    <label for="">{{ $language->display_name }}</label>
                    <input {{ !$k ? 'autofocus' : null }} type="text" class="form-control input-sm" name="values[{{ $language->id }}]" value="{{ $translations->where('language_id', $language->id)->first() ? $translations->where('language_id', $language->id)->first()->value : null }}">
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::translations.Update') }}</button>
            {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
