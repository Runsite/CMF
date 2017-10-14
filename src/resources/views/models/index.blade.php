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
                    @foreach($models as $modelItem)
                        <tr>
                            <td>{{ $modelItem->id }}</td>
                            <td>
                                <a class="ripple" data-ripple-color="#333" href="{{ route('admin.models.edit', $modelItem->id) }}" style="display: block;"><b>{{ $modelItem->display_name }}</b></a>
                            </td>
                            <td><span class="label label-default">{{ $modelItem->name }}</span></td>
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