<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;

class CreateSettingsNode
{
    public $message = 'Creating settings node';

    public function handle($options)
    {
        $model = Model::where('name', 'admin_section')->first();
        $node = Node::create(['parent_id'=>1, 'model_id'=>$model->id], 'Settings');

        // saving node
        foreach(Language::get() as $language)
        {
            $node->{$language->locale}->is_active = true;
            $node->{$language->locale}->name = 'Settings';
            $node->{$language->locale}->save();
        }
    }
}
