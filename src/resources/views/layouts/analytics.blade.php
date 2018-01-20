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

</ul>
@endsection

@section('app')


<div class="content">
	<div class="nav-tabs-custom">

		<ul class="nav nav-tabs nav-tabs-autochange">
			@foreach($depended_models as $k=>$depended_model_item)
				<li class="{{ $depended_model_item->id == $depended_model->id ? 'active' : null }}">
					<a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.nodes.analytics.show', ['node'=>$node, 'depended_model'=>$depended_model_item]) }}">{{ $depended_model_item->display_name }}</a>
				</li>
			@endforeach
		</ul>
		
		<div class="tab-content">
			@yield('analytic')
		</div>

	</div>
</div>

@endsection
