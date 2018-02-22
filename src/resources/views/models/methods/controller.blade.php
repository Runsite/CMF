@extends('runsite::layouts.methods')

@section('methods')
	<div class="xs-p-15">
		<pre>
			<code lang="php">{{ $content }}</code>
		</pre>
	</div>
@endsection
