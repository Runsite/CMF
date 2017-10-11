@extends('runsite::layouts.models')

@section('model')
    @if(! count($models))
        {{ trans('runsite::models.Models does not exist') }}
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Display name</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($models as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>
                                <a href="{{ route('admin.models.edit', $model->id) }}" style="display: block;"><b>{{ $model->display_name }}</b></a>
                            </td>
                            <td><span class="label label-default">{{ $model->name }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="xs-pl-15">
            {!! $models->links() !!}
        </div>
    @endif
@endsection