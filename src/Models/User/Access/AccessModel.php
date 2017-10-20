<?php 

namespace Runsite\CMF\Models\User\Access;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\{
    Model\Model,
    User\Group
};

class AccessModel extends Eloquent
{
    protected $table = 'rs_group_model_access';
    protected $fillable = ['group_id', 'model_id', 'access'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }
}