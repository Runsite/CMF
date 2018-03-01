@extends('runsite::layouts.nodes')

@section('node')
	<div class="xs-p-50 text-center">
		<img src="data:image/png;base64,{!! base64_encode(QrCode::format('png')->size('300')->margin(0)->generate(lPath($node->path->name, $language->locale))) !!}">
	</div>
@endsection
