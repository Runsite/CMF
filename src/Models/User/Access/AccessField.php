<?php 

namespace Runsite\CMF\Models\User\Access;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\{
    Model\Field\Field,
    User\Group
};

class AccessField extends Eloquent
{
    protected $table = 'rs_group_field_access';
    protected $fillable = ['group_id', 'field_id', 'access'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}