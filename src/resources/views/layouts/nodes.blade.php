@extends('runsite::layouts.app')

@section('breadcrumbs')
<ul class="nav navbar-nav navbar-breadcrumbs hidden-xs">
	<li class="{{ request()->route('id') == 1 ? 'active' : null }} "><a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>1]) }}"><i class="fa fa-home"></i></a></li>
	
	@if(count($breadcrumbs) > 2)
		<li>
			<a data-toggle="dropdown" class="ripple" href="#"><small>...</small></a>
			<ul class="dropdown-menu">
				@foreach($breadcrumbs as $k=>$breadcrumb)
					<li class="{{ (++$k) > (count($breadcrumbs) - 2) ? 'visible-xs visible-sm' : null }}">
						<a href="{{ route('admin.nodes.edit', ['id'=>$breadcrumb->id]) }}">
							{{ str_limit($breadcrumb->dynamicCurrentLanguage()->first()->name, 20) }}
						</a>
					</li>
				@endforeach
			</ul>
		</li>
	@endif
	
	@foreach($breadcrumbs as $k=>$breadcrumb)
		@if((++$k) > (count($breadcrumbs) - 2))
			<li class="{{ request()->route('id') == $breadcrumb->id ? 'active' : null }} visible-md visible-lg">
				<a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>$breadcrumb->id]) }}">
					<small>{{ str_limit($breadcrumb->dynamicCurrentLanguage()->first()->name, 20) }}</small>
				</a>
			</li>
		@endif
	@endforeach

	@if(isset($depended_models) and $depended_models)
		<li>
			<a class="ripple" href="#" data-toggle="dropdown">
				<small><i class="fa fa-plus"></i></small>
			</a>
			<ul class="dropdown-menu dropdown-menu-right">
				@foreach($depended_models as $depended_model)
					<li><a href="{{ route('admin.nodes.create', ['model_id'=>$depended_model->id, 'parent_id'=>$node->id]) }}">{{ $depended_model->display_name }}</a></li>
				@endforeach
			</ul>
		</li>
	@endif
	
	@if(Route::current()->getName() == 'admin.nodes.create')
		<li>
			<a href="{{ route('admin.nodes.edit', ['node_id'=>$node->id]) }}" class="ripple">
				<small>
					<i class="fa fa-plus animated fadeOutDown" style="position: absolute;"></i>
					<i class="fa fa-times animated fadeInDown"></i>
				</small>
			</a>
		</li>
	@endif

</ul>
@endsection

@section('app')
	{!! Form::open(['url'=>route('admin.nodes.destroy', $node->id), 'method'=>'delete', 'style'=>'display: none;', 'id'=>'deleting-node-'.$node->id]) !!}
	{!! Form::close() !!}
		<div class="content">
			@yield('form-open')
				<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							@foreach($languages as $k=>$language)
								<li class="{{ $active_language_tab == $language->locale ? 'active' : null }}">
									<a href="#lang-{{ $language->id }}" data-toggle="tab"><span class="lang-xs" lang="{{ $language->locale }}"></span> {{ $language->display_name }}</a>
								</li>
							@endforeach
							<li class="pull-right">
								<a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
								<ul class="dropdown-menu">
									<li>
										
										<a href="javascript: void(0);" onclick="return confirm('Yes?') ? $('#deleting-node-{{ $node->id }}').submit() : false;">
											<span class="text-danger">
												<i class="fa fa-trash"></i> {{ trans('runsite::nodes.Delete') }}
											</span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
						<div class="tab-content">
							@yield('node')
						</div>
						
				</div>
			{!! Form::close() !!}

			@if(isset($depended_models) and $depended_models)
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