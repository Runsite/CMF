<?php 

use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Model\Model;
use Runsite\CMF\Models\User\User;
use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Models\User\Access;
use Runsite\CMF\Models\User\UserGroup;
use Runsite\CMF\Models\Notification\Notification;

use Runsite\CMF\Tests\TestCase;

use Runsite\CMF\Models\Model\Dependency as ModelDependency;
use Runsite\CMF\Models\Model\Field\Field;

use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Node\Dependency as NodeDependency;

class ComplexTest extends TestCase
{
    public function testComplex()
    {
        // $this->disableExceptionHandling();
        // creating languages
        Language::create(['locale'=>'en', 'display_name'=>'English', 'is_active'=>true]);
        $this->assertDatabaseHas('rs_languages', ['locale' => 'en']);

        Language::create(['locale'=>'uk', 'display_name'=>'Українська', 'is_active'=>true]);
        $this->assertDatabaseHas('rs_languages', ['locale' => 'uk']);

        // creating model
        $model = Model::create(['name' => 'test_model', 'display_name' => 'Test Model', 'display_name_plural' => 'Test Models']);
        $this->assertDatabaseHas('rs_models', ['name' => 'test_model']);
        $this->assertDatabaseHas('rs_fields', ['model_id' => $model->id]);
        $this->assertDatabaseHas('rs_model_settings', ['model_id' => $model->id]);
        $this->assertDatabaseHas('rs_model_methods', ['model_id' => $model->id, 'get'=>$model->controllerName().'@show']);

        // testing creating new fields
        $field = Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('string'), 'name'=>'name', 'display_name'=>'Name']);
        $this->assertDatabaseHas('rs_fields', ['model_id' => $model->id, 'name' => 'is_active', 'position' => 1]);
        $this->assertDatabaseHas('rs_fields', ['model_id' => $model->id, 'name' => 'name', 'position' => 2]);
        $this->assertDatabaseHas('rs_field_settings', ['field_id' => $field->id, 'parameter' => 'control', 'value'=>'default']);

        // test after removing field 
        $field2 = Field::create(['model_id'=>$model->id, 'type_id'=>Field::getTypeId('image'), 'name'=>'image', 'display_name'=>'Test field image']);
        $this->assertDatabaseHas('rs_fields', ['model_id' => $model->id, 'name' => 'image', 'position' => 3]);

        // testing model dependencies
        $modelDependency = ModelDependency::create(['model_id' => $model->id, 'depended_model_id' => $model->id]);
        $this->assertDatabaseHas('rs_model_dependencies', ['model_id' => $model->id, 'depended_model_id' => $model->id]);

        // creating node
        $node = Node::create(['parent_id'=>null, 'model_id'=>$model->id], 'Test node');
        $this->assertDatabaseHas('rs_nodes', ['model_id' => $model->id]);
        $this->assertDatabaseHas('rs_paths', ['node_id' => $node->baseNode->id, 'name'=>'/']);
        $this->assertDatabaseHas('rs_node_methods', ['node_id' => $node->baseNode->id]);
        $this->assertDatabaseHas($model->tableName(), ['is_active' => false]);
        $this->assertDatabaseHas('rs_node_analytics', ['parent_node_id' => $node->baseNode->parent_id, 'model_id'=>$node->baseNode->model->id, 'type'=>1]);


        // saving node
        foreach(Language::get() as $language)
        {
            $node->{$language->locale}->is_active = true;
            $node->{$language->locale}->image = 'test-image-path.jpg';
            $node->{$language->locale}->save();
        }

        $this->assertDatabaseHas($model->tableName(), ['is_active' => true]);
        $this->assertDatabaseHas('rs_node_analytics', ['parent_node_id' => $node->baseNode->parent_id, 'model_id'=>$node->baseNode->model->id, 'type'=>2]);

        // node dependencies
        $nodeDependency = NodeDependency::create(['node_id'=>$node->baseNode->id, 'depended_model_id' => $model->id]);
        $this->assertDatabaseHas('rs_node_dependencies', ['node_id' => $node->baseNode->id, 'depended_model_id' => $model->id]);

        // creating dependent node
        $dependentNode = Node::create(['parent_id'=>$node->baseNode->id, 'model_id'=>$model->id], 'Test node');
        $this->assertDatabaseHas('rs_nodes', ['model_id' => $model->id]);
        $this->assertDatabaseHas('rs_paths', ['node_id' => $dependentNode->baseNode->id, 'name'=>'/test-node']);
        $this->assertDatabaseHas('rs_node_methods', ['node_id' => $dependentNode->baseNode->id]);

        // renaming node - must be created new path
        foreach($dependentNode->baseNode->dynamic()->get() as $nodeData)
        {
            $nodeData->name = 'New name';
            $nodeData->save();
            $nodeData->name = 'New new name';
            $nodeData->save();
        }
        $this->assertDatabaseHas('rs_paths', ['node_id' => $dependentNode->baseNode->id, 'name'=>'/test-node']);
        $this->assertDatabaseHas('rs_paths', ['node_id' => $dependentNode->baseNode->id, 'name'=>'/new-name']);
        $this->assertDatabaseHas('rs_paths', ['node_id' => $dependentNode->baseNode->id, 'name'=>'/new-new-name']);

        // creating node with one name
        $dependentNode2 = Node::create(['parent_id'=>$node->baseNode->id, 'model_id'=>$model->id], 'Test node');
        $this->assertDatabaseHas('rs_nodes', ['id' => $dependentNode2->baseNode->id, 'position' => 2]);
        $this->assertDatabaseHas('rs_paths', ['node_id' => $dependentNode2->baseNode->id, 'name'=>'/test-node2']);
        $this->assertDatabaseHas('rs_node_methods', ['node_id' => $dependentNode2->baseNode->id]);


        // creating users
        $user = User::create(['name'=>'tester', 'email'=>'tester@example.com', 'password'=>bcrypt('secret')]);
        $this->assertDatabaseHas('rs_users', ['email' => 'tester@example.com']);

        // creating group
        $group = Group::create(['name'=>'test group', 'description'=>'Test group description']);
        $this->assertDatabaseHas('rs_groups', ['name' => 'test group']);
        $this->assertDatabaseHas('rs_group_access', ['group_id' => $group->id, 'access'=>0]);

        // assigning group to user
        UserGroup::create(['user_id'=>$user->id, 'group_id'=>$group->id]);
        $this->assertDatabaseHas('rs_user_group', ['user_id' => $user->id, 'group_id' => $group->id]);

        // testing no access to node
        $this->assertFalse($user->canRead($node->baseNode->id));
        
        // testing assign access to group
        $group->assignAccess(1, $node->baseNode->id, true);
        $this->assertTrue($user->canRead($node->baseNode->id));
        $this->assertFalse($user->canEdit($node->baseNode->id));
        $this->assertFalse($user->canDelete($node->baseNode->id));
        $this->assertTrue($user->canRead($dependentNode->baseNode->id));
        $this->assertFalse($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        $group->assignAccess(2, $node->baseNode->id, true);
        $this->assertTrue($user->canRead($node->baseNode->id));
        $this->assertTrue($user->canEdit($node->baseNode->id));
        $this->assertFalse($user->canDelete($node->baseNode->id));
        $this->assertTrue($user->canRead($dependentNode->baseNode->id));
        $this->assertTrue($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        $group->assignAccess(3, $node->baseNode->id, true);
        $this->assertTrue($user->canRead($node->baseNode->id));
        $this->assertTrue($user->canEdit($node->baseNode->id));
        $this->assertTrue($user->canDelete($node->baseNode->id));
        $this->assertTrue($user->canRead($dependentNode->baseNode->id));
        $this->assertTrue($user->canEdit($dependentNode->baseNode->id));
        $this->assertTrue($user->canDelete($dependentNode->baseNode->id));

        $group->assignAccess(0, $node->baseNode->id, true);
        $this->assertFalse($user->canRead($node->baseNode->id));
        $this->assertFalse($user->canEdit($node->baseNode->id));
        $this->assertFalse($user->canDelete($node->baseNode->id));
        $this->assertFalse($user->canRead($dependentNode->baseNode->id));
        $this->assertFalse($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        // testing assign access to group without recursion
        $group->assignAccess(1, $node->baseNode->id, false);
        $this->assertTrue($user->canRead($node->baseNode->id));
        $this->assertFalse($user->canEdit($node->baseNode->id));
        $this->assertFalse($user->canDelete($node->baseNode->id));
        $this->assertFalse($user->canRead($dependentNode->baseNode->id));
        $this->assertFalse($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        $group->assignAccess(2, $node->baseNode->id, false);
        $this->assertTrue($user->canRead($node->baseNode->id));
        $this->assertTrue($user->canEdit($node->baseNode->id));
        $this->assertFalse($user->canDelete($node->baseNode->id));
        $this->assertFalse($user->canRead($dependentNode->baseNode->id));
        $this->assertFalse($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        $group->assignAccess(3, $node->baseNode->id, false);
        $this->assertTrue($user->canRead($node->baseNode->id));
        $this->assertTrue($user->canEdit($node->baseNode->id));
        $this->assertTrue($user->canDelete($node->baseNode->id));
        $this->assertFalse($user->canRead($dependentNode->baseNode->id));
        $this->assertFalse($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        $group->assignAccess(0, $node->baseNode->id, false);
        $this->assertFalse($user->canRead($node->baseNode->id));
        $this->assertFalse($user->canEdit($node->baseNode->id));
        $this->assertFalse($user->canDelete($node->baseNode->id));
        $this->assertFalse($user->canRead($dependentNode->baseNode->id));
        $this->assertFalse($user->canEdit($dependentNode->baseNode->id));
        $this->assertFalse($user->canDelete($dependentNode->baseNode->id));

        // testing fields
        foreach($model->fields as $fieldItem)
        {
            $fieldItem->update(['type_id'=>Field::getTypeId('decimal')]);
            $this->assertDatabaseHas('rs_fields', ['model_id' => $model->id, 'type_id' => Field::getTypeId('decimal')]);
            $this->assertDatabaseHas('rs_field_settings', ['field_id' => $fieldItem->id, 'parameter' => 'control', 'value'=>'default']);
            $this->assertDatabaseHas('rs_field_settings', ['field_id' => $fieldItem->id, 'parameter' => 'size', 'value'=>'5,2']);
        }

        $field2->moveUp();
        $this->assertDatabaseHas('rs_fields', ['id' => $field2->id, 'position' => 2]);
        $this->assertDatabaseHas('rs_fields', ['id' => $field->id, 'position' => 3]);

        $field2->moveDown();
        $this->assertDatabaseHas('rs_fields', ['id' => $field2->id, 'position' => 3]);
        $this->assertDatabaseHas('rs_fields', ['id' => $field->id, 'position' => 2]);

        $field2->moveUp();
        $field2->moveUp();
        $this->assertDatabaseHas('rs_fields', ['id' => $field2->id, 'position' => 1]);
        $this->assertDatabaseHas('rs_fields', ['id' => $field->id, 'position' => 3]);

        $field2->moveDown();
        $this->assertDatabaseHas('rs_fields', ['id' => $field2->id, 'position' => 2]);
        $this->assertDatabaseHas('rs_fields', ['id' => $field->id, 'position' => 3]);

        // deleting field
        $field->delete();
        $this->assertDatabaseMissing('rs_fields', ['model_id' => $model->id, 'name' => 'test_field']);
        $this->assertDatabaseHas('rs_fields', ['model_id' => $model->id, 'name' => 'image', 'position' => 2]);

        // test getting model
        $m = M($model->name, false)->first();
        // dd($m);

        // moving nodes
        $dependentNode2->baseNode->moveUp();
        $this->assertDatabaseHas('rs_nodes', ['id' => $dependentNode2->baseNode->id, 'position' => 1]);
        $this->assertDatabaseHas('rs_nodes', ['id' => $dependentNode->baseNode->id, 'position' => 2]);

        $dependentNode2->baseNode->moveDown();
        $this->assertDatabaseHas('rs_nodes', ['id' => $dependentNode2->baseNode->id, 'position' => 2]);
        $this->assertDatabaseHas('rs_nodes', ['id' => $dependentNode->baseNode->id, 'position' => 1]);

        // notifications
        Notification::create([
            'user_id' => $user->id,
            'node_id' => null,
            'message' => 'Test notification',
        ]);
        $this->assertDatabaseHas('rs_notifications', ['user_id' => $user->id, 'message' => 'Test notification']);


        // deleting node
        $dependentNodeId = $dependentNode->baseNode->id;
        $dependentNode->baseNode->delete();
        $this->assertDatabaseMissing('rs_nodes', ['id' => $dependentNodeId]);
        $this->assertDatabaseMissing('rs_paths', ['node_id' => $dependentNodeId]);
        $this->assertDatabaseMissing('rs_node_methods', ['node_id' => $dependentNodeId]);
        $this->assertDatabaseMissing($model->tableName(), ['node_id' => $dependentNodeId]);
        $this->assertDatabaseHas('rs_nodes', ['id' => $dependentNode2->baseNode->id, 'position' => 1]);

        // deleting group
        $group->delete();
        $this->assertDatabaseMissing('rs_groups', ['name' => 'test group']);
        $this->assertDatabaseMissing('rs_group_access', ['group_id' => $group->id]);

        // deleting user
        $user->delete();
        $this->assertDatabaseMissing('rs_users', ['email' => 'tester@example.com']);

        // renaming model 
        $model->update([
            'name' => 'new_article',
        ]);

        // deleting model
        $model->delete();
        $this->assertDatabaseMissing('rs_models', ['name' => 'test_model']);

        // deleting languages 
        foreach(Language::get() as $language)
        {
            $language->delete();
            // TODO: видалення полей (файли і тд)
        }
        $this->assertDatabaseMissing('rs_languages', ['locale' => 'en']);
        $this->assertDatabaseMissing('rs_languages', ['locale' => 'uk']);


        $user = User::create(['email' => 'goszowski@gmail.com']);

        // Перевірка чи юзер створився. Якщо нема то тест провалиться
        $this->assertDatabaseHas('users', ['email' => 'goszowski@gmail.com']);

        // Перевірка чи юзер створився вчора (просто від балди)
        $this->assertTrue($user->created_at->format('Y-m-d') == Carbon::now()->subday(1)->format('Y-m-d'));

        // Перевірка чи юзер маєє якусь властивість
        $this->assertTrue(isset($user->lol_property));

        // Перевірка ще якоїсь хуйні (тест провалиться)
        $this->assertFalse(true);

        // Ще в доках є купа методів. Відвідування урлів, заповнення форм, 
        // відправка форм, очікування результату, статусів і тд.

        $user->delete();

        // Перевірка чи юзер видалився
        $this->assertDatabaseMissing('users', ['email' => 'goszowski@gmail.com']);
    }
}