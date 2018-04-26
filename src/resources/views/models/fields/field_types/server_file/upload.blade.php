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
		<div class="xs-pb-5">
			<a href="{{ $value->url() }}" class="btn btn-default" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> {{ trans('runsite::models.fields.Open') }}</a>
		</div>
			<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" value="{{ $value->value }}">
		@endif

		<div class="input-image-wrapper">
			<span>
				<i class="fa fa-file-text input-image-icon-wait"></i>
				<i class="fa fa-check input-image-icon-selected hidden text-green animated fadeInUp"></i>
				&nbsp;{{ trans('runsite::models.fields.Select file') }}
			</span>
			<input 
				type="file" 
				class="input-image" 
				name="{{ $field->name }}[{{ $language->id }}]" 
				onchange="$(this).parent().find('span .input-image-icon-wait').addClass('hidden'); $(this).parent().find('span .input-image-icon-selected').removeClass('hidden'); $(this).parent().addClass('input-image-selected');"
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
