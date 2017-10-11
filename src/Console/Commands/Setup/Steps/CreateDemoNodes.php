<?php 

namespace Runsite\CMF\Console\Commands\Setup\Steps;

use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;

class CreateDemoNodes
{
    public $message = 'Creating demo nodes';

    public function handle()
    {
        $model = Model::where('name', 'section')->first();
        
        for($i=0; $i<20; $i++)
        {
            $node = Node::create(['parent_id'=>1, 'model_id'=>$model->id], 'Demo section '.$i);

            // saving node
            foreach(Language::get() as $language)
            {
                $node->{$language->locale}->is_active = true;
                $node->{$language->locale}->name = 'Demo section '.$i;
                $node->{$language->locale}->save();
            }

            for($a=0; $a<3; $a++)
            {
                $node2 = Node::create(['parent_id'=>$node->baseNode->id, 'model_id'=>$model->id], 'Demo child '.$a);

                // saving node
                foreach(Language::get() as $language)
                {
                    $node2->{$language->locale}->is_active = true;
                    $node2->{$language->locale}->name = 'Demo child '.$a;
                    $node2->{$language->locale}->save();
                }

                for($b=0; $b<2; $b++)
                {
                    $node3 = Node::create(['parent_id'=>$node2->baseNode->id, 'model_id'=>$model->id], 'Demo second child '.$b);

                    // saving node
                    foreach(Language::get() as $language)
                    {
                        $node3->{$language->locale}->is_active = true;
                        $node3->{$language->locale}->name = 'Demo second child '.$b;
                        $node3->{$language->locale}->save();
                    }
                }
            }
        }
    }
}