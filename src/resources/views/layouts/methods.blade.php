@extends('runsite::layouts.models')

@section('model')

	@if(count($methods->getControllers()))
		<div class="xs-p-15 xs-pb-0">
			<div class="btn-group">
				<a href="{{ route('admin.models.methods.edit', $model->id) }}" class="btn btn-{{ Route::current()->getName() == 'admin.models.methods.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.models.fields.edit') data-ripple-color="#898989" @endif>
					<i class="fa fa-pencil-square-o"></i> {{ trans('runsite::models.methods.Edit') }}
				</a>

				@foreach($methods->getControllers() as $controller)
					<a href="{{ route('admin.models.methods.controller', ['controller' => $controller, 'model'=>$model]) }}" class="btn btn-{{ (Route::current()->getName() == 'admin.models.methods.controller' and (isset($controller_name) and $controller_name == $controller)) ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.models.fields.edit') data-ripple-color="#898989" @endif> <i class="fa fa-code"></i> {{ $controller }}</a>
				@endforeach
			</div>
		</div>
	@endif

	@yield('methods')

@endsection
