<div class="form-group">
    <label class="col-sm-2" for="">{{ $field->display_name }}</label>
    <div class="col-sm-10">
        <input 
            type="text" 
            class="form-control input-sm" 
            name="{{ $field->name }}[{{ $language->id }}]" 
            value="{{ old($field->name[$language->id]) ?: $dynamic->where('language_id', $language->id)->first()->{$field->name} }}">
    </div>
</div>
