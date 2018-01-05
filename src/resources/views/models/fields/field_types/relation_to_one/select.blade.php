<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">
		<select 
			class="form-control input-sm" 
			style="width: 100%;" 
			
			name="{{ $field->name }}[{{ $language->id }}]" 
			id="{{ $field->name }}-{{ $language->id }}">
			<option value="">---</option>

			@foreach($field->getAvailableRelationValues($language) as $availableValue)
				<option 
					
					@if(isset($value) and $value and $value->node_id == $availableValue->node_id)
						selected 
					@endif

				 value="{{ $availableValue->node_id }}">{{ $availableValue->name }}</option>
			@endforeach

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
