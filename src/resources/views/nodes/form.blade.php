@foreach($languages as $k=>$language)
	<div class="tab-pane {{ $active_language_tab == $language->locale ? 'active' : null }}" id="lang-{{ $language->id }}">

		
			<div class="xs-pb-15">
				@if(count($model->groups))
					<div class="btn-group" data-toggle="buttons">
						<a data-toggle="tab" href="#no-group-{{ $language->id }}" class="btn btn-default active btn-sm ripple">
							<input type="radio" /> {{ trans('runsite::nodes.groups.Main') }}
							@foreach($model->fields as $field)
								@if(!$field->group_id and $errors->has($field->name . '.' . $language->id))
									<i class="fa fa-exclamation-circle text-danger animated tada" aria-hidden="true"></i>
									@break
								@endif
							@endforeach
						</a>
						@foreach($model->groups as $group)
							<a data-toggle="tab" href="#group-{{ $group->id }}-lang-{{ $language->id }}" class="btn btn-default btn-sm ripple">
								<input type="radio" /> {{ $group->name }}
								@foreach($model->fields as $field)
									@if($field->group_id == $group->id and $errors->has($field->name . '.' . $language->id))
										<i class="fa fa-exclamation-circle text-danger animated tada" aria-hidden="true"></i>
										@break
									@endif
								@endforeach
							</a>
						@endforeach
					</div>
				@endif
				{{-- Prev/Next Node --}}
				@if($prev_node or $next_node)
					<div class="btn-group pull-right">
						<a href="{{ $prev_node ? route('admin.nodes.edit', ['node'=>$prev_node, 'depended_model_id'=>$depended_model ? $depended_model->id : null]) : 'javascript: void(0)' }}" {{ !$prev_node ? 'disabled' : null }} class="btn btn-default btn-sm ripple"><i class="fa fa-caret-left"></i></a>
						<a href="{{ $next_node ? route('admin.nodes.edit', ['node'=>$next_node, 'depended_model_id'=>$depended_model ? $depended_model->id : null]) : 'javascript: void(0)' }}" {{ !$next_node ? 'disabled' : null }} class="btn btn-default btn-sm ripple"><i class="fa fa-caret-right"></i></a>
					</div>
				@endif
				{{-- [end] Prev/Next Node --}}
			</div>
		
		
		<div class="tab-content no-padding">
			<div class="tab-pane active" id="no-group-{{ $language->id }}">
				@foreach($model->fields as $field)
					@if(!$field->group_id and ($language->id == $defaultLanguage->id or !$field->is_common))
						@php($controllPath = $field->getControlPath())

						@if($controllPath)
							@include('runsite::models.fields.field_types.'.$controllPath, ['value'=>$field->getValue((isset($dynamic) ? $dynamic : null), (isset($language) ? $language : null))])
						@endif
						
					@endif
				@endforeach
			</div>
			@foreach($model->groups as $group)
				<div class="tab-pane" id="group-{{ $group->id }}-lang-{{ $language->id }}">
					@foreach($model->fields as $field)
						@if($field->group_id == $group->id  and ($language->id == $defaultLanguage->id or !$field->is_common))
							@php($controllPath = $field->getControlPath())

							@if($controllPath)
								@include('runsite::models.fields.field_types.'.$controllPath, ['value'=>$field->getValue((isset($dynamic) ? $dynamic : null), (isset($language) ? $language : null))])
							@endif
						@endif
					@endforeach
				</div>
			@endforeach
		</div>
		
		
		@if(isset($dynamic))
			<div class="form-group sm-mb-0">
				<div class="col-sm-2 text-sm-right"><small class="text-muted">{{ trans('runsite::nodes.Created at') }}</small></div>
				<div class="col-sm-10">
					<small class="text-muted">{{ $dynamic->where('language_id', $language->id)->first()->created_at->format('d.m.Y H:i') }}</small>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 text-sm-right"><small class="text-muted">{{ trans('runsite::nodes.Updated at') }}</small></div>
				<div class="col-sm-10">
					<small class="text-muted">{{ $dynamic->where('language_id', $language->id)->first()->updated_at->format('d.m.Y H:i') }}</small>
				</div>
			</div>
		@endif
		
	</div>
@endforeach

