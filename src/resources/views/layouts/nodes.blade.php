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
							{{ str_limit($breadcrumb->dynamicCurrentLanguage()->first()->name ?? ($breadcrumb->model->display_name . ' ' . $breadcrumb->id), 20) }}
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
					<small>{{ str_limit($breadcrumb->dynamicCurrentLanguage()->first()->name ?? ($breadcrumb->model->display_name . ' ' . $breadcrumb->id), 20) }}</small>
				</a>
			</li>
		@endif
	@endforeach

	@if(isset($depended_models_create) and $depended_models_create and Auth::user()->access()->node($node)->edit)
		<li>
			<a class="ripple" href="#" data-toggle="dropdown">
				<small><i class="fa fa-plus"></i></small>
			</a>
			<ul class="dropdown-menu dropdown-menu-right">
				@foreach($depended_models_create as $depended_models_create_item)
					<li><a href="{{ route('admin.nodes.create', ['model_id'=>$depended_models_create_item->id, 'parent_id'=>$node->id]) }}">{{ $depended_models_create_item->display_name }}</a></li>
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

	
		<div class="content">
			@yield('form-open')
				<div class="nav-tabs-custom">
						
						<ul class="nav nav-tabs">
							@if(isset($languages))
								@foreach($languages as $k=>$language)
									<li class="{{ $active_language_tab == $language->locale ? 'active' : null }}">
										<a href="#lang-{{ $language->id }}" data-toggle="tab">
											{{-- <span class="lang-xs" lang="{{ $language->locale }}"></span> --}}
											{{ $language->display_name }}
											@foreach($model->fields as $field)
												@if($errors->has($field->name . '.' . $language->id))
													&nbsp;<i class="fa fa-exclamation-circle text-danger animated tada" aria-hidden="true"></i>
													@break
												@endif
											@endforeach

											@if(isset($dynamic) and ! $dynamic->where('language_id', $language->id)->first())
												<i class="fa fa-minus-circle text-red" aria-hidden="true"></i>
											@endif
											{{-- @if($node->getErrorsCountByLanguageId($language->id, $errors))
												<span class="label label-danger">
													{{ $node->getErrorsCountByLanguageId($language->id, $errors) }}
												</span>
											@endif --}}
										</a>
									</li>
								@endforeach
							@endif

							@if($node->canBeRemoved())
								<li class="pull-right">
									<a href="#" data-toggle="modal" data-target="#destroy-current-node"><i class="fa fa-trash text-danger"></i></a>
								</li>
							@endif
							
							@if(Auth::user()->canEdit($node))
								<li class="pull-right">
									<a href="#" data-toggle="dropdown"><i class="fa fa-cog"></i></a>
									<ul class="dropdown-menu">
										<li>
											<a href="{{ route('admin.nodes.settings.edit', $node) }}">
												{{ trans('runsite::nodes.settings.Settings') }}
											</a>
										</li>

										<li>
											<a href="{{ route('admin.nodes.settings.paths.index', $node) }}">
												{{ trans('runsite::nodes.settings.Paths') }}
											</a>
										</li>

										<li>
											<a href="{{ route('admin.nodes.settings.dependencies.index', $node) }}">
												{{ trans('runsite::nodes.settings.Dependencies') }}
											</a>
										</li>

										<li>
											<a href="{{ route('admin.nodes.settings.access.edit', $node) }}">
												{{ trans('runsite::nodes.settings.Access') }}
											</a>
										</li>

										<li>
											<a href="{{ route('admin.nodes.settings.methods.edit', $node) }}">
												{{ trans('runsite::nodes.settings.Methods') }}
											</a>
										</li>
									</ul>
								</li>
							@endif

							@if(Route::current()->getName() == 'admin.nodes.edit')
								

								@if($node->methods->get or $node->model->methods->get)
									<li class="pull-right">
										<a href="#" data-toggle="dropdown"><i class="fa fa-eye"></i></a>
										<ul class="dropdown-menu">
											@foreach($languages as $languagePreview)
												<li>
													<a href="{{ lPath($node->path->name, $languagePreview->locale) }}?mode=preview" target="_blank">
														{{ $languagePreview->display_name }}
													</a>
												</li>
											@endforeach
										</ul>
									</li>
								
									<li class="pull-right">
										<a href="#" data-toggle="dropdown"><i class="fa fa-qrcode"></i></a>
										<ul class="dropdown-menu">
											@foreach($languages as $languageQR)
												<li>
													<a href="{{ route('admin.nodes.qr-code', ['node'=>$node, 'language'=>$languageQR]) }}">
														{{ $languageQR->display_name }}
													</a>
												</li>
											@endforeach
										</ul>
									</li>
								@endif
								
							@endif
						</ul>
						
						<div class="tab-content">
							@yield('node')
						</div>
						
				</div>
			{!! Form::close() !!}

			@if(isset($depended_models) and $depended_models and Route::current()->getName() == 'admin.nodes.edit')
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs nav-tabs-autochange">
						@foreach($depended_models as $depended_model_item)
							<li class="{{ $depended_model_item->id == $depended_model->id ? 'active' : null }}">
								<a class="ripple remember-scroll-position" data-ripple-color="#ccc" href="{{ route('admin.nodes.edit', ['id'=>$node->id, 'depended_model_id'=>$depended_model_item->id]) }}">{{ $depended_model_item->display_name_plural }}</a>
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
													@foreach($depended_model->fields->where('is_visible_in_nodes_list', true) as $field)
														<th>
															<small>{{ $field->display_name }}</small>
															@if($field->type()::$needField)
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
															@endif
														</th>
													@endforeach
													@if(str_is('position *', $depended_model->settings->nodes_ordering))
														<th>
															<small>{{ trans('runsite::nodes.Position') }}</small>
															{!! Form::open(['url'=>route('admin.nodes.edit', ['node'=>$node, 'depended_model_id'=>$depended_model->id]), 'method'=>'get', 'style'=>'display: inline']) !!}
																<input type="hidden" name="orderby" value="position">
																<div class="btn-group xs-ml-5">
																	<button type="submit" name="direct" value="asc" class="btn btn-default btn-xs ripple {{ ($orderField == 'position' and $orderDirection == 'asc') ? 'active' : null }}" data-ripple-color="#5d5d5d" style="padding: 2px; font-size: 0px;">
																		<i class="fa fa-caret-up" style="font-size: 11px;"></i>
																	</button>
																	<button type="submit" name="direct" value="desc" class="btn btn-default btn-xs ripple {{ ($orderField == 'position' and $orderDirection == 'desc') ? 'active' : null }}" data-ripple-color="#5d5d5d" style="padding: 2px; font-size: 0;">
																		<i class="fa fa-caret-down" style="font-size: 11px;"></i>
																	</button>
																</div>
															{!! Form::close() !!}
														</th>
													@endif
													<th><small>{{ trans('runsite::nodes.Actions') }}</small></th>
												</tr>
											</thead>
											<tbody>
												@foreach($children as $child)
													<tr class="{{ (Session::has('highlight') and Session::get('highlight') == $child->node_id) ? 'success' : null }}">
														@foreach($depended_model->fields->where('is_visible_in_nodes_list', true) as $field)
															<td>
																@include('runsite::models.fields.field_types.'.$field->type()::$displayName.'._view')
															</td>
														@endforeach
														@if(str_is('position *', $depended_model->settings->nodes_ordering))
															<td>
																@if($orderField == 'position' and $orderDirection == 'asc')
																	<div class="btn-group">
																		{!! Form::open(['url'=>route('admin.nodes.move.up', ['node'=>$child->node_id, 'depended_model_id'=>$depended_model->id]), 'method'=>'patch', 'style'=>'display: inline;']) !!}
																			<button type="submit" {{ $child->position == 1 ? 'disabled' : null }} class="btn btn-sm btn-default ripple remember-scroll-position" data-ripple-color="#5d5d5d">
																				<i class="fa fa-caret-{{ str_is('* asc', $depended_model->settings->nodes_ordering) ? 'up' : 'down' }}"></i>
																			</button>
																		{!! Form::close() !!}
																		{!! Form::open(['url'=>route('admin.nodes.move.down', ['node'=>$child->node_id, 'depended_model_id'=>$depended_model->id]), 'method'=>'patch', 'style'=>'display: inline;']) !!}
																			<button type="submit" {{ $child->position == $children_total_count ? 'disabled' : null }} class="btn btn-sm btn-default ripple remember-scroll-position" data-ripple-color="#5d5d5d">
																				<i class="fa fa-caret-{{ str_is('* asc', $depended_model->settings->nodes_ordering) ? 'down' : 'up' }}"></i>
																			</button>
																		{!! Form::close() !!}
																	</div>
																@endif
																
															</td>
														@endif
														<td>
															<a href="{{ route('admin.nodes.edit', $child->node_id) }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-edit"></i></a>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class="xs-pl-15">
										{!! $children->appends(['orderby'=>$orderField, 'direct'=>$orderDirection])->links() !!}
									</div>
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


		@if($node->canBeRemoved())
			<div class="modal modal-danger fade" tabindex="-1" role="dialog" id="destroy-current-node">
		      <div class="modal-dialog modal-sm" role="document">
		        <div class="modal-content">
		          <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('runsite::nodes.Close') }}"><span aria-hidden="true">&times;</span></button>
		            <h4 class="modal-title">{{ trans('runsite::nodes.Are you sure') }}?</h4>
		          </div>
		          <div class="modal-body">
		            <p>{{ trans('runsite::nodes.Are you sure you want to delete the node') }}?</p>
		          </div>
		          <div class="modal-footer">
		            {!! Form::open(['url'=>route('admin.nodes.destroy', ['node'=>$node]), 'method'=>'delete']) !!}
		                <button type="submit" class="btn btn-default btn-sm ripple" data-ripple-color="#ccc">{{ trans('runsite::nodes.Delete node') }}</button>
		            {!! Form::close() !!}
		          </div>
		        </div>
		      </div>
		    </div>
		@endif

@endsection
