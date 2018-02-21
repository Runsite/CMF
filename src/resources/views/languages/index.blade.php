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
                <th>{{ trans('runsite::languages.Fallback locale') }}</th>
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
                        @if($language->locale == config('app.fallback_locale'))
                            <span class="label label-success">
                                <i class="fa fa-check"></i>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(config('laravellocalization.supportedLocales.'.$language->locale))
                            <span class="label label-success">
                                <i class="fa fa-check"></i>
                            </span>
                        @else
                            <span style="display: inline-block;" class="label label-danger animated tada" data-toggle="tooltip" title="{{ trans('runsite::languages.Add or uncomment section with this locale in') }} config/laravellocalization.php">
                                <i class="fa fa-warning"></i> 
                                {{ trans('runsite::languages.Not available') }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(laravellocalization::getCurrentLocale() != $language->locale)
                            <button type="button" data-toggle="modal" data-target="#destroy-language-{{ $language->id }}" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        @else
                            <span class="label label-warning" data-toggle="tooltip" title="{{ trans('runsite::languages.You can not delete the current language') }}">{{ trans('runsite::languages.Current language') }}</span>
                        @endif
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="xs-pl-15">
    {!! $languages->links() !!}
</div>

@foreach($languages as $languageItem)
    @if(laravellocalization::getCurrentLocale() != $languageItem->locale)
        <div class="modal modal-danger fade" tabindex="-1" role="dialog" id="destroy-language-{{ $languageItem->id }}">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('runsite::languages.Close') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('runsite::languages.Are you sure') }}?</h4>
              </div>
              <div class="modal-body">
                <p>{{ trans('runsite::languages.Are you sure you want to delete language') }} "{{ $languageItem->display_name }}"?</p>
              </div>
              <div class="modal-footer">
                {!! Form::open(['url'=>route('admin.languages.destroy', $languageItem), 'method'=>'delete']) !!}
                    <button type="submit" class="btn btn-default btn-sm ripple" data-ripple-color="#ccc">{{ trans('runsite::languages.Delete language') }}</button>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
    @endif
@endforeach
@endsection
