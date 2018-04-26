<div class="form-group">
	<label class="col-sm-2" for="">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
	<div class="col-sm-10">
		<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" checked value="0">
		<div class="runsite-checkbox">
			<input 
				type="checkbox" 
				value="1"
				id="{{ $field->name }}-{{ $language->id }}"
				name="{{ $field->name }}[{{ $language->id }}]"
				{{ (old($field->name.'.'.$language->id) or $value) ? 'checked' : null }}
				>
				<label for="{{ $field->name }}-{{ $language->id }}"></label>
		</div>
		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
