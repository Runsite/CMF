<div class="form-group has-feedback {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">

		<input 
			type="text" 
			class="form-control input-sm" 
			readonly 
			id="{{ $field->name }}-{{ $language->id }}" 
			value="{{ $value ? $value->name : null }}">
		<span class="fa fa-lock form-control-feedback" aria-hidden="true"></span>

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
