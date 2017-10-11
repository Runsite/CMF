@extends('runsite::layouts.app')
@section('app')

    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="{{ str_is('admin.users.*', Route::current()->getName()) ? 'active' : null }}">
                  <a href="{{ route('admin.users.index') }}"><i class="fa fa-user"></i> {{ trans('runsite::users.Users') }}</a>
              </li>
              <li class="{{ str_is('admin.groups.*', Route::current()->getName()) ? 'active' : null }}">
                  <a href="{{ route('admin.groups.index') }}"><i class="fa fa-object-group"></i> {{ trans('runsite::users.Groups') }}</a>
              </li>

              
            </ul>
            <div class="tab-content no-padding">
              <div class="tab-pane active">
                @yield('user')
              </div>
            </div>
        </div>
    </div>

@endsection