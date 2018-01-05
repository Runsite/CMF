<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]">

		@if($value and $value->value)
			<img src="{{ $value->min() }}" class="img-responsive">
			<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" value="{{ $value->value }}">
		@endif

		<input 
			type="file" 
			class="btn btn-default" 
			name="{{ $field->name }}[{{ $language->id }}]" 
			id="{{ $field->name }}-{{ $language->id }}">

		@if($value and $value->value)
			<label>
				<input type="checkbox" name="{{ $field->name }}[{{ $language->id }}]" value="">
				{{ trans('runsite::nodes.Remove') }}
			</label>
		@endif

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
