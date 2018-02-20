@extends('runsite::layouts.app')
@section('app')

    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs nav-tabs-autochange">
              <li class="{{ str_is('admin.users.*', Route::current()->getName()) ? 'active' : null }}">
                  <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.users.index') }}"><i class="fa fa-user"></i> {{ trans('runsite::users.Users') }}</a>
              </li>
              <li class="{{ str_is('admin.groups.*', Route::current()->getName()) ? 'active' : null }}">
                  <a class="ripple" data-ripple-color="#ccc" href="{{ route('admin.groups.index') }}"><i class="fa fa-object-group"></i> {{ trans('runsite::users.Groups') }}</a>
              </li>

              
            </ul>
            <div class="tab-content no-padding">
              <div class="tab-pane active">

                @if(Route::current()->getName() == 'admin.groups.edit' or Route::current()->getName() == 'admin.groups.access.edit')
                  <div class="xs-p-15 xs-pb-0">
                    <div class="btn-group">
                      <a href="{{ route('admin.groups.edit', $group->id) }}" class="btn btn-{{ Route::current()->getName() == 'admin.groups.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.groups.edit') data-ripple-color="#898989" @endif>
                        <i class="fa fa-pencil-square-o"></i> {{ trans('runsite::groups.Edit') }}
                      </a>
                      <a href="{{ route('admin.groups.access.edit', $group->id) }}" class="btn btn-{{ Route::current()->getName() == 'admin.groups.access.edit' ? 'primary' : 'default' }} btn-sm ripple" @if(Route::current()->getName() != 'admin.groups.access.edit') data-ripple-color="#898989" @endif>
                        <i class="fa fa-pencil-square-o"></i> {{ trans('runsite::groups.Access') }}
                      </a>
                    </div>
                  </div>
                @endif

                @yield('user')
              </div>
            </div>
        </div>
    </div>

@endsection
