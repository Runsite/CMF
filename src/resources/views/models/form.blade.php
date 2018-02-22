<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', trans('runsite::models.Name')) }}
    {{ Form::text('name', null, ['class'=>'form-control input-sm', 'autofocus'=>'true', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('display_name') ? ' has-error' : '' }}">
    {{ Form::label('display_name', trans('runsite::models.Display name')) }}
    {{ Form::text('display_name', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
    @if ($errors->has('display_name'))
        <span class="help-block">
            <strong>{{ $errors->first('display_name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('display_name_plural') ? ' has-error' : '' }}">
    {{ Form::label('display_name_plural', trans('runsite::models.Display name plural')) }}
    {{ Form::text('display_name_plural', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
    @if ($errors->has('display_name_plural'))
        <span class="help-block">
            <strong>{{ $errors->first('display_name_plural') }}</strong>
        </span>
    @endif
</div>
