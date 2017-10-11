@extends('runsite::layouts.models')

@section('model')

    @if(Auth::user()->access()->application($application)->edit)
        <div class="xs-p-15">
            <a href="{{ route('admin.models.fields.create', $model->id) }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::models.fields.New field') }}</a>
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
                @foreach($fields as $field)
                    <tr class="{{ (Session::has('highlight') and Session::get('highlight') == $field->id) ? 'success' : null }}">
                        <td>{{ $field->id }}</td>
                        <td>
                            <a href="{{ route('admin.models.fields.edit', ['model_id'=>$model->id, 'id'=>$field->id]) }}" style="display: block;"><b>{{ $field->display_name }}</b></a>
                        </td>
                        <td><span class="label label-success">{{ $field->name }}</span></td>
                        <td><span class="label label-default">{{ $field->types[$field->type_id]::$displayName }}</span></td>
                        <td>
                            @if($field->group)
                                <span class="label label-primary">{{ $field->group->name }}</span>
                            @endif
                        </td>

                        @if(Auth::user()->access()->application($application)->edit)
                            <td>
                                <div class="btn-group">
                                    {!! Form::open(['url'=>route('admin.models.fields.move.up', ['model_id'=>$model->id, 'id'=>$field->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
                                        <button type="submit" {{ $field->position == 1 ? 'disabled' : null }} class="btn btn-default btn-xs"><i class="fa fa-caret-up"></i></button>
                                    {!! Form::close() !!}

                                    {!! Form::open(['url'=>route('admin.models.fields.move.down', ['model_id'=>$model->id, 'id'=>$field->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
                                        <button type="submit" {{ $field->position == count($fields) ? 'disabled' : null }} class="btn btn-default btn-xs"><i class="fa fa-caret-down"></i></button>
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        @endif
                        
                        @if(Auth::user()->access()->application($application)->delete)
                            <td>
                                {!! Form::open(['url'=>route('admin.models.fields.destroy', ['model_id'=>$model->id, 'field_id'=>$field->id]), 'method'=>'delete']) !!}
                                    <button @if($field->name == 'is_active') disabled @endif onclick="return confirm('{{ trans('runsite::models.fields.Are you sure') }}?')" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                {!! Form::close() !!}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection