@foreach($languages as $k=>$language)
	<div class="tab-pane {{ !$k ? 'active' : null }}" id="lang-{{ $language->id }}">

		@if($model->groups)
			<div class="xs-pb-15">
				<div class="btn-group" data-toggle="buttons">
					<a data-toggle="tab" href="#no-group-{{ $language->id }}" class="btn btn-default active btn-sm ripple"><input type="radio" /> {{ trans('runsite::nodes.groups.Main') }}</a>
					@foreach($model->groups as $group)
						<a data-toggle="tab" href="#group-{{ $group->id }}-lang-{{ $language->id }}" class="btn btn-default btn-sm ripple"><input type="radio" /> {{ $group->name }}</a>
					@endforeach
				</div>
			</div>
		@endif
		
		<div class="tab-content no-padding">
			<div class="tab-pane active" id="no-group-{{ $language->id }}">
				@foreach($model->fields as $field)
					@if(!$field->group_id)
						@include('runsite::models.fields.field_types.'.$field->getControlPath(), ['value'=>$field->getValue((isset($dynamic) ? $dynamic : null), (isset($language) ? $language : null))])
					@endif
				@endforeach
			</div>
			@foreach($model->groups as $group)
				<div class="tab-pane" id="group-{{ $group->id }}-lang-{{ $language->id }}">
					@foreach($model->fields as $field)
						@if($field->group_id == $group->id)
							@include('runsite::models.fields.field_types.'.$field->getControlPath(), ['value'=>$field->getValue((isset($dynamic) ? $dynamic : null), (isset($language) ? $language : null))])
						@endif
					@endforeach
				</div>
			@endforeach
		</div>
		
		
		@if(isset($dynamic))
			<div class="form-group sm-mb-0">
				<div class="col-sm-2 text-sm-right"><small class="text-muted">Created at</small></div>
				<div class="col-sm-10">
					<small class="text-muted">{{ $dynamic->where('language_id', $language->id)->first()->created_at->format('d.m.Y H:i') }}</small>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 text-sm-right"><small class="text-muted">Updated at</small></div>
				<div class="col-sm-10">
					<small class="text-muted">{{ $dynamic->where('language_id', $language->id)->first()->updated_at->format('d.m.Y H:i') }}</small>
				</div>
			</div>
		@endif
		
	</div>
@endforeach

