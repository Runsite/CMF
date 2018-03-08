@if($child->{$field->name})

	@php($relation = $child->{$field->name})

	@if($relation)
		<span class="label label-default" style="{{ isset($relation->bg_color) ? 'background-color: '.$relation->bg_color.'; ' : null }} {{ isset($relation->color) ? 'color: '.$relation->color : null }}">
			{{ str_limit($relation->name, 35) }}
		</span>
	@endif
	
@endif
