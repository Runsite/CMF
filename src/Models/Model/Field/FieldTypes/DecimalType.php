<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class DecimalType
{
    public static $name = 'decimal';

    public static $displayName = 'decimal';

    public static $needField = true;

    public static $size = ['base' => 5, 'extra' => 2];

    public static $defaultSettings = [
        'control' => [
            'value' => 'default',
            'variants' => [
                'default',
                'readonly',
            ],
        ],

        'custom_validation_rules' => [
            'value' => null,
            'variants' => null,
        ],
    ];

    public static function defaultValue()
    {
        return 0;
    }

    public static function beforeDeleting(Field $field, Node $node)
    {
        return;
    }
}