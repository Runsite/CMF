<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | {{ config('app.name') }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {!! Minify::stylesheet([
        //'/vendor/runsite/asset/bower_components/font-awesome/css/font-awesome.min.css',
        '/vendor/runsite/asset/bower_components/select2/dist/css/select2.min.css',
        '/vendor/runsite/asset/bower_components/bootstrap/dist/css/bootstrap.min.css',
        '/vendor/runsite/asset/dist/css/AdminLTE.css',
        '/vendor/runsite/asset/dist/css/skins/skin-black.css',
        '/vendor/runsite/asset/plugins/iCheck/square/blue.css',
        '/vendor/runsite/asset/plugins/cropper/cropper.min.css',
        '/vendor/runsite/asset/plugins/sass-space/sass-space.css',
        '/vendor/runsite/asset/plugins/bootstrap-toggle/bootstrap-toggle.min.css',
        '/vendor/runsite/asset/plugins/responsive-align/responsive-align.css',
        '/vendor/runsite/asset/plugins/ripple/ripple.css',
        '/vendor/runsite/asset/plugins/bootstrap-languages/languages.min.css',
        '/vendor/runsite/asset/plugins/errors/forbidden.css',
        '/vendor/runsite/asset/plugins/runsite-checkbox/runsite-checkbox.css',
        '/vendor/runsite/asset/plugins/noty/noty.css',
        '/vendor/runsite/asset/plugins/animate/animate.css',
        '/vendor/runsite/asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css',
        '/vendor/runsite/asset/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
        '/vendor/runsite/asset/plugins/highlight/styles/github.css',
        '/vendor/runsite/asset/plugins/pace/pace.css',
    ]) !!}

    {{-- <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/dist/css/AdminLTE.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/dist/css/skins/skin-black.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/cropper/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/sass-space/sass-space.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/bootstrap-toggle/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/responsive-align/responsive-align.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/ripple/ripple.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/bootstrap-languages/languages.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/errors/forbidden.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/runsite-checkbox/runsite-checkbox.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/noty/noty.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/animate/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/pace/pace.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/runsite/asset/plugins/highlight/styles/github.css') }}"> --}}

    <script src="{{ asset('vendor/runsite/asset/plugins/ckeditor/ckeditor.js') }}"></script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <style>
        
        .input-progress-wrapper {
            width: 100%;
            background: #eee;
            position: relative;
            height: 2px;
            opacity: 0;
        }

        .input-progress {
            position: absolute;
            left: 0;
            top: 0; bottom: 0;
            width: 0%;
            background: #347ffb;
            transition: width 100ms;
        }

        .input-image-wrapper {
            border: 2px dashed #347ffb;
            display: inline-block;
            position: relative;
            overflow: hidden;
            border-radius: 2px;
            background: rgba(0,0,0, 0.0);
            transition: background 200ms, border-color 100ms;
        }

        .input-image-wrapper.input-image-selected {
            border-color: #00a65a;
        }

        .input-image-wrapper:hover {
            background: rgba(0,0,0, 0.05);
            border-color: #1b6ffb;
        }

        .input-image-wrapper:active {
            background: rgba(0,0,0, 0.1);
            transition: background 50ms;
        }

        .input-image-wrapper input[type=file] {
            position: absolute;
            z-index: 1;
            left: -50px; width: calc(100% + 50px);
            top: -50px; height: calc(100% + 50px);
            overflow: hidden;
            cursor: pointer;
            opacity: 0;
        }

        .input-image-wrapper span {
            display: block;
            padding: 15px 50px;
        }

        .typeahead { z-index: 1051; }

        .tree-image-circle {
            width: 20px; height: 20px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255, 0.9);
            border-radius: 50%;
            display: inline-block;
            vertical-align: middle;
            background-size: cover;
            margin-right: 3px;
            margin-left: -4px;
            position: relative;
            top: -2px;
        }

    </style>
  </head>
  <body class="hold-transition skin-black sidebar-mini fixed @yield('body-class')">
    @yield('content')
    
    <script src="{{ asset('vendor/runsite/asset/bower_components/moment/min/moment-with-locales.min.js') }}"></script>

    {!! Minify::javascript([
        '/vendor/runsite/asset/bower_components/jquery/dist/jquery.min.js',
        '/vendor/runsite/asset/bower_components/bootstrap/dist/js/bootstrap.min.js',
        '/vendor/runsite/asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
        '/vendor/runsite/asset/dist/js/adminlte.js',
        '/vendor/runsite/asset/plugins/cropper/cropper.min.js',
        '/vendor/runsite/asset/plugins/bootstrap-toggle/bootstrap-toggle.min.js',
        '/vendor/runsite/asset/plugins/ripple/ripple.js',
        '/vendor/runsite/asset/plugins/noty/noty.min.js',
        '/vendor/runsite/asset/plugins/jquery-cookie/jquery.cookie.js',
        '/vendor/runsite/asset/plugins/pace/pace.min.js',
        '/vendor/runsite/asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        '/vendor/runsite/asset/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        '/vendor/runsite/asset/bower_components/select2/dist/js/select2.full.min.js',
        '/vendor/runsite/asset/bower_components/select2/dist/js/i18n/'.LaravelLocalization::setLocale().'.js',
        '/vendor/runsite/asset/plugins/highlight/highlight.pack.js',
        '/vendor/runsite/asset/plugins/iCheck/icheck.min.js',
        '/vendor/runsite/asset/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js',
        '/vendor/runsite/asset/plugins/PreventDoubleSubmission/preventDoubleSubmission.js',
    ]) !!}

    <script>
    $(document).ready(function () {

        $('form:not(.js-allow-double-submission)').preventDoubleSubmission();

        $(".typeahead").each(function() {
            var object = $(this);
            object.typeahead({
              source: object.data('source'),
              autoSelect: true
            });
        });

        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });

        $('[data-toggle=tooltip]').tooltip();

        $('input.has-progress').on('keyup focus', function() {
            var currentLength = $(this).val().length;
            var maxLength = $(this).attr('maxlength');
            var percent = currentLength * 100 / maxLength;
            $(this).parent().find('.input-progress').css('width', percent+'%');
            $(this).parent().find('.input-progress-wrapper').css('opacity', 1);
        });

        $('input.has-progress').on('blur', function() {
            $(this).parent().find('.input-progress-wrapper').css('opacity', 0);
        });


      $('.sidebar-menu').tree();
      $('.icheck input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
      });

      $('.nav.nav-tabs.nav-tabs-autochange li a').on('click', function() {
        $(this).parent().parent().find('li').removeClass('active');
        $(this).parent().addClass('active');
      });

      $('.input-datetime').each(function(){
        $(this).datetimepicker({
            locale: '{{ LaravelLocalization::setLocale() }}',
            format: 'YYYY-MM-DD HH:mm:ss'
        });
      });

      $('.input-date').each(function(){
        $(this).datepicker({
            language: '{{ LaravelLocalization::setLocale() }}',
            format: 'yyyy-mm-dd'
        });
      });

      $('.remember-scroll-position').on('click', function() {
        $.cookie('remember-scroll-position', $(window).scrollTop());
      });

      if($.cookie('remember-scroll-position'))
      {
        $(window).scrollTop($.cookie('remember-scroll-position'));
        $.removeCookie('remember-scroll-position');
      }

      $('.inner-link-search').each(function(){
        var select = $(this);

        select.select2({
            locale: '{{ LaravelLocalization::setLocale() }}',
            ajax: {
                delay: 500,
                url: "{{route('admin.api.node.inner-link')}}",
                dataType: 'json',
                cache: false,
                data: function (term, page) {
                  return {
                    q: term
                  }
                }
            }
        });
      });


      $('.relation-to-one-search').each(function(){
        var select = $(this);
        var related_model_name = select.data('related-model-name');
        var related_parent_node_id = select.data('related-parent-node-id');

        select.select2({
            locale: '{{ LaravelLocalization::setLocale() }}',
            ajax: {
                delay: 500,
                url: "{{route('admin.api.node.find-by-name')}}?related_model_name="+related_model_name+"&related_parent_node_id="+related_parent_node_id,
                dataType: 'json',
                cache: false,
                data: function (term, page) {
                  return {
                    q: term
                  }
                }
            }
        });
      });

      $('.relation-to-many-search').each(function(){
        var select = $(this);
        var related_model_name = select.data('related-model-name');
        var related_parent_node_id = select.data('related-parent-node-id');

        select.select2({
            multiple: true,
            locale: '{{ LaravelLocalization::setLocale() }}',
            ajax: {
                delay: 500,
                url: "{{route('admin.api.node.find-by-name')}}?multiple=true&related_model_name="+related_model_name+"&related_parent_node_id="+related_parent_node_id,
                dataType: 'json',
                cache: false,
                data: function (term, page) {
                  return {
                    q: term
                  }
                }
            }
        });
      });


    })
    </script>

    @if (\Session::has('success'))
        <script>
            new Noty({
                text: '{!! \Session::get('success') !!}',
                type: 'success',
                timeout: 1500,
            }).show();
        </script>
    @endif

    @if (\Session::has('error'))
        <script>
            new Noty({
                text: '{!! \Session::get('error') !!}',
                type: 'error',
                timeout: 1500,
            }).show();
        </script>
    @endif

    @if($errors->count())
        <script>
            new Noty({
                text: 'Some errors detected',
                type: 'error',
                timeout: 1500,
            }).show();
        </script>
    @endif

    @yield('js-notifications')
    @yield('js')
  </body>
</html>
