

<div class="form-group {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">{{ $field->display_name }}</label>
	<div class="col-sm-10">

		<input type="hidden" name="{{ $field->name }}[{{ $language->id }}]" value="">

		@if(isset($value) and $value->relations())
			@php($relationsArr = $value->relations()->pluck('node_id')->toArray())
		@endif
		
		@foreach($field->getAvailableRelationValues($language) as $availableValue)
			<input type="hidden" name="{{ $field->name }}[{{ $language->id }}][{{ $availableValue->node_id }}]" value="">
			<label>
				<input 
				
				@if($value and isset($relationsArr) and $value->relations() and in_array($availableValue->node_id, $relationsArr))
					checked 
				@endif

				type="checkbox" name="{{ $field->name }}[{{ $language->id }}][{{ $availableValue->node_id }}]" value="{{ $availableValue->node_id }}"> {{ $availableValue->name }}
			</label>
			<br>
		@endforeach


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
