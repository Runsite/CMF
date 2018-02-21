<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Model\Field\FieldGroup;

class CreatePageModel
{
    public $message = 'Creating page model';

    public function handle($options)
    {
        $model = Model::create(['name' => 'page', 'display_name' => 'Page', 'display_name_plural' => 'Pages'], true, true);
        $group = FieldGroup::create(['name' => 'SEO', 'model_id' => $model->id]);

        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('string'), 'name'=>'name', 'display_name'=>'Name']);
        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('string'), 'group_id' => $group->id, 'name'=>'title', 'display_name'=>'SEO Title']);
        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('textarea'), 'group_id' => $group->id, 'name'=>'description', 'display_name'=>'SEO Description']);
        Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('ckeditor'), 'name'=>'content', 'display_name'=>'Content']);
    }
}
