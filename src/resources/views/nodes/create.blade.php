@extends('runsite::layouts.nodes')

@section('form-open')
	{!! Form::open(['url'=>route('admin.nodes.store', ['model'=>$model, 'parent_node' => $node]), 'method'=>'post', 'class'=>'form-horizontal', 'files'=>true]) !!}
@endsection

@section('node')
	@include('runsite::nodes.form')
	<div class="form-group xs-mb-0">
		<div class="col-sm-10 col-sm-push-2">
			<div class="xs-pl-15">
				<div class="form-group">
					<label for="create_next_one">
						{{ trans('runsite::nodes.Create the next one after') }}
						<br><small class="text-muted" style="font-weight: normal;">{{ trans('runsite::nodes.Opens another form immediately after creation') }}.</small>
					</label>
					
					<div class="runsite-checkbox">
						{{ Form::checkbox('create_next_one', 1, Session::has('create_next_one') ? true : null, ['id'=>'create_next_one']) }}
						<label for="create_next_one"></label>
					</div>
					
				</div>
			</div>
			<button type="submit" class="btn btn-primary btn-sm ripple">{{ trans('runsite::nodes.Create') }}</button>
		</div>
	</div>
@endsection
