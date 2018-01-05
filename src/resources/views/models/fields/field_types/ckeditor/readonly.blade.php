<div class="form-group has-feedback">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		{!! $value !!}

		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
