@extends('runsite::layouts.models')

@section('model')
	<div class="xs-p-15 xs-pb-15">
		{!! Form::model($settings, ['url'=>route('admin.models.settings.update', $settings->model->id), 'method'=>'patch']) !!}

			<div class="row">
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('nodes_ordering') ? ' has-error' : '' }}">
						{{ Form::label('nodes_ordering', trans('runsite::models.settings.Nodes ordering')) }}
						{{ Form::text('nodes_ordering', null, ['class'=>'form-control input-sm', 'placeholder'=>'position asc', 'autofocus'=>'true', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
						@if ($errors->has('nodes_ordering'))
							<span class="help-block">
								<strong>{{ $errors->first('nodes_ordering') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.Ordering in admin panel and in then scope ordered()') }}</small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('dynamic_model') ? ' has-error' : '' }}">
						{{ Form::label('dynamic_model', trans('runsite::models.settings.Dynamic model')) }}
						{{ Form::text('dynamic_model', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
						@if ($errors->has('dynamic_model'))
							<span class="help-block">
								<strong>{{ $errors->first('dynamic_model') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.Name of class stored in App\Models') }}</small>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('max_nodes_count') ? ' has-error' : '' }}">
						{{ Form::label('max_nodes_count', trans('runsite::models.settings.Max nodes count')) }}
						{{ Form::text('max_nodes_count', null, ['class'=>'form-control input-sm', ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
						@if ($errors->has('max_nodes_count'))
							<span class="help-block">
								<strong>{{ $errors->first('max_nodes_count') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.Here you can determine how many nodes of this model can be created in the admin panel') }}</small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('node_icon') ? ' has-error' : '' }}">
						{{ Form::label('node_icon', trans('runsite::models.settings.Node icon')) }}
						{{ Form::text('node_icon', null, ['class'=>'form-control input-sm typeahead', 'data-source'=>json_encode(FontAwesome::icons()), (! Auth::user()->access()->application($application)->edit or $model->id == 1) ? 'disabled' : null]) }}
						@if ($errors->has('node_icon'))
							<span class="help-block">
								<strong>{{ $errors->first('node_icon') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.The icon will be displayed in the tree') }}.</small>
					</div>
				</div>
			</div>
			

			

			

			<div class="row">
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('show_in_admin_tree') ? ' has-error' : '' }}">
						{{ Form::label('show_in_admin_tree', trans('runsite::models.settings.Show in admin tree')) }}
						<input type="hidden" name="show_in_admin_tree" value="0">
						<div class="runsite-checkbox">
							{{ Form::checkbox('show_in_admin_tree', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
							<label for="show_in_admin_tree"></label>
						</div>
						@if ($errors->has('show_in_admin_tree'))
							<span class="help-block">
								<strong>{{ $errors->first('show_in_admin_tree') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.Nodes of this model will be displayed in the tree') }}. {{ trans('runsite::models.settings.There are restrictions on the level of nesting: only two levels are displayed') }}</small>
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
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.Responses of this model will be cached and returned without processing by the controllers') }}. {{ trans('runsite::models.settings.Only GET requests are processed') }}. <br><br>{{ trans('runsite::models.settings.Please note: if your page contains forms with CSRF protection - do not use caching') }}.</small>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('slug_autogeneration') ? ' has-error' : '' }}">
						{{ Form::label('slug_autogeneration', trans('runsite::models.settings.Generate new slug automaticly')) }}
						<input type="hidden" name="slug_autogeneration" value="0">
						<div class="runsite-checkbox">
							{{ Form::checkbox('slug_autogeneration', 1, null, [ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null]) }}
							<label for="slug_autogeneration"></label>
						</div>
						@if ($errors->has('slug_autogeneration'))
							<span class="help-block">
								<strong>{{ $errors->first('slug_autogeneration') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.After node updating, new slug will be generated automaticly, based on the name field') }}. {{ trans('runsite::models.settings.Do not use for static partitions such as "news list", "contacts," etc') }}.</small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('is_searchable') ? ' has-error' : '' }}">
						{{ Form::label('is_searchable', trans('runsite::models.settings.Is searchable')) }}
						<input type="hidden" name="is_searchable" value="0">
						<div class="runsite-checkbox">
							{{ Form::checkbox('is_searchable', 1, null, [ (! Auth::user()->access()->application($application)->edit or ! $settings->model->hasField('name')) ? 'disabled' : null]) }}
							<label for="is_searchable"></label>
						</div>
						@if ($errors->has('is_searchable'))
							<span class="help-block">
								<strong>{{ $errors->first('is_searchable') }}</strong>
							</span>
						@endif

						@if(! $settings->model->hasField('name'))
							<small class="text-red"><i class="fa fa-warning"></i> {{ trans('runsite::models.settings.The search requires a Name field') }}</small><br>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.The model will be available for search in the admin panel') }}.</small>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('require_seo') ? ' has-error' : '' }}">
						{{ Form::label('require_seo', trans('runsite::models.settings.Require to fill fields for SEO')) }}
						<input type="hidden" name="require_seo" value="0">
						<div class="runsite-checkbox">
							{{ Form::checkbox('require_seo', 1, null, [ (! Auth::user()->access()->application($application)->edit or ! $settings->model->hasField('title') or ! $settings->model->hasField('description')) ? 'disabled' : null]) }}
							<label for="require_seo"></label>
						</div>
						@if ($errors->has('require_seo'))
							<span class="help-block">
								<strong>{{ $errors->first('require_seo') }}</strong>
							</span>
						@endif
						@if(! $settings->model->hasField('title') or ! $settings->model->hasField('description'))
							<small class="text-red"><i class="fa fa-warning"></i> {{ trans('runsite::models.settings.This option requires the following fields') }}: title, description</small><br>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.In the nodes, in which no title and descripton fields are filled, a warning will be displayed') }}.</small>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group {{ $errors->has('redirect_to_node_after_creation') ? ' has-error' : '' }}">
						{{ Form::label('redirect_to_node_after_creation', trans('runsite::models.settings.Redirect to node after creation')) }}
						<input type="hidden" name="redirect_to_node_after_creation" value="0">
						<div class="runsite-checkbox">
							{{ Form::checkbox('redirect_to_node_after_creation', 1, null, [ (! Auth::user()->access()->application($application)->edit) ? 'disabled' : null]) }}
							<label for="redirect_to_node_after_creation"></label>
						</div>
						@if ($errors->has('redirect_to_node_after_creation'))
							<span class="help-block">
								<strong>{{ $errors->first('redirect_to_node_after_creation') }}</strong>
							</span>
						@endif
						<small class="text-muted"><i class="fa fa-info"></i> {{ trans('runsite::models.settings.After the section is created, the user will be redirected to the edit instead of the list') }}.</small>
					</div>
				</div>
			</div>

			@if(Auth::user()->access()->application($application)->edit)
				<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.Update') }}</button>
			@endif
		{!! Form::close() !!}
	</div>
@endsection
