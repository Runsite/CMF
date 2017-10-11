<?php

namespace Runsite\CMF\Models\User;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Runsite\CMF\Models\User\Access\Access;

class User extends Authenticatable
{
    use Notifiable;

    public $table = 'rs_users';
    public $imagesPath = 'uploads/account/images';
    public $thumbsFolderName = 'thumbs';
    public $imageWidth = 100;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['last_action_at'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rs_user_group', 'user_id', 'group_id');
    }

    public function access()
    {
        return new Access($this);
    }

    public function getAccess($node_id)
    {
        $maxAccess = 0; // Default: no access

        foreach($this->groups as $group)
        {
            $access = $group->getAccess($node_id)->access;
            if($access > $maxAccess)
            {
                // if this group has access and access is higher
                $maxAccess = $access;
            }
        }

        return $maxAccess;
    }

    public function canRead($node_id)
    {
        return $this->getAccess($node_id) >= 1;
    }

    public function canEdit($node_id)
    {
        return $this->getAccess($node_id) >= 2;
    }

    public function canDelete($node_id)
    {
        return $this->getAccess($node_id) == 3;
    }

    public function imagePath()
    {
        return asset($this->image ? $this->imagesPath . '/' . $this->id . '/' . $this->thumbsFolderName . '/' . $this->image : 'vendor/runsite/images/no-image.png');
    }

    public function imagePathOriginal()
    {
        return asset($this->image ? $this->imagesPath . '/' . $this->id . '/' . $this->image : null);
    }

    public function assignGroup(Group $group)
    {
        UserGroup::create(['user_id'=>$this->id, 'group_id'=>$group->id]);
    }

    public function getAccessToApplication($application_name)
    {
        $maxAccess = 0; // Default: no access

        foreach($this->groups as $group)
        {
            $access = $group->getAccessToApplication($application_name)->access;
            if($access > $maxAccess)
            {
                // if this group has access and access is higher
                $maxAccess = $access;
            }
        }

        return $maxAccess;
    }

    public function hasAccessToApplication(string $application_name, string $level)
    {
        $access = $this->getAccessToApplication($application_name);

    }
}
