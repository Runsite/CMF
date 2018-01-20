<div class="form-group has-feedback">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<textarea 
			rows="3" 
			class="form-control input-sm" 
			readonly 
			id="{{ $field->name }}-{{ $language->id }}"
			>{{ $value }}</textarea>
		<span class="fa fa-lock form-control-feedback" aria-hidden="true"></span>

		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
