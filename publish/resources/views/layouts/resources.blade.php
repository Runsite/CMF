<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" prefix="og: http://ogp.me/ns#">
    <head>
        <meta charset="UTF-8">

        @if(isset($seo))
            {{-- Base meta tags --}}
            <title>{{ $seo->title }}</title>
            <meta name="description" content="{{ $seo->description }}">
            <meta name="robots" content="index, follow">
            <meta name="author" content="{{ $seo->author }}">
            {{-- Base meta tags END --}}

            {{-- Open Graph --}}
            <meta name="og:title" content="{{ $seo->title }}">
            <meta name="og:type" content="website">

            @if($seo->image)
                <meta name="og:image" content="{{ $seo->image }}">
            @endif

            <meta name="og:description" content="{{ $seo->description }}">
            <meta name="og:site_name" content="{{ $seo->author }}">
            {{-- Open Graph END --}}
        @else
            <title>{{ config('app.name') }}</title>
        @endif
        

        {{-- Viewport mobile tag for sensible mobile support --}}
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        {{-- VENDOR STYLES --}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        {{-- VENDOR STYLES END --}}

        {{-- APP STYLES --}}
        {!! Minify::stylesheet([
            // Put app styles paths here
        ]) !!}
        {{-- APP STYLES END --}}
    </head>
    <body>
        @yield('app')

        {{-- VENDOR SCRIPTS --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        {{-- VENDOR SCRIPTS END --}}

        {{-- APP SCRIPTS --}}
        {!! Minify::javascript([
            // Put app scripts paths here
        ]) !!}
        {{-- APP SCRIPTS END --}}
    </body>
</html>
