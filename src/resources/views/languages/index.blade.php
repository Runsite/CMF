@extends('runsite::layouts.translations')

@section('translation')

<div class="xs-p-15">
    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::languages.New language') }}</a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('runsite::languages.Display name') }}</th>
                <th>{{ trans('runsite::languages.Delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($languages as $language)
                <tr>
                    <td>{{ $language->id }}</td>
                    <td>
                        <a class="ripple" data-ripple-color="#333" href="{{ route('admin.languages.edit', $language) }}" style="display: block;"><b>{{ $language->display_name }} ({{ $language->locale }})</b></a>
                    </td>
                    <td>
                        {!! Form::open(['url'=>route('admin.languages.destroy', $language), 'method'=>'delete']) !!}
                            <button onclick="return confirm('{{ trans('runsite::languages.Are you sure') }}?')" type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="xs-pl-15">
    {!! $languages->links() !!}
</div>
@endsection
