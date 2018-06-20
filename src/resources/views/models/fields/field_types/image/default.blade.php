<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
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
				&nbsp;{{ trans('runsite::models.fields.Select file') }} <small class="text-muted">({{ trans('runsite::models.fields.Max') }}. {{ $field->type()::resolveMaxUploadSize($field)->to('megabyte')->format(2,'.',',') }} Mb)</small>
			</span>
			<input 
				type="file" 
				accept="image/*" 
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
