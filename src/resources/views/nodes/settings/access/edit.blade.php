@extends('runsite::layouts.nodes')

@section('node')
	<div class="xs-p-15">
		{!! Form::open(['url'=>route('admin.nodes.settings.access.update', ['node'=>$node]), 'method'=>'patch']) !!}

			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>{{ trans('runsite::nodes.access.Group name') }}</th>
							<th>{{ trans('runsite::nodes.access.Read') }}</th>
							<th>{{ trans('runsite::nodes.access.Edit') }}</th>
							<th>{{ trans('runsite::nodes.access.Apply to all subnodes') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($groups as $group)
							<tr>
								<td>
									{{ $group->name }}
								</td>
								<td>
									<div class="runsite-checkbox">
										<input type="checkbox" data-group="{{ $group->id }}" data-access="read" id="{{ $group->id }}-read" name="groups[{{ $group->id }}][read]" @if($group->canRead($node)) checked @endif @if($group->canEdit($node)) disabled @endif>
										<label for="{{ $group->id }}-read"></label>
									</div>
								</td>
								<td>
									<div class="runsite-checkbox">
										<input type="checkbox" data-group="{{ $group->id }}" data-access="edit" id="{{ $group->id }}-edit" name="groups[{{ $group->id }}][edit]" @if($group->canEdit($node)) checked @endif>
										<label for="{{ $group->id }}-edit"></label>
									</div>
								</td>
								<td>
									<div class="runsite-checkbox">
										<input type="checkbox" id="{{ $group->id }}-subnodes" name="groups[{{ $group->id }}][subnodes]">
										<label for="{{ $group->id }}-subnodes"></label>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.access.Update') }}</button>
		{!! Form::close() !!}
	</div>
@endsection

@section('js')
<script>
	$(function(){
		$('[data-access="edit"]').on('change', function(){
			var read = $('[data-access="read"][data-group="'+$(this).data('group')+'"]');
			if($(this).is(':checked'))
			{
				read.prop('checked', true);
				read.prop('disabled', true);
			}
			else
			{
				read.prop('disabled', false);
			}
		});
	});
</script>
@endsection
