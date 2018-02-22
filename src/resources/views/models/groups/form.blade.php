<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', trans('runsite::models.groups.Name')) }}
    {{ Form::text('name', null, ['class'=>'form-control input-sm', 'autofocus'=>'true']) }}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>
