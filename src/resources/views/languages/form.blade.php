<div class="form-group {{ $errors->has('locale') ? ' has-error' : '' }}">
    {{ Form::label('locale', trans('runsite::languages.Locale')) }}
    {{ Form::text('locale', null, ['class'=>'form-control input-sm', 'autofocus'=>'true']) }}
    @if ($errors->has('locale'))
        <span class="help-block">
            <strong>{{ $errors->first('locale') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('display_name') ? ' has-error' : '' }}">
    {{ Form::label('display_name', trans('runsite::languages.Display name')) }}
    {{ Form::text('display_name', null, ['class'=>'form-control input-sm']) }}
    @if ($errors->has('display_name'))
        <span class="help-block">
            <strong>{{ $errors->first('display_name') }}</strong>
        </span>
    @endif
</div>
