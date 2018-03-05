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
						<th>Display name</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach($models as $k=>$modelItem)

						@if(! str_is('*_*', $modelItem->name))
							@php($prefix = $modelItem->name)
						@endif

						<tr>
							<td>{{ $modelItem->id }}</td>
							<td>
								<a class="ripple" data-ripple-color="#333" href="{{ route('admin.models.edit', $modelItem->id) }}" style="display: block;">
									@if(isset($prefix) and str_is('*_*', $modelItem->name) and str_is($prefix . '_*', $modelItem->name))
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
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div class="xs-pl-15">
			{!! $models->links() !!}
		</div>
	@endif
@endsection
