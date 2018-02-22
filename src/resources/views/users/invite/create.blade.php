@extends('runsite::layouts.users')

@section('user')
<div class="xs-p-15 xs-pb-15">
    {!! Form::model($invite, ['url'=>route('admin.users.invite.store'), 'method'=>'post']) !!}
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            {{ Form::label('name', trans('runsite::users.Name')) }}
            {{ Form::text('name', null, ['class'=>'form-control input-sm', 'autofocus'=>'true']) }}
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

        <div class="form-group {{ $errors->has('expires_at') ? ' has-error' : '' }}">
            {{ Form::label('expires_at', trans('runsite::users.invite.Expires at')) }}
            {{ Form::text('expires_at', null, ['class'=>'form-control input-sm input-datetime']) }}
            @if ($errors->has('expires_at'))
                <span class="help-block">
                    <strong>{{ $errors->first('expires_at') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('groups', trans('runsite::users.Groups')) }}
            <select name="groups[]" id="groups" class="form-control input-sm" multiple>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::users.Create') }}</button>
    {!! Form::close() !!}
</div>
@endsection
