@extends('runsite::layouts.models')

@section('model')
	@if(! count($models))
		{{ trans('runsite::models.Models does not exist') }}
	@else
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ trans('runsite::models.Display name') }}</th>
						<th>{{ trans('runsite::models.Name') }}</th>
						<th>{{ trans('runsite::models.Delete') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($models as $k=>$modelItem)

						{{-- @if(! str_is('*_*', $modelItem->name))
							@php($prefix = $modelItem->name)
						@endif --}}

						<tr>
							<td>{{ $modelItem->id }}</td>
							<td>
								<a class="ripple" data-ripple-color="#333" href="{{ route('admin.models.edit', $modelItem->id) }}" style="display: block;">
									@if($k and str_is($models[$k-1]->prefix.'*', $modelItem->name))
										<small> <i class="fa fa-caret-right xs-ml-10"></i> {{ $modelItem->display_name }}</small>
										@if($modelItem->fields->count() <= 1)
											<br><span class="label label-danger xs-ml-10">{{ trans('runsite::models.This model has not useful fields') }}</span>
										@endif
									@else
										<b>{{ $modelItem->display_name }}</b>
										@if($modelItem->fields->count() <= 1)
											<br><span class="label label-danger">{{ trans('runsite::models.This model has not useful fields') }}</span>
										@endif
									@endif

									
									
								</a>
							</td>
							<td><span class="label label-default">{{ $modelItem->name }}</span></td>
							<td>
								<button {{ $modelItem->id == 1 ? 'disabled' : null }} type="button" class="btn btn-xs btn-danger ripple" data-toggle="modal" data-target="#destroy-model-{{ $modelItem->id }}"><i class="fa fa-trash"></i></button>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@endif

	@foreach($models as $modelItem)
	<div class="modal modal-danger fade" tabindex="-1" role="dialog" id="destroy-model-{{ $modelItem->id }}">
	  <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('runsite::models.fields.Close') }}"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">{{ trans('runsite::models.Are you sure') }}?</h4>
		  </div>
		  <div class="modal-body">
			<p>{{ trans('runsite::models.Are you sure you want to delete the model') }} "{{ $modelItem->display_name }}"?</p>
		  </div>
		  <div class="modal-footer">
			{!! Form::open(['url'=>route('admin.models.destroy', ['model'=>$modelItem]), 'method'=>'delete']) !!}
				<button type="submit" class="btn btn-default btn-sm ripple" data-ripple-color="#ccc">{{ trans('runsite::models.Delete model') }}</button>
			{!! Form::close() !!}
		  </div>
		</div>
	  </div>
	</div>
	@endforeach
@endsection
