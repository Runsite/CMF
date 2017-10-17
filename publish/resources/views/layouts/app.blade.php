<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="UTF-8">
		<title>{{ config('app.name') }}</title>

		{{-- Viewport mobile tag for sensible mobile support --}}
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		{{-- VENDOR STYLES --}}
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		{{-- VENDOR STYLES END --}}

		{{-- APP STYLES --}}

		{{-- APP STYLES END --}}
	</head>
	<body>
		@yield('app')

		{{-- VENDOR SCRIPTS --}}
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		{{-- VENDOR SCRIPTS END --}}

		{{-- APP SCRIPTS --}}

		{{-- APP SCRIPTS END --}}
	</body>
</html>