@extends('runsite::layouts.models')

@section('model')
	<div class="xs-p-15">
		{!! Form::open(['url'=>route('admin.models.access.update', ['model_id'=>$model->id]), 'method'=>'patch']) !!}

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
						@foreach($groups as $group)
							<tr>
								<td>
									{{ $group->name }}
								</td>
								<td>
									<div class="runsite-checkbox">
										<input type="checkbox" data-group="{{ $group->id }}" data-access="read" id="{{ $group->id }}-read" name="groups[{{ $group->id }}][read]" @if($group->canSeeModel($model)) checked @endif @if($group->canCreateModel($model)) disabled @endif>
										<label for="{{ $group->id }}-read"></label>
									</div>
								</td>
								<td>
									<div class="runsite-checkbox">
										<input type="checkbox" data-group="{{ $group->id }}" data-access="edit" id="{{ $group->id }}-edit" name="groups[{{ $group->id }}][edit]" @if($group->canCreateModel($model)) checked @endif>
										<label for="{{ $group->id }}-edit"></label>
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
