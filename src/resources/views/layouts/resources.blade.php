<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | {{ config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/dist/css/AdminLTE.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/dist/css/skins/skin-black.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/cropper/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/sass-space/sass-space.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/bootstrap-toggle/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/responsive-align/responsive-align.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/ripple/ripple.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/bootstrap-languages/languages.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/errors/forbidden.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/plugins/runsite-checkbox/runsite-checkbox.css') }}">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  </head>
  <body class="hold-transition skin-black sidebar-mini fixed @yield('body-class')">
    @yield('content')
    <script src="{{ asset('vendor/runsite/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('vendor/runsite/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/dist/js/demo.js') }}"></script>
    <script src="{{ asset('vendor/runsite/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/plugins/cropper/cropper.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/plugins/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('vendor/runsite/plugins/ripple/ripple.js') }}"></script>
    <script>
    $(document).ready(function () {
      $('.sidebar-menu').tree();
      $('.icheck input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });

      $('.nav.nav-tabs.nav-tabs-autochange li a').on('click', function() {
        $(this).parent().parent().find('li').removeClass('active');
        $(this).parent().addClass('active');
      });
    })
    </script>

    @yield('js')
  </body>
</html>