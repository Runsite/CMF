@extends('runsite::layouts.resources')
@section('content')
<div class="wrapper">
  <header class="main-header">
    <a href="{{ route('admin.boot') }}" class="logo">
      <span class="logo-mini"><b>R</b>S</span>
      <span class="logo-lg"><b>runsite</b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      @yield('breadcrumbs')

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          {{-- <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li> --}}


          <li class="dropdown">
            <a href="#" class="dropdown-toggle ripple" data-toggle="dropdown">
              <i class="fa fa-cogs" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('admin.models.index') }}">{{ trans('runsite::models.Models') }}</a></li>
              <li><a href="{{ route('admin.users.index') }}">{{ trans('runsite::users.Users') }} / {{ trans('runsite::users.Groups') }}</a></li>
            </ul>
          </li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle text-uppercase ripple" data-toggle="dropdown">
              {{-- {{ LaravelLocalization::getCurrentLocale() }}  --}}
              <i class="fa fa-language"></i>
            </a>
            <ul class="dropdown-menu">
              @foreach(LaravelLocalization::getSupportedLocales() as $locale=>$info)
                <li><a href="{{ LaravelLocalization::getLocalizedURL($locale) }}">{{ $info['name'] }}</a></li>
              @endforeach
            </ul>
          </li>

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ $authUser->imagePath() }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ $authUser->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="{{ $authUser->imagePath() }}" class="img-circle" alt="User Image">
                <p>
                  {{ $authUser->name }}
                  <small>
                    @foreach($authUser->groups as $k=>$group)
                    {{ $group->name }}{{ ++$k < count($authUser->groups) ? ', ' : null }}
                    @endforeach
                  </small>
                  <small>{{ trans('runsite::account.Member since') }} {{ $authUser->created_at->format('d.m.Y') }}</small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('admin.account.settings.edit') }}" class="btn btn-default btn-flat">{{ trans('runsite::account.Settings') }}</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ trans('runsite::account.Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<aside class="main-sidebar">
  <section class="sidebar">
    {{-- <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ $authUser->imagePath() }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ $authUser->name }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="button" name="search" id="search-btn" class="btn btn-flat">
          <i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form> --}}
    <ul class="sidebar-menu" data-widget="tree">
      {{-- <li class="header">NAVIGATION</li> --}}
      <li class="{{ (request()->route('node') and request()->route('node')->id == 1) ? 'active' : null }}"><a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>$rootNode->id]) }}"><i class="fa fa-home"></i> <span>{{ $rootNode->dynamicCurrentLanguage()->first()->name }}</span></a></li>

      @foreach($childNodes as $childNode)
        <li class="{{ (request()->route('node') and request()->route('node')->id == $childNode->id) ? 'active' : null }}">
          <a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>$childNode->id]) }}">
            <i class="fa fa-folder"></i> 
            <span>{{ $childNode->dynamicCurrentLanguage()->first()->name }}</span>
          </a>
        </li>
      @endforeach
      {{-- <li><a href="#"><i class="fa fa-folder"></i> <span>Новини</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Публікації</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Автори</span></a></li>
      <li class="active"><a href="#"><i class="fa fa-folder-open"></i> <span>Коментарі</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Фотогалерея</span></a></li>
      <li><a href="#"><i class="fa fa-file-text"></i> <span>Контакти</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Новини</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Публікації</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Автори</span></a></li>
      <li><a href="#"><i class="fa fa-folder-open"></i> <span>Коментарі</span></a></li>
      <li><a href="#"><i class="fa fa-folder"></i> <span>Фотогалерея</span></a></li>
      <li><a href="#"><i class="fa fa-file-text"></i> <span>Контакти</span></a></li> --}}
      
    </ul>
  </section>
</aside>
<div class="content-wrapper">

  

  @yield('app')
</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.4.0
  </div>
  <strong>Copyright &copy; <a href="https://adminlte.io">RunsiteCMF</a>.</strong> All rights
  reserved.
</footer>
</div>
@endsection