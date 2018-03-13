@extends('runsite::layouts.resources')
@section('content')
<div class="wrapper">
  <header class="main-header">
    <a href="{{ route('admin.boot') }}" class="logo ripple hidden-xs">
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

          <li class="dropdown notifications-menu global-search-wrapper">
            <a href="#" class="dropdown-toggle ripple" data-toggle="dropdown" onclick="setTimeout(function(){$('.global-search-wrapper input[name=\'search_key\']').focus();}, 1)">
              <i class="fa fa-search"></i>
            </a>
            <ul class="dropdown-menu">
              <li class="header">
                {{ trans('runsite::search.Search') }}
              </li>
              <li class="header">
                <input type="text" class="form-control input-sm" name="search_key">
              </li>
              <li>
                <ul class="menu">

                  @foreach($searchHistory as $searchHistoryItem)
                    <li>
                      <a href="{{ route('admin.search.find-model', $searchHistoryItem->search_key) }}">{{ $searchHistoryItem->search_key }}</a>
                    </li>
                  @endforeach
                </ul>
              </li>
            </ul>
          </li>

          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle ripple" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span id="notifications-counter" class="label label-warning {{ !$unreadNotificationsCount ? 'hidden' : null }}">{{ $unreadNotificationsCount }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">
                {{ trans('runsite::app.Notifications') }}


                <div class="pull-right">
                  <button type="button" class="btn btn-xs btn-default {{ !Session::has('notificationSoundsMuted') ? 'active' : null }}" id="notifications-sound-control">
                    <i class="fa fa-volume-up" aria-hidden="true"></i>
                  </button>
                </div>

              </li>
              <li>
                <ul class="menu" id="notifications-container">

                  @foreach($notifications as $notification)
                    <li>
                      <a href="{{ route('admin.notifications.show', $notification) }}" style="white-space: normal;">
                        <i class="fa fa-{{ $notification->icon_name ?: 'flag' }} {{ !$notification->is_reviewed ? 'text-orange' : null }}"></i>
                        {{ $notification->message }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>
              <li class="footer">
                  <a href="{{ route('admin.notifications.index') }}">{{ trans('runsite::notifications.All notifications') }}</a>
              </li>
            </ul>
          </li>

          @yield('node_model')

          <li class="dropdown">
            <a href="#" class="dropdown-toggle text-uppercase ripple" data-toggle="dropdown">
              {{-- {{ LaravelLocalization::getCurrentLocale() }}  --}}
              <i class="fa fa-language"></i>
              @if($languagesHaveErrors)
                &nbsp;<i class="fa fa-warning text-red"></i>
              @endif
            </a>
            <ul class="dropdown-menu">
              {{-- @foreach(LaravelLocalization::getSupportedLocales() as $locale=>$info)
                <li><a href="{{ LaravelLocalization::getLocalizedURL($locale) }}">{{ $info['name'] }}</a></li>
              @endforeach --}}
              @foreach($allLanguages as $allLanguagesItem)
                <li>
                  <a href="{{ $allLanguagesItem->hasConfig() ?  LaravelLocalization::getLocalizedURL($allLanguagesItem->locale) : 'javascript:void(0);' }}">
                    {{ $allLanguagesItem->display_name }}
                    @if(!$allLanguagesItem->hasConfig())
                      <span class="label label-danger" title="{{ trans('runsite::languages.Not available') }}" data-toggle="tooltip">
                        <i class="fa fa-warning"></i>
                      </span>
                      
                    @endif
                  </a>
                </li>
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
      

      

      {{-- <li class="{{ (str_is('admin.users.*', Route::current()->getName()) or str_is('admin.groups.*', Route::current()->getName())) ? 'active' : null }}"><a class="ripple" href="{{ route('admin.users.index') }}"><i class="fa fa-circle-o text-success"></i> <span>{{ trans('runsite::users.Users') }} / {{ trans('runsite::users.Groups') }}</span></a></li>

      <li class="{{ (str_is('admin.translations.*', Route::current()->getName()) or str_is('admin.languages.*', Route::current()->getName())) ? 'active' : null }}"><a class="ripple" href="{{ route('admin.translations.index') }}"><i class="fa fa-circle-o text-primary"></i> <span>{{ trans('runsite::translations.Translations') }} / {{ trans('runsite::translations.Languages') }}</span></a></li>

      <li class="{{ str_is('admin.elfinder.*', Route::current()->getName()) ? 'active' : null }}"><a class="ripple" href="{{ route('admin.elfinder.index') }}"><i class="fa fa-circle-o text-warning"></i> <span>{{ trans('runsite::elfinder.Elfinder') }}</span></a></li> --}}

      <li class="header text-uppercase">{{ trans('runsite::app.Website') }}</li>
      <li class="{{ (request()->route('node') and request()->route('node')->id == 1) ? 'active' : null }}"><a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>$rootNode->id]) }}"><i class="fa fa-home"></i> <span>{{ $rootNode->dynamicCurrentLanguage()->first()->name }}</span></a></li>

      @foreach($childNodes as $childNode)

        @php($dynamic = $childNode->dynamicCurrentLanguage()->first())

        <li class="{{ (isset($node) and (str_is($childNode->currentLanguagePath->name, $node->currentLanguagePath->name) or str_is($childNode->currentLanguagePath->name.'/*', $node->currentLanguagePath->name))) ? 'active' : null }}">
          <a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>$childNode->id]) }}">
            <i class="fa fa-{{ $childNode->settings->node_icon ?: ($childNode->model->settings->node_icon ?: 'archive') }} {{ (!$childNode->hasMethod and !$childNode->model->hasMethod) ? 'text-warning' : null }}"></i> 
            <span>{{ $dynamic->name ?: trans('runsite::nodes.Node').' '.$childNode->id }}</span>

            @if( (! $dynamic->title or ! $dynamic->description) and $childNode->model->settings->require_seo)
              <i class="fa fa-warning text-orange xs-ml-5"></i>
            @endif

            <span data-node-id="{{ $childNode->id }}" class="label pull-right bg-yellow {{ !$childNode->totalUnreadNotificationsCount ? 'hidden' : null }}">{{ $childNode->totalUnreadNotificationsCount }}</span>
          </a>
          @if($childNode->hasTreeChildren())
            <ul class="treeview-menu">

              @foreach($childNode->getTreeChildren() as $treeChild)
              
                @php($dynamic = $treeChild->dynamicCurrentLanguage()->first())

                <li class="{{ (isset($node) and (str_is($treeChild->currentLanguagePath->name, $node->currentLanguagePath->name) or str_is($treeChild->currentLanguagePath->name.'/*', $node->currentLanguagePath->name))) ? 'active' : null }}">
                  <a class="ripple" href="{{ route('admin.nodes.edit', ['id'=>$treeChild->id]) }}">
                    <div class="xs-pl-10">

                      @if($dynamic->image and $dynamic->image->value and !$treeChild->settings->node_icon and !$treeChild->model->settings->node_icon)
                        <div class="tree-image-circle" style="background-image: url({{ $dynamic->image->min() }})"></div>
                      @else
                        <i class="fa fa-{{ $treeChild->settings->node_icon ?: ($treeChild->model->settings->node_icon ?: 'file-o') }} xs-mr-5"></i> 
                      @endif

                      

                    {{ $dynamic->name ?: trans('runsite::nodes.Node').' '.$treeChild->id }}

                    <span data-node-id="{{ $treeChild->id }}" class="label pull-right bg-green {{ !$treeChild->totalUnreadNotificationsCount ? 'hidden' : null }}">{{ $treeChild->totalUnreadNotificationsCount }}</span>
                    </div>
                  </a>
                </li>
              @endforeach
            </ul>
          @endif
          
        </li>
      @endforeach

      <li class="header text-uppercase">{{ trans('runsite::app.Tools') }}</li>

      @foreach($treeApplications as $application)
        @if($authUser->access()->application($application)->read)
          <li class="{{ str_is('admin.'.$application->name.'.*', Route::current()->getName()) ? 'active' : null }}"><a class="ripple" href="{{ route('admin.'.$application->name.'.index') }}"><i class="fa fa-circle-o text-{{ $application->color_name }}"></i> <span>{{ trans('runsite::'.$application->name.'.app_name') }}</span></a></li>
        @endif
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

@section('js-notifications')
<script>
  var notificationSoundsEnabled = {{ Session::has('notificationSoundsMuted') ? 'false' : 'true' }};

    $(function() {

        $('#notifications-sound-control').on('click', function() {
          if(!$(this).hasClass('active'))
          {
            $(this).addClass('active');

            notificationSoundsEnabled = true;

            $.get('{{ route('admin.api.enable-notifications-sound') }}');
          }
          else
          {
            $(this).removeClass('active');

            notificationSoundsEnabled = false;

             $.get('{{ route('admin.api.disable-notifications-sound') }}');
          }
        });

        $('.global-search-wrapper input[name=\'search_key\']').on('keypress', function(e) {
          if(e.which == 13)
          {
            var search_key = $('.global-search-wrapper input[name=search_key]').val();
            window.location.href = '{{ url('admin/search') }}/' + search_key;
          }
        });

        setInterval(function(){

            $.get('{{ route('admin.api.sound-notification-count') }}', function(data) {

                if(data.totalCount)
                {
                  $('#notifications-counter').html(data.totalCount).removeClass('hidden');
                }
                else
                {
                  $('#notifications-counter').html(data.totalCount).addClass('hidden');
                }
                
                Object.keys(data.nodes).forEach(function(node_id) {
                  var obj = $('[data-node-id="'+node_id+'"]');
                  obj.html(data.nodes[node_id]).removeClass('hidden');

                  if(data.playSound)
                  {
                    obj.addClass('animated').addClass('shake');
                  }
                });

                $('#notifications-container').html(data.notificationsHtml);

                if(data.playSound && notificationSoundsEnabled)
                {
                  $('#notification-player')[0].play();
                  $('#notifications-counter').addClass('animated').addClass('shake');
                }

                setTimeout(function() {
                  $('#notifications-counter, .treeview-menu a span').removeClass('animated').removeClass('shake');
                }, 2000);
            });

        }, 5000);
    });
</script>

<audio src="{{ asset('vendor/runsite/asset/sounds/notification.ogg') }}" id="notification-player"></audio>
@endsection
