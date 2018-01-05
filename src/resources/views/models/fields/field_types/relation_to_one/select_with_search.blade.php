<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<select 
			class="form-control input-sm relation-to-one-search" 
			style="width: 100%;" 
			data-related-model-name="{{ $field->findSettings('related_model_name')->value }}" 
			data-related-parent-node-id="{{ $field->findSettings('related_parent_node_id')->value }}" 
			name="{{ $field->name }}[{{ $language->id }}]" 
			id="{{ $field->name }}-{{ $language->id }}">
			<option value="">---</option>

			@if($value)
				<option value="{{ $value->node_id }}" selected>{{ $value->name }}</option>
			@endif
		</select>

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
