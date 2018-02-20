@extends('runsite::layouts.translations')

@section('translation')

<div class="xs-p-15">
    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary btn-sm ripple"><i class="fa fa-plus"></i> {{ trans('runsite::languages.New language') }}</a>
</div>

@foreach(config('laravellocalization.supportedLocales') as $locale=>$data)
    @if(!$languages->where('locale', $locale)->count())
        <div class="xs-p-15">
            <div class="alert alert-danger">
                <i class="fa fa-warning"></i> 
                {{ trans('runsite::languages.You have unsupported locales in configuration file') }}. 
                {{ trans('runsite::languages.Comment it in') }} <b>config/laravellocalization.php </b>
                {{ trans('runsite::languages.or add locale to panel') }}.
            </div>
        </div>
        @break
    @endif
@endforeach

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('runsite::languages.Display name') }}</th>
                <th>{{ trans('runsite::languages.Available in config') }}</th>
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
                        @if(config('laravellocalization.supportedLocales.'.$language->locale))
                            <span class="label label-success">
                                <i class="fa fa-check"></i>
                            </span>
                        @else
                            <span class="label label-danger" data-toggle="tooltip" title="{{ trans('runsite::languages.Add or uncomment section with this locale in') }} config/laravellocalization.php">
                                <i class="fa fa-warning"></i> 
                                {{ trans('runsite::languages.Not available') }}
                            </span>
                        @endif
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
