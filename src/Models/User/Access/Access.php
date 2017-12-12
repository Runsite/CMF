<?php 

namespace Runsite\CMF\Models\User\Access;

use Runsite\CMF\Models\{
    User\User,
    User\Access\AccessApplication,
    User\Access\AccessNode,
    User\Access\AccessModel,
    User\Access\AccessField,
    Node\Node,
    Model\Model,
    Model\Field\Field,
    Application
};

class Access {

    protected $groups;
    
    public $read = false;
    public $edit = false;
    public $delete = false; 

    public function __construct(User $user)
    {
        $this->groups = $user->groups;
    }

    protected function getAccess($class)
    {
        $maxAccess = 0; // Default: no access
        foreach($this->groups as $group)
        {
            $tmp_class = clone $class;
            $access = $tmp_class->where('group_id', $group->id)->first()->access;
            if($access > $maxAccess)
            {
                // if this group has access and access is higher
                $maxAccess = $access;
                if($maxAccess == 3)
                {
                    break;
                }
            }
        }

        if($maxAccess > 0)
        {
            $this->read = true;
        }

        if($maxAccess > 1)
        {
            $this->edit = true;
        }

        if($maxAccess > 2)
        {
            $this->delete = true;
        }

        return $this;
    }

    public function application(Application $application)
    {
        return $this->getAccess(AccessApplication::where('application_id', $application->id));
    }

    public function node(Node $node)
    {
        return $this->getAccess(AccessNode::where('node_id', $node->id));
    }

    public function model(Model $model)
    {
        return $this->getAccess(AccessModel::where('model_id', $model->id));
    }

    public function field(Field $field)
    {
        return $this->getAccess(AccessField::where('field_id', $field->id));
    }
}