@extends('runsite::layouts.app')
@section('app')

		<div class="content">

			

			@yield('form-open')
				<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							@foreach($languages as $k=>$language)
								<li class="{{ !$k ? 'active' : null }}">
										<a href="#lang-{{ $language->id }}" data-toggle="tab"><span class="lang-xs" lang="{{ $language->locale }}"></span> {{ $language->display_name }}</a>
								</li>
							@endforeach
						</ul>
						<div class="tab-content">
							@yield('node')
						</div>
						
				</div>
			{!! Form::close() !!}

			@if($depended_models)
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						@foreach($depended_models as $depended_model_item)
							<li class="{{ $depended_model_item->id == $depended_model->id ? 'active' : null }}">
								<a href="{{ route('admin.nodes.edit', ['id'=>$node->id, 'depended_model_id'=>$depended_model_item->id]) }}">{{ $depended_model_item->display_name_plural }}</a>
							</li>
						@endforeach
					</ul>
					<div class="tab-content no-padding">

						<div class="tab-pane active">
							@if($depended_model)
								@if(count($children))
									<div class="table-responsive">
										<table class="table">
											<thead>
												<tr>
													@foreach($depended_model->fields as $field)
														<th>
															<small>{{ $field->display_name }}</small>
														</th>
													@endforeach
												</tr>
											</thead>
											<tbody>
												@foreach($children as $child)
													<tr>
														@foreach($depended_model->fields as $field)
															<td>
																@include('runsite::models.fields.field_types.'.$field->type()::$name.'._view')
															</td>
														@endforeach
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									{!! $children->links() !!}
								@else
									<div class="xs-p-15">
										<small class="text-muted">Empty</small>
									</div>
								@endif
								
							@endif
							
						</div>
					</div>
				</div>
			@endif
			
		</div>

@endsection