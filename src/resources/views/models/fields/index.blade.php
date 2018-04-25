@extends('runsite::layouts.models')

@section('model')

	@if(Auth::user()->access()->application($application)->edit)
		<div class="xs-p-15">
			

			@if(count($fieldTemplates))
				<div class="btn-group">
					<a href="{{ route('admin.models.fields.create', $model->id) }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::models.fields.New field') }}</a>

					<div class="btn-group">
						<button type="button" class="btn btn-default btn-sm dropdown-toggle ripple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							{{ trans('runsite::models.fields.Templates') }} <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							@foreach($fieldTemplates as $template_id=>$fieldTemplate)
								<li>
									{!! Form::open(['url'=>route('admin.models.fields.store_by_template', ['model'=>$model, 'template_id'=>$template_id]), 'method'=>'post', 'class'=>'hidden']) !!}
										
									{!! Form::close() !!}
									<a href="javascript:void(0)" onclick="$(this).parent().find('form').submit()">{{ $fieldTemplate->display_name }} - [<small>{{ $fieldTemplate->getTypeName() }}</small>]</a>
									
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			@else
				<a href="{{ route('admin.models.fields.create', $model->id) }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::models.fields.New field') }}</a>
			@endif
		</div>
	@endif

	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>{{ trans('runsite::models.fields.Display name') }}</th>
					<th>{{ trans('runsite::models.fields.Name') }}</th>
					<th>{{ trans('runsite::models.fields.Type') }}</th>
					<th>{{ trans('runsite::models.fields.Group') }}</th>
					
					@if(Auth::user()->access()->application($application)->edit)
						<th>{{ trans('runsite::models.fields.Position') }}</th>
					@endif

					@if(Auth::user()->access()->application($application)->delete)
						<th>{{ trans('runsite::models.fields.Delete') }}</th>
					@endif
				</tr>
			</thead>
			<tbody>
				@foreach($fields as $fieldItem)
					<tr class="{{ (Session::has('highlight') and Session::get('highlight') == $fieldItem->id) ? 'success' : null }}">
						<td>{{ $fieldItem->id }}</td>
						<td>
							<a class="ripple" data-ripple-color="#333" href="{{ route('admin.models.fields.edit', ['model_id'=>$model->id, 'id'=>$fieldItem->id]) }}" style="display: block;"><b>{{ $fieldItem->display_name }}</b></a>

							@if($fieldItem->name == 'name' and !$fieldItem->model->settings->is_searchable)
								<div class="xs-pl-10 xs-mt-5" style="border-left: 2px solid #ccc">
									{!! Form::open(['route'=>['admin.models.settings.make_model_searchable', $fieldItem->model], 'method'=>'patch']) !!}
										<small><button data-ripple-color="#333" type="submit" class="ripple text-orange btn-link" style="padding: 0;">Make this model searchable</button></small><br>
										<small class="text-muted">This model may be available for search in the general search engine of the admin panel.</small>
									{!! Form::close() !!}
								</div>
							@endif
						</td>
						<td><span class="label label-success">{{ $fieldItem->name }}</span></td>
						<td>
							<span class="label label-default">{{ $fieldItem->types[$fieldItem->type_id]::$displayName }}</span>
							@if(($fieldItem->types[$fieldItem->type_id]::$displayName == 'relation_to_one' or $fieldItem->types[$fieldItem->type_id]::$displayName == 'relation_to_many') and !$fieldItem->findSettings('related_model_name')->value)
								&nbsp;<span style="display: inline-block;" class="label label-danger animated tada">
										<i class="fa fa-warning"></i> 
										{{ trans('runsite::models.fields.Missing model name') }}
									</span>
							@endif

						</td>
						<td>
							@if($fieldItem->group)
								<span class="label label-primary">{{ $fieldItem->group->name }}</span>
							@endif
						</td>

						@if(Auth::user()->access()->application($application)->edit)
							<td>
								<div class="btn-group">
									{!! Form::open(['url'=>route('admin.models.fields.move.up', ['model_id'=>$model->id, 'id'=>$fieldItem->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
										<button type="submit" {{ $fieldItem->position == 1 ? 'disabled' : null }} class="btn btn-default btn-xs ripple remember-scroll-position" data-ripple-color="#5d5d5d"><i class="fa fa-caret-up"></i></button>
									{!! Form::close() !!}

									{!! Form::open(['url'=>route('admin.models.fields.move.down', ['model_id'=>$model->id, 'id'=>$fieldItem->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
										<button type="submit" {{ $fieldItem->position == count($fields) ? 'disabled' : null }} class="btn btn-default btn-xs ripple remember-scroll-position" data-ripple-color="#5d5d5d"><i class="fa fa-caret-down"></i></button>
									{!! Form::close() !!}
								</div>
							</td>
						@endif
						
						@if(Auth::user()->access()->application($application)->delete)
							<td>
								<button @if($fieldItem->name == 'is_active') disabled @endif data-toggle="modal" data-target="#destroy-field-{{ $fieldItem->id }}" class="btn btn-danger btn-xs">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@foreach($fields as $fieldItem)
	<div class="modal modal-danger fade" tabindex="-1" role="dialog" id="destroy-field-{{ $fieldItem->id }}">
	  <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('runsite::models.fields.Close') }}"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">{{ trans('runsite::models.fields.Are you sure') }}?</h4>
		  </div>
		  <div class="modal-body">
			<p>{{ trans('runsite::models.fields.Are you sure you want to delete the field') }} "{{ $fieldItem->name }}"?</p>
		  </div>
		  <div class="modal-footer">
			{!! Form::open(['url'=>route('admin.models.fields.destroy', ['model_id'=>$model->id, 'field_id'=>$fieldItem->id]), 'method'=>'delete']) !!}
				<button type="submit" class="btn btn-default btn-sm ripple" data-ripple-color="#ccc">{{ trans('runsite::models.fields.Delete field') }}</button>
			{!! Form::close() !!}
		  </div>
		</div>
	  </div>
	</div>
	@endforeach
@endsection
