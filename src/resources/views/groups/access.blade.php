@extends('runsite::layouts.users')

@section('user')
<div class="xs-p-15">
	{!! Form::open(['url'=>route('admin.groups.access.update', ['group'=>$group]), 'method'=>'patch']) !!}

		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>{{ trans('runsite::models.access.Group name') }}</th>
						<th>{{ trans('runsite::models.access.Read') }}</th>
						<th>{{ trans('runsite::models.access.Edit') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($applications as $application)
						<tr>
							<td>
								<i class="fa fa-cog text-red"></i> {{ $application->name }}
							</td>
							<td>
								<div class="runsite-checkbox">
									<input type="checkbox" data-group="{{ $application->id }}" data-access="read" id="{{ $application->id }}-read" name="applications[{{ $application->id }}][read]" @if($group->canReadApplication($application)) checked @endif @if($group->canManageApplication($application)) disabled @endif>
									<label for="{{ $application->id }}-read"></label>
								</div>
							</td>
							<td>
								<div class="runsite-checkbox">
									<input type="checkbox" data-group="{{ $application->id }}" data-access="edit" id="{{ $application->id }}-edit" name="applications[{{ $application->id }}][edit]" @if($group->canManageApplication($application)) checked @endif>
									<label for="{{ $application->id }}-edit"></label>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.access.Update') }}</button>
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
