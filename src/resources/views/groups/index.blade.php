@extends('runsite::layouts.users')

@section('user')

<div class="xs-p-15">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::models.fields.New field') }}</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{ $group->id }}</td>
                    <td>
                        <a href="{{ route('admin.groups.edit', $group->id) }}" style="display: block;"><b>{{ $group->name }}</b></a>
                    </td>
                    <td><span class="text-muted">{{ $group->description }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="xs-pl-15">
    {!! $groups->links() !!}
</div>
@endsection