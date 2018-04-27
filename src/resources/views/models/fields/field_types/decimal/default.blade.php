<div class="form-group">
    <label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">
    	{{ $field->display_name }}
    	@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
    </label>
    <div class="col-sm-10">
        <input 
            type="text" 
            class="form-control input-sm" 
            name="{{ $field->name }}[{{ $language->id }}]" 
            value="{{ old($field->name[$language->id]) ?: $dynamic->where('language_id', $language->id)->first()->{$field->name} }}">
    </div>
</div>
