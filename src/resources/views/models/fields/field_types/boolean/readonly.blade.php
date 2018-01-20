<div class="form-group">
	<label class="col-sm-2" for="">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		@if($value)
			{{ trans('runsite::models.fields.Yes') }}
		@else 
			{{ trans('runsite::models.fields.No') }}
		@endif

		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
