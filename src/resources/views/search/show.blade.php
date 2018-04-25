@extends('runsite::layouts.app')

@section('app')
	<div class="content">
		<div class="nav-tabs-custom">

			<ul class="nav nav-tabs nav-tabs-autochange">
				@foreach($searchableModels as $searchableModel)
					@if($searchableModel->resultsCount)
						<li class="{{ $searchableModel->id == $model->id ? 'active' : null }}">
							<a href="{{ route('admin.search.show', ['search_key'=>$search_key, 'model'=>$searchableModel]) }}" class="ripple remember-scroll-position" data-ripple-color="#ccc">
								{{ $searchableModel->display_name_plural }} 
								<span class="label label-default">{{ $searchableModel->resultsCount }}</span>
							</a>
						</li>
					@endif
				@endforeach
			</ul>

			<div class="tab-content no-padding">
				<div class="tab-pane active">
					<div class="xs-p-15">
						<div class="row">
							<div class="col-md-6 col-lg-4">
								<div class="form-group">
									<label for="search_key">{{ trans('runsite::search.Search') }}</label>
									<div class="input-group input-group-sm" id="global-search">
										<input type="text" class="form-control input-sm" name="search_key" id="search_key" value="{{ $search_key }}">
										<div class="input-group-btn">
											<button class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					@if(count($results))
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										@foreach($model->fields->where('is_visible_in_nodes_list', true) as $field)
											<th>
												<small>{{ $field->display_name }}</small>
												{{-- @if($field->type()::$needField)
													{!! Form::open(['url'=>route('admin.nodes.edit', ['node'=>$node, 'depended_model_id'=>$depended_model->id]), 'method'=>'get', 'style'=>'display: inline']) !!}
														<input type="hidden" name="orderby" value="{{ $field->name }}">
														<div class="btn-group xs-ml-5">
															<button type="submit" name="direct" value="asc" class="btn btn-default btn-xs ripple {{ ($orderField == $field->name and $orderDirection == 'asc') ? 'active' : null }}" data-ripple-color="#5d5d5d" style="padding: 2px; font-size: 0px;">
																<i class="fa fa-caret-up" style="font-size: 11px;"></i>
															</button>
															<button type="submit" name="direct" value="desc" class="btn btn-default btn-xs ripple {{ ($orderField == $field->name and $orderDirection == 'desc') ? 'active' : null }}" data-ripple-color="#5d5d5d" style="padding: 2px; font-size: 0;">
																<i class="fa fa-caret-down" style="font-size: 11px;"></i>
															</button>
														</div>
													{!! Form::close() !!}
												@endif --}}
											</th>
										@endforeach
									</tr>
								</thead>
								<tbody>
									@foreach($results as $result)
										<tr>
											@foreach($model->fields->where('is_visible_in_nodes_list', true) as $field)
												<td>
													@include('runsite::models.fields.field_types.'.$field->type()::$displayName.'._view', ['child'=>$result])
												</td>
											@endforeach
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<div class="xs-pl-15">
							{!! $results->links() !!}
						</div>
					@else
						<div class="xs-pl-15 xs-pr-15 xs-pb-15">
							<div class="alert alert-warning xs-mb-0">
								{{ trans('runsite::search.Nothing was found') }}
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
<script>
	$(function() {
		var startSearch = function()
		{
			var search_key = $('#global-search input[name=search_key]').val();
			window.location.href = '{{ url('admin/search') }}/' + search_key + '/{{ $model->id }}';
		};


		$('#global-search input').on('keypress', function(e) {
			if(e.which == 13)
			{
				startSearch();
			}
		});

		$('#global-search button').on('click', startSearch);
	});
</script>
@endsection
