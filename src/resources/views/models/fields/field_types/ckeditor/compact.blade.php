<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<textarea 
			maxlength="{{ $field->getLength() }}" 
			style="height: 295px" 
			class="form-control input-sm" 
			name="{{ $field->name }}[{{ $language->id }}]" 
			id="{{ $field->name }}-{{ $language->id }}"
			>{{ old($field->name.'.'.$language->id) ?: $value }}</textarea>

		<script>
			CKEDITOR.replace( '{{ $field->name }}-{{ $language->id }}', {
				toolbar: 'compact'
			} );
		</script>

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
