<div class="form-group">
	<label class="col-sm-2" for="">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" checked value="0">
		<input 
			type="checkbox" 
			value="1"
			name="{{ $field->name }}[{{ $language->id }}]"
			{{ (old($field->name[$language->id]) or $dynamic->where('language_id', $language->id)->first()->{$field->name}) ? 'checked' : null }}
			>
		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
