@if($child->{$field->name})

	@php($relations = $child->{$field->name})

	@if($relations)
		@foreach($relations as $relation)
			<span class="label label-default" style="{{ isset($relation->system_bg_color) ? 'background-color: '.$relation->system_bg_color.'; ' : null }} {{ isset($relation->system_color) ? 'color: '.$relation->system_color : null }}">
				{{ str_limit($relation->name, 35) }}
			</span> &nbsp;
		@endforeach
	@endif
	
@endif
