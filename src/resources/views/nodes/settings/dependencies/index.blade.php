@extends('runsite::layouts.nodes')

@section('node')
    <div class="xs-p-15">
        <i class="fa fa-info-circle text-info" aria-hidden="true"></i>
        {{ trans('runsite::models.dependencies.Add dependent models') }}.
        {{ trans('runsite::models.dependencies.The sections of the models on the right column can be created under the sections of this model') }}.
    </div>
    <div class="row">
        <div class="col-xs-6 xs-pr-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ trans('runsite::models.dependencies.Available models') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($available_models as $available_model)
                            <tr>
                                <td>
                                    {!! Form::open(['url'=>route('admin.nodes.settings.dependencies.store', $node), 'method'=>'post']) !!}
                                        <input type="hidden" name="depended_model_id" value="{{ $available_model->id }}">
                                        <button type="submit" class="btn btn-default btn-sm btn-block ripple">
                                            {{ $available_model->display_name }} <i class="fa fa-plus"></i>
                                        </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-xs-6 xs-pl-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ trans('runsite::models.dependencies.Depended models') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($depended_models as $depended_model)
                            <tr class="{{ (Session::has('highlight') and Session::get('highlight') == $depended_model->id) ? 'success' : null }}">
                                <td>
                                    <div class="btn-group btn-group-justified">
                                        <div class="btn-group" style="width:10%">
                                            {!! Form::open(['url'=>route('admin.nodes.settings.dependencies.delete', $node), 'method'=>'delete', 'style'=>'display:inline;']) !!}
                                                <input type="hidden" name="depended_model_id" value="{{ $depended_model->dependedModel->id }}">
                                                
                                                    <button type="submit" class="btn btn-default btn-sm ripple">
                                                        {{ $depended_model->dependedModel->display_name }} <i class="fa fa-times text-danger"></i>
                                                    </button>
                                                
                                            {!! Form::close() !!}
                                        </div>

                                        <div class="btn-group">
                                            {!! Form::open(['url'=>route('admin.nodes.settings.dependencies.move.up', ['node'=>$node, 'id'=>$depended_model->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
                                                    <button type="submit" {{ $depended_model->position == 1 ? 'disabled' : null }} class="btn btn-default btn-sm"><i class="fa fa-caret-up"></i></button>
                                            {!! Form::close() !!}
                                        </div>

                                        <div class="btn-group">
                                            {!! Form::open(['url'=>route('admin.nodes.settings.dependencies.move.down', ['node'=>$node, 'id'=>$depended_model->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
                                                    <button type="submit" {{ $depended_model->position == count($depended_models) ? 'disabled' : null }} class="btn btn-default btn-sm"><i class="fa fa-caret-down"></i></button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @foreach($depended_locked_models as $depended_locked_model)
                            <tr>
                                <td>
                                    <div class="btn-group btn-group-justified">
                                        <div class="btn-group" style="width:10%">
                                            <button disabled type="submit" class="btn btn-default btn-sm">
                                                {{ $depended_locked_model->dependedModel->display_name }} <i class="fa fa-lock text-danger"></i>
                                            </button>
                                        </div>

                                        
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection