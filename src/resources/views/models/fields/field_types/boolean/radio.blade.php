<div class="form-group">
	<label class="col-sm-2" for="">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
	<div class="col-sm-10">
		<input 
			type="radio" 
			value="1"
			name="{{ $field->name }}[{{ $language->id }}]"
			id="{{ $field->name }}-{{ $language->id }}-1"
			{{ (old($field->name[$language->id]) == 1 or $dynamic->where('language_id', $language->id)->first()->{$field->name} == 1) ? 'checked' : null }}
			>
		<label for="{{ $field->name }}-{{ $language->id }}-1">
			{{ trans('runsite::models.fields.Yes') }}
		</label>

		<input 
			type="radio" 
			value="0"
			name="{{ $field->name }}[{{ $language->id }}]"
			id="{{ $field->name }}-{{ $language->id }}-0"
			{{ (old($field->name[$language->id]) === 0 or $dynamic->where('language_id', $language->id)->first()->{$field->name} == 0) ? 'checked' : null }}
			>
		<label for="{{ $field->name }}-{{ $language->id }}-0">
			{{ trans('runsite::models.fields.No') }}
		</label>

		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
