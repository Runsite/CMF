@extends('runsite::layouts.app')
@section('app')
<div id="elfinder"></div>
@endsection

@section('js')
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/runsite/asset/plugins/elfinder/css/elfinder.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/runsite/asset/plugins/elfinder/css/elFinder-Material-Theme-master/Material/css/theme-gray.css') }}">
<script src="{{ asset('vendor/runsite/asset/plugins/elfinder/js/elfinder.min.js') }}"></script>

@if($locale)
    <!-- elFinder translation (OPTIONAL) -->
    <script src="{{ asset("vendor/runsite/asset/plugins/elfinder/js/i18n/elfinder.$locale.js") }}"></script>
@endif

<script type="text/javascript" charset="utf-8">
    $().ready(function() {
        $('#elfinder').elfinder({
            height: $('.content-wrapper').height() - $('.main-footer').height() - $('.main-header').height() + 15,
            resize: false,
            
            @if($locale)
                lang: '{{ $locale }}',
            @endif
            customData: { 
                _token: '{{ csrf_token() }}'
            },
            url : '{{ route("elfinder.connector") }}',
            soundPath: '{{ asset('vendor/runsite/asset/plugins/elfinder/sounds') }}'
        });
    });
</script>
@endsection
