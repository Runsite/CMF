@extends('runsite::layouts.methods')

@section('methods')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($methods, ['url'=>route('admin.models.methods.update', $methods->model->id), 'method'=>'patch']) !!}
 
            <div class="form-group">
                <i class="fa fa-info-circle text-info" aria-hidden="true"></i>
                {{ trans('runsite::models.methods.You can configure 4 default methods for sections of this model') }}
            </div>

            <div class="form-group {{ $errors->has('get') ? ' has-error' : '' }}">
                {{ Form::label('get', trans('runsite::models.methods.GET')) }}
                @if($methods->validMethod('get') !== true)
                    &nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('get') }}</strong>
                @endif
                {{ Form::text('get', null, ['class'=>'form-control input-sm', 'autofocus'=>'true', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                @if ($errors->has('get'))
                    <span class="help-block">
                        <strong>{{ $errors->first('get') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('post') ? ' has-error' : '' }}">
                {{ Form::label('post', trans('runsite::models.methods.POST')) }}
                @if($methods->validMethod('post') !== true)
                    &nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('post') }}</strong>
                @endif
                {{ Form::text('post', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                @if ($errors->has('post'))
                    <span class="help-block">
                        <strong>{{ $errors->first('post') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('patch') ? ' has-error' : '' }}">
                {{ Form::label('patch', trans('runsite::models.methods.PATCH')) }}
                @if($methods->validMethod('patch') !== true)
                    &nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('patch') }}</strong>
                @endif
                {{ Form::text('patch', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                @if ($errors->has('patch'))
                    <span class="help-block">
                        <strong>{{ $errors->first('patch') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('delete') ? ' has-error' : '' }}">
                {{ Form::label('delete', trans('runsite::models.methods.DELETE')) }}
                @if($methods->validMethod('delete') !== true)
                    &nbsp; <i class="fa fa-warning text-danger"></i> &nbsp; <strong class="text-danger">{{ $methods->validMethod('delete') }}</strong>
                @endif
                {{ Form::text('delete', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                @if ($errors->has('delete'))
                    <span class="help-block">
                        <strong>{{ $errors->first('delete') }}</strong>
                    </span>
                @endif
            </div>

            @if(Auth::user()->access()->application($application)->edit)
                <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.methods.Update') }}</button>
            @endif

        {!! Form::close() !!}
    </div>
@endsection
