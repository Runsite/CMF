@if($child->{$field->name}->value)
	<img src="{{ $child->{$field->name}->min() }}" class="img-responsive thumbnail">
@endif
