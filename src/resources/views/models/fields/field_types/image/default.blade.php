<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]">

		@if($value and $value->value)
			<img src="{{ $value->min() }}" class="img-responsive" style="margin-bottom: 15px;">
			<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" value="{{ $value->value }}">
		@endif

		<div class="input-image-wrapper">
			<span>
				<i class="fa fa-image"></i>
				<i class="fa fa-check hidden text-green animated fadeInUp"></i>
				&nbsp;{{ trans('runsite::models.fields.Select file') }}
			</span>
			<input 
				type="file" 
				class="input-image" 
				name="{{ $field->name }}[{{ $language->id }}]" 
				onchange="$(this).parent().find('span .fa-image').addClass('hidden'); $(this).parent().find('span .fa-check').removeClass('hidden'); $(this).parent().addClass('input-image-selected');"
				id="{{ $field->name }}-{{ $language->id }}">
		</div>
		

		@if($value and $value->value)
			<label style="display: block; margin-top: 5px;">
				<input type="checkbox" name="{{ $field->name }}[{{ $language->id }}]" value="">
				&nbsp;{{ trans('runsite::nodes.Remove') }}
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
