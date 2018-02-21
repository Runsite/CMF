<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Model\Field\FieldGroup;

class CreateAdminSectionModel
{
    public $message = 'Creating admin section model';

    public function handle($options)
    {
        $model = Model::create(['name' => 'admin_section', 'display_name' => 'Admin section', 'display_name_plural' => 'Admin sections']);

        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('string'), 'name'=>'name', 'display_name'=>'Name']);
    }
}
