<div class="form-group">
	<label class="col-sm-2" for="">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" checked value="0">
		<div class="runsite-checkbox">
			<input 
				type="checkbox" 
				value="1"
				id="{{ $field->name }}-{{ $language->id }}"
				name="{{ $field->name }}[{{ $language->id }}]"
				{{ (old($field->name.'.'.$language->id) or $value) ? 'checked' : null }}
				>
				<label for="{{ $field->name }}-{{ $language->id }}"></label>
		</div>
		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
