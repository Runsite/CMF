@extends('runsite::layouts.models')

@section('model')

    <div class="xs-p-15">
        <a href="{{ route('admin.models.groups.create', $model->id) }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::models.groups.New group') }}</a>
    </div>

    @if(count($groups))
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('runsite::models.groups.Name') }}</th>
                        <th>{{ trans('runsite::models.groups.Position') }}</th>
                        <th>{{ trans('runsite::models.groups.Delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                        <tr class="{{ (Session::has('highlight') and Session::get('highlight') == $group->id) ? 'success' : null }}">
                            <td>{{ $group->id }}</td>
                            <td>
                                <a href="{{ route('admin.models.groups.edit', ['model_id'=>$model->id, 'id'=>$group->id]) }}"><b>{{ $group->name }}</b></a>
                            </td>
                            <td>
                                <div class="btn-group">
                                    {!! Form::open(['url'=>route('admin.models.groups.move.up', ['model_id'=>$model->id, 'group_id'=>$group->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
                                        <button type="submit" {{ $group->position == 1 ? 'disabled' : null }} class="btn btn-default btn-xs"><i class="fa fa-caret-up"></i></button>
                                    {!! Form::close() !!}

                                    {!! Form::open(['url'=>route('admin.models.groups.move.down', ['model_id'=>$model->id, 'group_id'=>$group->id]), 'method'=>'patch', 'style'=>'display:inline;']) !!}
                                        <button type="submit" {{ $group->position == count($groups) ? 'disabled' : null }} class="btn btn-default btn-xs"><i class="fa fa-caret-down"></i></button>
                                    {!! Form::close() !!}
                                </div>
                            </td>
                            <td>
                                {!! Form::open(['url'=>route('admin.models.groups.destroy', ['model_id'=>$model->id, 'field_id'=>$group->id]), 'method'=>'delete']) !!}
                                    <button onclick="return confirm('{{ trans('runsite::models.groups.Are you sure') }}?')" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
@endsection