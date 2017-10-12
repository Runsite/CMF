@foreach($languages as $k=>$language)
	<div class="tab-pane {{ !$k ? 'active' : null }}" id="lang-{{ $language->id }}">

		<div class="xs-pb-15">
			<div class="btn-group">
				<a href="#" class="btn btn-primary btn-sm ripple">Group</a>
				@foreach($model->groups as $group)
					<a href="#" class="btn btn-default btn-sm ripple">{{ $group->name }}</a>
				@endforeach
			</div>
		</div>

		@foreach($model->groups as $group)
			@foreach($model->fields as $field)
				@if(! $field->group_id)
					@include('runsite::models.fields.field_types.'.$field->getControlPath(), ['value'=>$field->getValue((isset($dynamic) ? $dynamic : null), (isset($language) ? $language : null))])
				@endif
			@endforeach
		@endforeach
		
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

