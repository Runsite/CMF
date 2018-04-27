<?php

namespace Runsite\CMF\Models\User;

use Illuminate\{
    Notifications\Notifiable,
    Foundation\Auth\User as Authenticatable
};
use Runsite\CMF\Models\{
    User\Access\Access,
    Node\Node,
    Notification
};

class User extends Authenticatable
{
    use Notifiable;

    public $table = 'rs_users';
    public $imagesPath = null;
    public $thumbsFolderName = 'thumbs';
    public $imageWidth = 100;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_locked', 'email', 'password', 'image',
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

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->imagesPath = config('runsite.cmf.account.images.path');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rs_user_group', 'user_id', 'group_id');
    }

    public function access()
    {
        return new Access($this);
    }

    public function getAccess(Node $node)
    {
        $maxAccess = 0; // Default: no access
        $maxAccessModel = 0; // Default: no access

        foreach($this->groups as $group)
        {
            $access = $group->getAccess($node)->access;
            if($access > $maxAccess)
            {
                // if this group has access and access is higher
                $maxAccess = $access;
            }

            $modelAccess = $group->getAccessModel($node->model)->access;
            if($modelAccess > $maxAccessModel)
            {
                // if this group has access and access is higher
                $maxAccessModel = $modelAccess;
            }
        }

        if($maxAccessModel == 2)
        {
            // User can manage nodes of this model
            $maxAccessModel = 3; 
        }

        return $maxAccessModel < $maxAccess ? $maxAccessModel : $maxAccess;
    }

    public function canRead(Node $node)
    {
        return $this->getAccess($node) >= 1;
    }

    public function canEdit(Node $node)
    {
        return $this->getAccess($node) >= 2;
    }

    public function canDelete(Node $node)
    {
        return $this->getAccess($node) == 3;
    }

    public function imagePath()
    {
        return asset($this->image ? $this->imagesPath . '/' . $this->id . '/' . $this->thumbsFolderName . '/' . $this->image : 'vendor/runsite/asset/images/no-image.png');
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

    public function delete()
    {
        // TODO
        return parent::delete();
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_id');
    }

    public function notify(Node $node = null, $message, $icon_name=null)
    {
        return Notification::create([
            'user_id' => $this->id,
            'node_id' => $node ? $node->id : null,
            'message' => $message,
            'icon_name' => $icon_name,
        ]);
    }
}
