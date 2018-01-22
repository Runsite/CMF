@extends('runsite::layouts.users')

@section('user')

<div class="xs-p-15">
    <a href="{{ route('admin.groups.create') }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::groups.New group') }}</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('runsite::groups.Name') }}</th>
                <th>{{ trans('runsite::groups.Description') }}</th>
                <th>{{ trans('runsite::groups.Delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{ $group->id }}</td>
                    <td>
                        <a class="ripple" data-ripple-color="#333" href="{{ route('admin.groups.edit', $group->id) }}" style="display: block;"><b>{{ $group->name }}</b></a>
                    </td>
                    <td><span class="text-muted">{{ $group->description }}</span></td>

                    <td>
                        {!! Form::open(['url'=>route('admin.groups.destroy', $group), 'method'=>'delete']) !!}
                            <button onclick="return confirm('{{ trans('runsite::models.groups.Are you sure') }}?')" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="xs-pl-15">
    {!! $groups->links() !!}
</div>
@endsection
