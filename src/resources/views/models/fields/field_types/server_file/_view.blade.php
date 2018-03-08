@if($child->{$field->name}->value)
	<a href="{{ $child->{$field->name}->url() }}" class="btn btn-default btn-xs" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> {{ trans('runsite::models.fields.Open') }}</a>
@endif
