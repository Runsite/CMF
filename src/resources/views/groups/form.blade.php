<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', trans('runsite::groups.Name')) }}
    {{ Form::text('name', null, ['class'=>'form-control input-sm', 'autofocus'=>'true']) }}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
    {{ Form::label('description', trans('runsite::groups.Description')) }}
    {{ Form::text('description', null, ['class'=>'form-control input-sm']) }}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('description') }}</strong>
        </span>
    @endif
</div>
