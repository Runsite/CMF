@extends('runsite::layouts.app')
@section('app')
<div class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('runsite::account.settings.Account settings') }}</h3>
        </div>
        {!! Form::model($authUser, ['route'=>'admin.account.settings.update', 'method'=>'patch', 'files'=>true]) !!}
            {{ csrf_field() }}
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            {{ Form::label('name', trans('runsite::account.settings.Name')) }}
                            {{ Form::text('name', null, ['class'=>'form-control input-sm']) }}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            {{ Form::label('email', trans('runsite::account.settings.Email')) }}
                            {{ Form::email('email', null, ['class'=>'form-control input-sm']) }}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                            {{ Form::label('image', trans('runsite::account.settings.Image')) }}
                            {{ Form::file('image', null) }}
                            @if ($errors->has('image'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <img src="{{ $authUser->imagePath() }}" alt="" class="img-responsive">
                            @if($authUser and $authUser->image)
                                <a href="{{ route('admin.account.image.edit') }}" class="btn btn-default btn-xs"><i class="fa fa-crop"></i> {{ trans('runsite::account.settings.Crop image') }}</a>
                            @endif
                        </div>
                        
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" {{ ($errors->has('password') or $errors->has('password_confirmation')) ? 'checked' : '' }} data-toggle="collapse" data-target="#change-password">
                                {{ trans('runsite::account.settings.Change password') }}
                            </label>
                        </div>
                        
                        <div class="collapse {{ ($errors->has('password') or $errors->has('password_confirmation')) ? ' in' : '' }}" id="change-password">
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                {{ Form::label('password', trans('runsite::account.settings.Password')) }}
                                {{ Form::password('password', ['class'=>'form-control input-sm']) }}
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                {{ Form::label('password_confirmation', trans('runsite::account.settings.Password confirmation')) }}
                                {{ Form::password('password_confirmation', ['class'=>'form-control input-sm']) }}
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">{{ trans('runsite::account.settings.Update settings') }}</button>
            </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection