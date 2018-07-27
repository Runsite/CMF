<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
        <label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">
            {{ $field->display_name }}
            @if($userCanReadModels)
                <br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
            @endif
        </label>
        <div class="col-sm-10">
            <textarea 
                rows="3" 
                class="form-control input-sm field-code readonly" 
                name="{{ $field->name }}[{{ $language->id }}]" 
                id="{{ $field->name }}-{{ $language->id }}" 
                data-theme="{{ $field->findSettings('theme')->value }}" 
                data-language="{{ $field->findSettings('language')->value }}" 
                readonly="readonly" 
                >{{ old($field->name.'.'.$language->id) ?: $value }}</textarea>
            <pre id="pre-{{ $field->name }}-{{ $language->id }}" style="height: 150px;"></pre>
    
            @if($field->hint)
                <div class="text-muted"><small>{{ $field->hint }}</small></div>
            @endif
    
            @if ($errors->has($field->name.'.'.$language->id))
                <span class="help-block">
                    <strong>{{ $errors->first($field->name.'.'.$language->id) }}</strong>
                </span>
            @endif
        </div>
    </div>
    
