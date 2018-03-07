@if($field->name == 'name')
	<a style="margin-left: -10px;" class="ripple btn" data-ripple-color="#347ffb" href="{{ route('admin.nodes.edit', ['id'=>$child->node_id]) }}">
		<i class="fa fa-edit"></i>
		{{ str_limit($child->{$field->name}, 50) }}
	</a>
@else 
	{{ str_limit($child->{$field->name}, 50) }}
@endif
