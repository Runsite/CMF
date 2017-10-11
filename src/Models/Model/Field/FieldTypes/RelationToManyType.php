<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

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

        'related_model_name' => [
            'value' => '',
            'variants' => null,
        ],
        
        'related_parent_node_id' => [
            'value' => '',
            'variants' => null,
        ],
    ];

    public static function beforeDeleting(Field $field, Node $node)
    {
        return;
    }
}