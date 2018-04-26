<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
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
				toolbar: 'compact',
				language: '{{ config('app.locale') }}'
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
