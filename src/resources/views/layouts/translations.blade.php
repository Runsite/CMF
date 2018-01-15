@extends('runsite::layouts.app')
@section('app')

    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="{{ str_is('admin.translations.*', Route::current()->getName()) ? 'active' : null }}">
                  <a href="{{ route('admin.translations.index') }}"><i class="fa fa-language"></i> {{ trans('runsite::translations.Translations') }}</a>
              </li>
              <li class="{{ str_is('admin.languages.*', Route::current()->getName()) ? 'active' : null }}">
                  <a href="{{ route('admin.languages.index') }}"><i class="fa fa-globe"></i> {{ trans('runsite::translations.Languages') }}</a>
              </li>

              
            </ul>
            <div class="tab-content no-padding">
              <div class="tab-pane active">
                @yield('translation')
              </div>
            </div>
        </div>
    </div>

@endsection
