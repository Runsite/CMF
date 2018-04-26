@extends('layouts.app')

@section('content')
	<div class="jumbotron">
		<div class="container">
			<h1>{{ $fields->name }}</h1>
			<p>Content Management Framework</p>
			<p><a class="btn btn-primary btn-lg" href="{{ url('admin') }}" role="button">Go to admin panel</a></p>
		</div>
	</div>
@endsection
