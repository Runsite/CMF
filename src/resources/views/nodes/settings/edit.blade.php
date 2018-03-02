@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::model($settings, ['url'=>route('admin.nodes.settings.update', ['node'=>$node]), 'method'=>'patch']) !!}
@endsection

@section('node')

	<div class="row">
		<div class="col-md-6">
			<div class="form-group {{ $errors->has('node_icon') ? ' has-error' : '' }}">
				{{ Form::label('node_icon', trans('runsite::models.settings.Node icon')) }}
				{{ Form::text('node_icon', null, ['class'=>'form-control input-sm typeahead', 'data-source'=>json_encode(FontAwesome::icons()), ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
				@if ($errors->has('node_icon'))
					<span class="help-block">
						<strong>{{ $errors->first('node_icon') }}</strong>
					</span>
				@endif
				<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.The icon will be displayed in the tree') }}.</small>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group {{ $errors->has('use_response_cache') ? ' has-error' : '' }}">
				{{ Form::label('use_response_cache', trans('runsite::models.settings.Use response cache')) }}
				<input type="hidden" name="use_response_cache" value="0">
				<div class="runsite-checkbox">
					{{ Form::checkbox('use_response_cache', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
					<label for="use_response_cache"></label>
				</div>
				@if ($errors->has('use_response_cache'))
					<span class="help-block">
						<strong>{{ $errors->first('use_response_cache') }}</strong>
					</span>
				@endif
				<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.Responses of this model will be cached and returned without processing by the controllers') }}. {{ trans('runsite::models.settings.Only GET requests are processed') }}. {{ trans('runsite::models.settings.Please note: if your page contains forms with CSRF protection - do not use caching') }}.</small>
			</div>
		</div>
	</div>

	

	


	@if(Auth::user()->access()->application($application)->edit)
		<div class="form-group xs-mb-0">
			<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Update') }}</button>
		</div>
	@endif
@endsection
