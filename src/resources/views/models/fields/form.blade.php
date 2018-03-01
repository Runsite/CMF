<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            {{ Form::label('name', trans('runsite::models.Name')) }}
            {{ Form::text('name', null, ['class'=>'form-control input-sm', 'autofocus'=>'true', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
            <small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.fields.The name of database field') }}</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('display_name') ? ' has-error' : '' }}">
            {{ Form::label('display_name', trans('runsite::models.Display name')) }}
            {{ Form::text('display_name', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
            @if ($errors->has('display_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('display_name') }}</strong>
                </span>
            @endif
            <small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.fields.Is visible in admin panel') }}</small>
        </div>
    </div>
</div>





<div class="form-group {{ $errors->has('hint') ? ' has-error' : '' }}">
    {{ Form::label('hint', trans('runsite::models.fields.Hint')) }}
    {{ Form::text('hint', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
    @if ($errors->has('hint'))
        <span class="help-block">
            <strong>{{ $errors->first('hint') }}</strong>
        </span>
    @endif
    <small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.fields.Appears under the field in the admin panel') }}</small>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('type_id') ? ' has-error' : '' }}">
            {{ Form::label('type_id', trans('runsite::models.fields.Field type')) }}
            <select name="type_id" id="type_id" class="form-control input-sm" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
                @foreach($field->types as $type_id=>$type)
                    <option @if(isset($field->type_id) and $type_id == $field->type_id) selected @endif value="{{ $type_id }}">{{ $type::$displayName }}</option>
                @endforeach
            </select>
            @if ($errors->has('type_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('type_id') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('group_id') ? ' has-error' : '' }}">
            {{ Form::label('group_id', trans('runsite::models.fields.Group')) }}
            <select name="group_id" id="group_id" class="form-control input-sm" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
                <option @if(isset($field) and $field->group_id == null) selected @endif value="">{{ trans('runsite::models.fields.Without group') }}</option>
                @foreach($model->groups as $group)
                    <option @if(isset($field) and $field->group_id == $group->id) selected @endif value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
            @if ($errors->has('group_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('group_id') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('is_common') ? ' has-error' : '' }}">
            {{ Form::label('is_common', trans('runsite::models.fields.Is common for all languages')) }}
            <input type="hidden" name="is_common" value="0">
            <div class="runsite-checkbox">
                <input type="checkbox" value="1" {{ (isset($field) and $field->is_common) ? 'checked' : null }} id="is_common" name="is_common" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
                <label for="is_common"></label>
            </div>
            @if ($errors->has('is_common'))
                <span class="help-block">
                    <strong>{{ $errors->first('is_common') }}</strong>
                </span>
            @endif
            <small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.fields.Common field for all languages: the control will only be displayed in the primary language tab, but the value will be duplicated in all other languages') }}.</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('is_visible_in_nodes_list') ? ' has-error' : '' }}">
            {{ Form::label('is_visible_in_nodes_list', trans('runsite::models.fields.Is visible in nodes list')) }}
            <input type="hidden" name="is_visible_in_nodes_list" value="0">
            <div class="runsite-checkbox">
                <input type="checkbox" value="1" {{ (isset($field) and $field->is_visible_in_nodes_list) ? 'checked' : null }} id="is_visible_in_nodes_list" name="is_visible_in_nodes_list" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
                <label for="is_visible_in_nodes_list"></label>
            </div>
            @if ($errors->has('is_visible_in_nodes_list'))
                <span class="help-block">
                    <strong>{{ $errors->first('is_visible_in_nodes_list') }}</strong>
                </span>
            @endif
            <small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.fields.The value of the field will be displayed in the list of nodes') }}.</small>
        </div>
    </div>
</div>
