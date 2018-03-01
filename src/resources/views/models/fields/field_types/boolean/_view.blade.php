@if($child->{$field->name})
	<span class="label label-success">{{ trans('runsite::models.fields.Yes') }}</span>
@else
	<span class="label label-danger">{{ trans('runsite::models.fields.Yes') }}</span>
@endif
