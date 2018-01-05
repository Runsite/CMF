<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;
use Runsite\CMF\Models\Node\Relation;

class RelationToManyType
{    
    public static $displayName = 'relation_to_many';

    public static $needField = false;

    public static $defaultSettings = [
        'control' => [
            'value' => 'checkbox',
            'variants' => [
                'checkbox',
                'checkbox_columns',
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
        Relation::where('language_id', $language->id)->where('node_id', $node->id)->where('field_id', $field->id)->delete();

        if(is_array($value))
        {
            foreach($value as $node_id)
            {
                if($node_id)
                {
                    Relation::create([
                        'language_id' => $language->id,
                        'node_id' => $node->id,
                        'field_id' => $field->id,
                        'related_node_id' => $node_id,
                    ]);
                }
            }
        }
        

        return null;
    }

    public static function beforeUpdating($value, $old_value, Node $node, Field $field, Language $language)
    {
        Relation::where('language_id', $language->id)->where('node_id', $node->id)->where('field_id', $field->id)->delete();

        if(is_array($value))
        {
            foreach($value as $node_id)
            {
                if($node_id)
                {
                    Relation::create([
                        'language_id' => $language->id,
                        'node_id' => $node->id,
                        'field_id' => $field->id,
                        'related_node_id' => $node_id,
                    ]);
                }
                
            }
        }

        return null;
    }
}
