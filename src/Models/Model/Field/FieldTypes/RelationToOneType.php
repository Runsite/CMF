<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;

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
        return $value;
    }

    public static function beforeUpdating($value, $old_value, Node $node, Field $field, Language $language)
    {
        return $value;
    }
}
