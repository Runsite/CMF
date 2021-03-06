<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
	<div class="col-sm-10">
		<div class="input-group input-group-sm">
			<input 
				type="text" 
				class="form-control input-sm input-date" 
				name="{{ $field->name }}[{{ $language->id }}]" 
				id="{{ $field->name }}-{{ $language->id }}" 
				value="{{ old($field->name.'.'.$language->id) ?: $value }}">
			<span class="input-group-btn">
				<button class="btn btn-primary ripple" onclick="$(this).parent().parent().find('input').val('')" type="button">
					<i class="fa fa-times"></i>
				</button>
			</span>
		</div>

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
