@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($settings, ['url'=>route('admin.models.settings.update', $settings->model->id), 'method'=>'patch']) !!}
            <div class="form-group {{ $errors->has('show_in_admin_tree') ? ' has-error' : '' }}">
                {{ Form::hidden('show_in_admin_tree', 0) }}
                {{ Form::checkbox('show_in_admin_tree', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                {{ Form::label('show_in_admin_tree', trans('runsite::models.settings.Show in admin tree')) }}
                @if ($errors->has('show_in_admin_tree'))
                    <span class="help-block">
                        <strong>{{ $errors->first('show_in_admin_tree') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('use_response_cache') ? ' has-error' : '' }}">
                {{ Form::hidden('use_response_cache', 0) }}
                {{ Form::checkbox('use_response_cache', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                {{ Form::label('use_response_cache', trans('runsite::models.settings.Use response cache')) }}
                @if ($errors->has('use_response_cache'))
                    <span class="help-block">
                        <strong>{{ $errors->first('use_response_cache') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('nodes_ordering') ? ' has-error' : '' }}">
                {{ Form::label('nodes_ordering', trans('runsite::models.settings.Nodes ordering')) }}
                {{ Form::text('nodes_ordering', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                @if ($errors->has('nodes_ordering'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nodes_ordering') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('dynamic_model') ? ' has-error' : '' }}">
                {{ Form::label('dynamic_model', trans('runsite::models.settings.Dynamic model')) }}
                {{ Form::text('dynamic_model', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                @if ($errors->has('dynamic_model'))
                    <span class="help-block">
                        <strong>{{ $errors->first('dynamic_model') }}</strong>
                    </span>
                @endif
            </div>

            @if(Auth::user()->access()->application($application)->edit)
                <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.Update') }}</button>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
