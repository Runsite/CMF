<div class="form-group">
	<label>{{ trans('runsite::models.fields.Other fields') }}</label>
	<div class="box">
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				@foreach($fields as $fieldItem)
					<li class="{{ $fieldItem->id == $field->id ? 'active' : null }}">
						<a class="ripple" href="{{ route(Route::current()->getName(), ['model_id'=>$model->id, 'field_id'=>$fieldItem->id]) }}" onclick="$(this).parent().parent().find('li').removeClass('active'); $(this).parent().addClass('active')">
							{{ $fieldItem->display_name }}
							<span class="label label-primary pull-right">{{ $fieldItem->name }}</span>
						</a>
					</li>
				@endforeach
			  </ul>
		</div>
	</div>
</div>
