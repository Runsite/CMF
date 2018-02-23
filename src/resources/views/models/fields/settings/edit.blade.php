@extends('runsite::layouts.models')

@section('model')
	<div class="xs-p-15 xs-pb-15">
		<div class="row">
			<div class="col-lg-8">
				{!! Form::open(['url'=>route('admin.models.fields.settings.update', ['model_id'=>$model->id, 'field_id'=>$field->id]), 'method'=>'patch']) !!}

					@foreach($field->type()::$defaultSettings as $name=>$setting)
						<div class="form-group {{ ($errors->has($name) or ($name == 'related_model_name' and !$field->findSettings('related_model_name')->value)) ? ' has-error' : '' }}">
							{{ Form::label($name, trans('runsite::models.fields.settings.'.$name)) }}
							{{-- {{ Form::select($name, $field->prepareSettingsArray($setting['variants']), null, ['class'=>'form-control input-sm']) }} --}}
							@if(is_array($setting['variants']))
								<select name="{{ $name }}" id="{{ $name }}" class="form-control input-sm" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
									@foreach($setting['variants'] as $variant)
										<option @if($variant == $settings->where('parameter', $name)->first()->value) selected  @endif value="{{ $variant }}">{{ $variant }}</option>
									@endforeach
								</select>
							@else
								<input type="text" name="{{ $name }}" id="{{ $name }}" class="form-control input-sm" value="{{ old($name) ?? $settings->where('parameter', $name)->first()->value }}" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
							@endif
							
							@if ($errors->has($name))
								<span class="help-block">
									<strong>{{ $errors->first($name) }}</strong>
								</span>
							@endif
						</div>
					@endforeach

					{{-- Field size --}}
					@if($column)
						<div class="form-group">
							{{ Form::label('field_length', trans('runsite::models.fields.settings.Column length')) }}
							<input type="text" class="form-control input-sm" name="field_length" id="field_length" value="{{ $column->getLength() ?: ($column->getPrecision().','.$column->getScale()) }}" {{ ! Auth::user()->access()->application($application)->edit ? 'disabled' : null }}>
							<small class="text-muted"><i class="fa fa-info-circle text-info"></i> {{ trans('runsite::models.fields.settings.For fields, like decimal, you can use two numbers, separated by a comma') }}</small>
						</div>
					@endif

					@if(Auth::user()->access()->application($application)->edit)
						<button class="btn btn-primary btn-sm ripple">{{ trans('runsite::models.fields.settings.Update') }}</button>
					@endif
				{!! Form::close() !!}
			</div>
			<div class="col-lg-4 visible-lg">
				@include('runsite::models.fields.other_fields')
			</div>
		</div>
	</div>
@endsection
