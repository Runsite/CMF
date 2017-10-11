<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class RelationToOneType
{
    public static $name = 'integer';

    public static $displayName = 'relation_to_one';

    public static $needField = true;

    public static $size = ['base' => 11, 'extra' => null];

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

    public static function beforeDeleting(Field $field, Node $node)
    {
        return;
    }
}