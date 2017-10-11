@extends('runsite::layouts.app')
@section('app')

    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs nav-tabs-autochange">
              <li class="{{ Route::current()->getName() == 'admin.models.index' ? 'active' : null }}">
                  <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.index') }}"><i class="fa fa-cogs"></i> {{ trans('runsite::models.Models') }}</a>
              </li>

              @if(Route::current()->getName() != 'admin.models.index' and Route::current()->getName() != 'admin.models.create' and isset($model))

                  <li class="{{ Route::current()->getName() == 'admin.models.edit' ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.edit', $model->id) }}"><i class="fa fa-pencil-square-o"></i> {{ trans('runsite::models.Edit') }}</a>
                  </li>
                  <li class="{{ Route::current()->getName() == 'admin.models.settings.edit' ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.settings.edit', $model->id) }}"><i class="fa fa-cog"></i> {{ trans('runsite::models.Settings') }}</a>
                  </li>
                  <li class="{{ Route::current()->getName() == 'admin.models.methods.edit' ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.methods.edit', $model->id) }}">
                        <i class="fa fa-window-restore"></i> {{ trans('runsite::models.Methods') }}
                        @if($model->methodsCount())
                          <span class="label label-default">{{ $model->methodsCount() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.fields.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.fields.index', $model->id) }}">
                        <i class="fa fa-th-list"></i> {{ trans('runsite::models.Fields') }}
                        @if($model->fields->count())
                          <span class="label label-default">{{ $model->fields->count() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.groups.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.groups.index', $model->id) }}">
                        <i class="fa fa-object-group"></i> {{ trans('runsite::models.Groups') }}
                        @if($model->groups->count())
                          <span class="label label-default">{{ $model->groups->count() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.dependencies.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.dependencies.index', $model->id) }}">
                        <i class="fa fa-sitemap"></i> {{ trans('runsite::models.Dependencies') }}
                        @if($model->dependencies->count())
                          <span class="label label-default">{{ $model->dependencies->count() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.access.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.access.edit', $model->id) }}"><i class="fa fa-lock"></i> {{ trans('runsite::models.Access') }}</a>
                  </li>
                  
                  
              @else 
                  @if(Auth::user()->access()->application($application)->edit)
                      <li class="{{ Route::current()->getName() == 'admin.models.create' ? 'active' : null }}">
                          <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.create') }}"><i class="fa fa-plus"></i> {{ trans('runsite::models.New model') }}</a>
                      </li>
                  @endif
              @endif
              
            </ul>
            <div class="tab-content no-padding">
              <div class="tab-pane active">

                @if(Route::current()->getName() == 'admin.models.fields.edit' or Route::current()->getName() == 'admin.models.fields.settings.edit'or Route::current()->getName() == 'admin.models.fields.access.edit')
                  <div class="xs-p-15 xs-pb-0">
                    <div class="btn-group">
                        <a href="{{ route('admin.models.fields.edit', ['model_id'=>$model->id, 'id'=>$field->id]) }}" class="btn btn-{{ Route::current()->getName() == 'admin.models.fields.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.models.fields.edit') data-ripple-color="#898989" @endif>
                          <i class="fa fa-pencil-square-o"></i> {{ trans('runsite::models.fields.Edit') }}
                        </a>
                        <a href="{{ route('admin.models.fields.settings.edit', ['model_id'=>$model->id, 'id'=>$field->id]) }}" class="btn btn-{{ Route::current()->getName() == 'admin.models.fields.settings.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.models.settings.edit') data-ripple-color="#898989" @endif>
                          <i class="fa fa-cog"></i> {{ trans('runsite::models.fields.Settings') }}
                        </a>
                        <a href="{{ route('admin.models.fields.access.edit', ['model_id'=>$model->id, 'id'=>$field->id]) }}" class="btn btn-{{ Route::current()->getName() == 'admin.models.fields.access.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.models.access.edit') data-ripple-color="#898989" @endif>
                          <i class="fa fa-lock"></i> {{ trans('runsite::models.fields.Access') }}
                        </a>
                      </div>
                  </div>
                @endif

                @yield('model')
              </div>
            </div>
        </div>
    </div>

@endsection