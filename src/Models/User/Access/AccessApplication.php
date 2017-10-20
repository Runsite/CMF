<?php 

namespace Runsite\CMF\Models\User\Access;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\{
	Application,
	User\Group
};

class AccessApplication extends Eloquent
{
    protected $table = 'rs_group_application_access';
    protected $fillable = ['group_id', 'application_id', 'access'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}