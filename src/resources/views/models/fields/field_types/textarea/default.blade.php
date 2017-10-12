<div class="form-group">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<textarea 
			rows="3"
			class="form-control input-sm"
			name="{{ $field->name }}[{{ $language->id }}]"
			id="{{ $field->name }}-{{ $language->id }}"
			>{{ old($field->name.'['.$language->id.']') ?: $value }}</textarea>

		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>