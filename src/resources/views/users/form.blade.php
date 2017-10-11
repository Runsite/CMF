<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {{ Form::label('name', trans('runsite::users.Name')) }}
    {{ Form::text('name', null, ['class'=>'form-control input-sm']) }}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
    {{ Form::label('email', trans('runsite::users.Email')) }}
    {{ Form::email('email', null, ['class'=>'form-control input-sm']) }}
    @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
    {{ Form::label('password', trans('runsite::users.Password')) }}
    {{ Form::password('password', ['class'=>'form-control input-sm']) }}
    @if ($errors->has('password'))
        <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    {{ Form::label('password_confirmation', trans('runsite::users.Password confirmation')) }}
    {{ Form::password('password_confirmation', ['class'=>'form-control input-sm']) }}
    @if ($errors->has('password_confirmation'))
        <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    {{ Form::label('groups', trans('runsite::users.Groups')) }}
    <select name="groups[]" id="groups" class="form-control input-sm" multiple>
        @foreach($groups as $group)
            <option @if($user->groups and in_array($group->id, $user->groups->pluck('id')->toArray())) selected @endif value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    </select>
</div>