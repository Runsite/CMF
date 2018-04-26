<div class="form-group has-feedback {{ $errors->has($field->name.'.'.$language->id) ? ' has-error' : '' }}">
	<label class="col-sm-2" for="{{ $field->name }}-{{ $language->id }}">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
	<div class="col-sm-10">

		@if($value)

			@foreach($value as $relation)
				<span class="label label-default" style="{{ isset($relation->system_bg_color) ? 'background-color: '.$relation->system_bg_color.'; ' : null }} {{ isset($relation->system_color) ? 'color: '.$relation->system_color : null }}">
					{{ str_limit($relation->name, 35) }}
				</span> &nbsp;
			@endforeach
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
