@extends('runsite::layouts.translations')
@section('translation')

<div class="pad">
    {!! Form::open(['url'=>route('admin.translations.index'), 'method'=>'get']) !!}
    <div class="form-group">
        <div class="input-group">
            <input type="text" class="form-control input-sm" name="search" value="{{ request('search') }}" placeholder="{{ trans('runsite::translations.Search') }}">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm ripple" type="button"><i class="fa fa-search"></i></button>
            </span>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ trans('runsite::translations.Key') }}</th>
                <th>{{ trans('runsite::translations.Values') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($translations as $translation)
                <tr>
                    <td>
                        <a class="ripple" data-ripple-color="#333" href="{{ route('admin.translations.edit', $translation->id) }}" style="display: block;"><b>{{ $translation->key }}</b></a>
                    </td>
                    <td>
                        @foreach($translation->variants() as $variant)
                            <span class="label label-default">{{ $variant->value }}</span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="xs-pl-15">
    {!! $translations->links() !!}
</div>

@endsection
