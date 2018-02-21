@extends('runsite::layouts.users')

@section('user')

<div class="xs-p-15">
    <div class="btn-group">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::users.New user') }}</a>
        <a href="{{ route('admin.users.invite.create') }}" class="btn btn-default btn-sm ripple" data-ripple-color="#333"><i class="fa fa-plus"></i> {{ trans('runsite::users.invite.New invite') }}</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('runsite::users.Name') }}</th>
                <th>{{ trans('runsite::users.Email') }}</th>
                <th>{{ trans('runsite::users.Groups') }}</th>
                <th>{{ trans('runsite::users.Delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <a class="ripple" data-ripple-color="#333" href="{{ route('admin.users.edit', $user->id) }}" style="display: block;"><b>{{ $user->name }}</b></a>
                    </td>
                    <td><span class="label label-default">{{ $user->email }}</span></td>
                    <td>
                        @foreach($user->groups as $group)
                            <span class="label label-primary">{{ $group->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        {!! Form::open(['url'=>route('admin.users.destroy', $user->id), 'method'=>'delete']) !!}
                            <button onclick="return confirm('{{ trans('runsite::users.Are you sure') }}?')" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="xs-pl-15">
    {!! $users->links() !!}
</div>
@endsection
