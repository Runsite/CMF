@extends('layouts.app')

@section('app')
	<div class="jumbotron">
		<div class="container">
			<h1>runsite.<b>CMF</b></h1>
			<p>Content Management Framework</p>
			<p><a class="btn btn-primary btn-lg" href="{{ url('admin') }}" role="button">Go to admin panel</a></p>
		</div>
	</div>
@endsection