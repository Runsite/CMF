<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Model\Model;

class RelationToOneType
{
    public static $name = 'integer';

    public static $displayName = 'relation_to_one';

    public static $needField = true;

    public static $size = ['base' => null, 'extra' => null];

    public static $foreign = true;

    public static $defaultSettings = [
        'control' => [
            'value' => 'select_with_search',
            'variants' => [
                'select',
                'select_with_search',
                'readonly',
            ],
        ],

        'custom_validation_rules' => [
            'value' => null,
            'variants' => null,
        ],

        'related_model_name' => [
            'value' => '',
            'variants' => null,
        ],
        
        'related_parent_node_id' => [
            'value' => '',
            'variants' => null,
        ],
    ];

    public static function defaultValue()
    {
        return null;
    }

    public static function beforeDeleting($old_value, Node $node, Field $field, Language $language)
    {
        return;
    }

    public static function beforeCreating($value, Node $node, Field $field, Language $language)
    {
        if($value and !is_numeric($value) and str_is('@#-create-*', $value) and $field->findSettings('related_parent_node_id'))
        {
            $value = self::createNewNode($value, $field);
        }

        return $value;
    }

    public static function beforeUpdating($value, $old_value, Node $node, Field $field, Language $language)
    {
        if($value and !is_numeric($value) and str_is('@#-create-*', $value) and $field->findSettings('related_parent_node_id'))
        {
            $value = self::createNewNode($value, $field);
        }

        return $value;
    }

    protected static function createNewNode($value, Field $field)
    {
        $value = str_replace('@#-create-', '', $value);
        $relatedModelName = $field->findSettings('related_model_name');
        $relatedModel = Model::where('name', $relatedModelName->value)->first();

        $parent_id = $field->findSettings('related_parent_node_id')->value;

        $nodeExists = M($relatedModelName->value)->where('rs_nodes.parent_id', $parent_id)->where('name', $value)->first();

        if($nodeExists)
        {
            return $nodeExists->node->id;
        }

        // Creating node
        $node = Node::create([
            'parent_id' => $parent_id,
            'model_id' => $relatedModel->id,
        ], $value);

        foreach(Language::get() as $language)
        {
            $node->{$language->locale}->is_active = true;
            $node->{$language->locale}->name = $value;
            $node->{$language->locale}->save();
        }

        return $node->baseNode->id;
    }
}
