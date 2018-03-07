<div class="form-group {{ $errors->has('locale') ? ' has-error' : '' }}">
    {{ Form::label('locale', trans('runsite::languages.Locale')) }}
    {{ Form::text('locale', null, ['class'=>'form-control input-sm', 'autofocus'=>'true']) }}
    @if ($errors->has('locale'))
        <span class="help-block">
            <strong>{{ $errors->first('locale') }}</strong>
        </span>
    @endif
</div>

<div class="form-group {{ $errors->has('display_name') ? ' has-error' : '' }}">
    {{ Form::label('display_name', trans('runsite::languages.Display name')) }}
    {{ Form::text('display_name', null, ['class'=>'form-control input-sm']) }}
    @if ($errors->has('display_name'))
        <span class="help-block">
            <strong>{{ $errors->first('display_name') }}</strong>
        </span>
    @endif
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('is_active') ? ' has-error' : '' }}">
            {{ Form::label('is_active', trans('runsite::languages.Is active')) }}
            <input type="hidden" name="is_active" value="{{ $language->is_main ? '1' : '0' }}">
            <div class="runsite-checkbox">
                {{ Form::checkbox('is_active', 1, null, [ (! Auth::user()->access()->application($application)->edit or $language->is_main) ? 'disabled' : null]) }}
                <label for="is_active"></label>
            </div>
            @if ($errors->has('is_active'))
                <span class="help-block">
                    <strong>{{ $errors->first('is_active') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('is_main') ? ' has-error' : '' }}">
            {{ Form::label('is_main', trans('runsite::languages.Is main')) }}
            <input type="hidden" name="is_main" value="0">
            <div class="runsite-checkbox">
                {{ Form::checkbox('is_main', 1, null, [ (! Auth::user()->access()->application($application)->edit) ? 'disabled' : null]) }}
                <label for="is_main"></label>
            </div>
            @if ($errors->has('is_main'))
                <span class="help-block">
                    <strong>{{ $errors->first('is_main') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
