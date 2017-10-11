<?php 

namespace Runsite\CMF\Models\Model\Field;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    protected $table = 'rs_field_settings';
    protected $fillable = ['field_id', 'parameter', 'value'];

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}