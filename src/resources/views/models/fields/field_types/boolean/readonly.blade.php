<div class="form-group">
	<label class="col-sm-2" for="">
		{{ $field->display_name }}
		@if($userCanReadModels)
			<br><small class="text-muted" style="font-weight: normal">{{ $field->name }} <a href="{{ route('admin.models.fields.edit', ['model'=>$model, 'field'=>$field]) }}" class="text-red"><i class="fa fa-cog"></i></a></small>
		@endif
	</label>
	<div class="col-sm-10">
		@if($value)
			{{ trans('runsite::models.fields.Yes') }}
		@else 
			{{ trans('runsite::models.fields.No') }}
		@endif

		@if($field->hint)
			<div class="text-muted"><small>{{ $field->hint }}</small></div>
		@endif
	</div>
</div>
