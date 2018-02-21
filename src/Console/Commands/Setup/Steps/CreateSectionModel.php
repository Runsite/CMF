<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Model\Field\FieldGroup;

class CreateSectionModel
{
    public $message = 'Creating section model';

    public function handle($options)
    {
        $model = Model::create(['name' => 'section', 'display_name' => 'Section', 'display_name_plural' => 'Sections'], true, true);
        $group = FieldGroup::create(['name' => 'SEO', 'model_id' => $model->id]);

        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('string'), 'name'=>'name', 'display_name'=>'Name']);
        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('string'), 'group_id' => $group->id, 'name'=>'title', 'display_name'=>'SEO Title']);
        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('textarea'), 'group_id' => $group->id, 'name'=>'description', 'display_name'=>'SEO Description']);
    }
}
