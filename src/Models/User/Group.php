<?php 

namespace Runsite\CMF\Models\User;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Runsite\CMF\Models\{
    Node\Node,
    Model\Model,
    Model\Field\Field,
    User\Access\AccessNode,
    User\Access\AccessField,
    User\Access\AccessModel,
    User\Access\AccessApplication,
    Application
};
use Exception;

class Group extends Eloquent
{
    protected $table = 'rs_groups';
    protected $fillable = ['name', 'description'];

    public function getAccessToApplication(Application $application)
    {
        return AccessApplication::where('group_id', $this->id)->where('application_id', $application->id)->first();
    }

    public function getAccess(Node $node)
    {
        return AccessNode::where('group_id', $this->id)->where('node_id', $node->id)->first();
    }

    public function canRead(Node $node)
    {
        return $this->getAccess($node)->access >= 1;
    }

    public function canEdit(Node $node)
    {
        return $this->getAccess($node)->access >= 2;
    }

    public function canDelete(Node $node)
    {
        return $this->getAccess($node)->access == 3;
    }

    public function getAccessField($field_id)
    {
        return AccessField::where('group_id', $this->id)->where('field_id', $field_id)->first();
    }

    public function canReadField($field_id)
    {
        return $this->getAccessField($field_id)->access >= 1;
    }

    public function canEditField($field_id)
    {
        return $this->getAccessField($field_id)->access >= 2;
    }

    public function getAccessModel(Model $model)
    {
        return AccessModel::where('group_id', $this->id)->where('model_id', $model->id)->first();
    }

    public function canSeeModel(Model $model)
    {
        return $this->getAccessModel($model)->access >= 1;
    }

    public function canCreateModel(Model $model)
    {
        return $this->getAccessModel($model)->access >= 2;
    }

    public function canDeleteModel(Model $model)
    {
        return $this->getAccessModel($model)->access >= 3;
    }

    public function getAccessApplication(Application $application)
    {
        return AccessApplication::where('group_id', $this->id)->where('application_id', $application->id)->first();
    }

    public function canReadApplication(Application $application)
    {
        return $this->getAccessApplication($application)->access >= 1;
    }

    public function canManageApplication(Application $application)
    {
        return $this->getAccessApplication($application)->access >= 2;
    }

    public static function create(array $attributes = [])
    {
        $group = parent::query()->create($attributes);

        foreach(Node::get() as $node)
        {
            AccessNode::create([
                'group_id' => $group->id,
                'node_id' => $node->id,
            ]);
        }

        foreach(Field::get() as $field)
        {
            AccessField::create([
                'group_id' => $group->id,
                'field_id' => $field->id,
            ]);
        }

        foreach(Model::get() as $model)
        {
            AccessModel::create([
                'group_id' => $group->id,
                'model_id' => $model->id,
            ]);
        }

        foreach(Application::get() as $application)
        {
            AccessApplication::create([
                'group_id' => $group->id,
                'application_id' => $application->id,
            ]);
        }

        return $group;
    }

    public function assignAccessToApplication(int $access, Application $application)
    {
        return AccessApplication::where('group_id', $this->id)->where('application_id', $application->id)->update([
            'access' => $access,
        ]);
    }

    public function assignAccess($access, $node_id, $recursively)
    {
        AccessNode::where('group_id', $this->id)->where('node_id', $node_id)->update([
            'access' => $access,
        ]);

        if($recursively)
        {
            $childNodes = Node::where('parent_id', $node_id)->get();
            foreach($childNodes as $node)
            {
                $this->assignAccess($access, $node->id, $recursively);
            }
        }

        return true;
    }
}
