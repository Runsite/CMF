@extends('runsite::layouts.app')

@section('breadcrumbs')
<ul class="nav navbar-nav navbar-breadcrumbs visible-md visible-lg">
  <li class="{{ Route::current()->getName() == 'admin.models.index' ? 'active' : null }}"><a class="ripple" href="{{ route('admin.models.index') }}"><i class="fa fa-cogs"></i></a></li>

  @if(isset($model))
    <li class="{{ Route::current()->getName() == 'admin.models.edit' ? 'active' : null }}">
      <a class="ripple" href="{{ route('admin.models.edit', $model->id) }}">
        <small><i class="fa fa-cog text-red"></i> {{ $model->display_name }}</small>
      </a>
    </li>
  @endif

  @if(isset($field) and isset($field->display_name))
    <li class="{{ str_is('admin.models.fields.*', Route::current()->getName()) ? 'active' : null }}">
      <a class="ripple" href="{{ route('admin.models.fields.edit', ['model_id'=>$model->id, 'field_id'=>$field->id]) }}">
        <small><i class="fa fa-th-list text-blue"></i> {{ $field->display_name }}</small>
      </a>
    </li>
  @endif
</ul>
@endsection

@section('app')

    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs nav-tabs-autochange">
              <li class="{{ Route::current()->getName() == 'admin.models.index' ? 'active' : null }}">
                  <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.index') }}">
                    <i class="fa fa-cogs"></i>&nbsp;
                    <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Models'), 3, '.') }}</span>
                    <span class="visible-lg-inline">{{ trans('runsite::models.Models') }}</span>
                  </a>
              </li>

              @if(Route::current()->getName() != 'admin.models.index' and Route::current()->getName() != 'admin.models.create' and isset($model))

                  <li class="{{ Route::current()->getName() == 'admin.models.edit' ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.edit', $model->id) }}">
                        <i class="fa fa-pencil-square-o"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Edit'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Edit') }}</span>
                      </a>
                  </li>
                  <li class="{{ Route::current()->getName() == 'admin.models.settings.edit' ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.settings.edit', $model->id) }}">
                        <i class="fa fa-cog"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Settings'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Settings') }}</span>
                      </a>
                  </li>
                  <li class="{{ (Route::current()->getName() == 'admin.models.methods.edit' or Route::current()->getName() == 'admin.models.methods.controller') ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.methods.edit', $model->id) }}">
                        <i class="fa fa-window-restore"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Methods'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Methods') }}</span>
                        @if($model->methodsCount())&nbsp;
                          <span class="label label-default visible-lg-inline">{{ $model->methodsCount() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.fields.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.fields.index', $model->id) }}">
                        <i class="fa fa-th-list"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Fields'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Fields') }}</span>
                        @if($model->fields->count())&nbsp;
                          <span class="label label-default visible-lg-inline">{{ $model->fields->count() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.groups.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.groups.index', $model->id) }}">
                        <i class="fa fa-object-group"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Groups'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Groups') }}</span>
                        @if($model->groups->count())&nbsp;
                          <span class="label label-default visible-lg-inline">{{ $model->groups->count() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.dependencies.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.dependencies.index', $model->id) }}">
                        <i class="fa fa-sitemap"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Dependencies'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Dependencies') }}</span>
                        @if($model->dependencies->count())&nbsp;
                          <span class="label label-default visible-lg-inline">{{ $model->dependencies->count() }}</span>
                        @endif
                      </a>
                  </li>
                  <li class="{{ str_is('admin.models.access.*', Route::current()->getName()) ? 'active' : null }}">
                      <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.access.edit', $model->id) }}">
                        <i class="fa fa-lock"></i>&nbsp;
                        <span class="visible-md-inline">{{ str_limit(trans('runsite::models.Access'), 3, '.') }}</span>
                        <span class="visible-lg-inline">{{ trans('runsite::models.Access') }}</span>
                      </a>
                  </li>
                  
                  
              @else 
                  @if(Auth::user()->access()->application($application)->edit)
                      <li class="{{ Route::current()->getName() == 'admin.models.create' ? 'active' : null }}">
                          <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.models.create') }}">
                            <i class="fa fa-plus"></i>&nbsp;
                            {{ trans('runsite::models.New model') }}
                          </a>
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
                          @if(($field->types[$field->type_id]::$displayName == 'relation_to_one' or $field->types[$field->type_id]::$displayName == 'relation_to_many') and !$field->findSettings('related_model_name')->value)
                            &nbsp;<span style="display: inline-block;" class="label label-danger animated tada">
                                <i class="fa fa-warning" style="font-size: 11px;"></i> 
                              </span>
                          @endif
                        </a>
                        <a href="{{ route('admin.models.fields.access.edit', ['model_id'=>$model->id, 'id'=>$field->id]) }}" class="btn btn-{{ Route::current()->getName() == 'admin.models.fields.access.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.models.access.edit') data-ripple-color="#898989" @endif>
                          <i class="fa fa-lock"></i> {{ trans('runsite::models.fields.Access') }}
                        </a>
                      </div>

                      <div class="pull-right hidden-xs">
                        <div class="btn-group">
                          <a 
                            href="{{ $field->prevField() ? route(Route::current()->getName(), ['model_id'=>$model->id, 'field_id'=>$field->prevField()->id]) : 'javascript:void(0)' }}" 
                            class="btn btn-default btn-sm {{ $field->prevField() ? 'ripple' : 'disabled' }}" data-ripple-color="#898989"
                            @if(!$field->prevField()) disabled @endif
                            >
                              <i class="fa fa-caret-left"></i>
                              @if($field->prevField())
                                &nbsp;<span class="visible-lg-inline">
                                  {{ $field->prevField()->name }}
                                </span>
                              @endif
                          </a>

                          <a href="javascript:void(0);" class="btn btn-default btn-sm disabled">{{ $field->name }}</a>
                          
                          <a 
                            href="{{ $field->nextField() ? route(Route::current()->getName(), ['model_id'=>$model->id, 'field_id'=>$field->nextField()->id]) : 'javascript:void(0)' }}" 
                            class="btn btn-default btn-sm {{ $field->nextField() ? 'ripple' : 'disabled' }}" data-ripple-color="#898989"
                            @if(!$field->nextField()) disabled @endif
                            >
                            @if($field->nextField())
                              <span class="visible-lg-inline">
                                {{ $field->nextField()->name }}
                              </span>&nbsp;
                            @endif
                            
                              <i class="fa fa-caret-right"></i>
                          </a>
                        </div>
                      </div>
                  </div>
                @endif

                @yield('model')
              </div>
            </div>
        </div>
    </div>

@endsection
