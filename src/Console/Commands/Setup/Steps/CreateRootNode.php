<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;

class CreateRootNode
{
    public $message = 'Creating root node';

    public function handle($options)
    {
        $model = Model::where('name', 'root')->first();
        $node = Node::create(['parent_id'=>null, 'model_id'=>$model->id], 'Home page');

        // saving node
        foreach(Language::get() as $language)
        {
            $node->{$language->locale}->is_active = true;
            $node->{$language->locale}->name = 'Home page';
            $node->{$language->locale}->save();
        }
    }
}
