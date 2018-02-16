@extends('runsite::layouts.models')

@section('model')
	<div class="xs-p-15">
		<div class="row">
			<div class="col-lg-8">
				{!! Form::open(['url'=>route('admin.models.fields.access.update', ['model_id'=>$model->id, 'field_id'=>$field->id]), 'method'=>'patch']) !!}

					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>{{ trans('runsite::models.fields.access.Group name') }}</th>
									<th>{{ trans('runsite::models.fields.access.Read') }}</th>
									<th>{{ trans('runsite::models.fields.access.Edit') }}</th>
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
												<input type="checkbox" data-group="{{ $group->id }}" data-access="read" id="{{ $group->id }}-read" name="groups[{{ $group->id }}][read]" @if($group->canReadField($field->id)) checked @endif @if($group->canEditField($field->id)) disabled @endif {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
												<label for="{{ $group->id }}-read"></label>
											</div>
										</td>
										<td>
											<div class="runsite-checkbox">
												<input type="checkbox" data-group="{{ $group->id }}" data-access="edit" id="{{ $group->id }}-edit" name="groups[{{ $group->id }}][edit]" @if($group->canEditField($field->id)) checked @endif {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
												<label for="{{ $group->id }}-edit"></label>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					
					@if(Auth::user()->access()->application($application)->edit)
						<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.fields.access.Update') }}</button>
					@endif
				{!! Form::close() !!}
			</div>
			<div class="col-lg-4 visible-lg">
				@include('runsite::models.fields.other_fields')
			</div>
		</div>
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
