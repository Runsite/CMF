@extends('runsite::layouts.models')

@section('model')
    <div class="xs-p-15 xs-pb-15">
        {!! Form::model($settings, ['url'=>route('admin.models.settings.update', $settings->model->id), 'method'=>'patch']) !!}

            <div class="form-group {{ $errors->has('nodes_ordering') ? ' has-error' : '' }}">
                {{ Form::label('nodes_ordering', trans('runsite::models.settings.Nodes ordering')) }}
                {{ Form::text('nodes_ordering', null, ['class'=>'form-control input-sm', 'autofocus'=>'true', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
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

            <div class="form-group {{ $errors->has('show_in_admin_tree') ? ' has-error' : '' }}">
                {{ Form::label('show_in_admin_tree', trans('runsite::models.settings.Show in admin tree')) }}
                <input type="hidden" name="show_in_admin_tree" value="0">
                <div class="runsite-checkbox">
                    {{ Form::checkbox('show_in_admin_tree', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                    <label for="show_in_admin_tree"></label>
                </div>
                @if ($errors->has('show_in_admin_tree'))
                    <span class="help-block">
                        <strong>{{ $errors->first('show_in_admin_tree') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('use_response_cache') ? ' has-error' : '' }}">
                {{ Form::label('use_response_cache', trans('runsite::models.settings.Use response cache')) }}
                <input type="hidden" name="use_response_cache" value="0">
                <div class="runsite-checkbox">
                    {{ Form::checkbox('use_response_cache', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                    <label for="use_response_cache"></label>
                </div>
                @if ($errors->has('use_response_cache'))
                    <span class="help-block">
                        <strong>{{ $errors->first('use_response_cache') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('slug_autogeneration') ? ' has-error' : '' }}">
                {{ Form::label('slug_autogeneration', trans('runsite::models.settings.Generate new slug automaticly')) }}
                <input type="hidden" name="slug_autogeneration" value="0">
                <div class="runsite-checkbox">
                    {{ Form::checkbox('slug_autogeneration', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
                    <label for="slug_autogeneration"></label>
                </div>
                @if ($errors->has('slug_autogeneration'))
                    <span class="help-block">
                        <strong>{{ $errors->first('slug_autogeneration') }}</strong>
                    </span>
                @endif
            </div>

            @if(Auth::user()->access()->application($application)->edit)
                <button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.Update') }}</button>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
