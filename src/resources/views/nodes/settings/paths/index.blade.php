@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::open(['url'=>route('admin.nodes.settings.paths.update', ['node'=>$node]), 'method'=>'patch']) !!}
@endsection

@section('node')
	@foreach($languages as $language)
		<div class="tab-pane {{ $active_language_tab == $language->locale ? 'active' : null }}" id="lang-{{ $language->id }}">
			@foreach($paths as $path)
				@if($path->language_id == $language->id)
					<div class="form-group">
						<div class="input-group">
						  <span class="input-group-addon bg-gray"><small class="text-mted">{{ $path->rootName }}</small></span>
						  <input 
							type="text" 
							class="form-control input-sm" 
							name="path[{{ $language->id }}][{{ $path->id }}]" 
							value="{{ $path->baseName }}">
						</div>
						
						
						<small class="text-muted"><i class="fa fa-clock-o"></i> {{ trans('runsite::nodes.Created at') }}: {{ $path->created_at->format('H:i, d.m.Y') }}</small>
					</div>
				@endif
			@endforeach
		</div>
	@endforeach

	<div class="form-group xs-mb-0">
		<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Update') }}</button>
	</div>
@endsection
