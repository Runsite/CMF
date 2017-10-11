<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class BooleanType
{
    public static $name = 'boolean';

    public static $displayName = 'boolean';

    public static $needField = true;

    public static $size = ['base' => null, 'extra' => null];

    public static $defaultSettings = [
        'control' => [
            'value' => 'switch',
            'variants' => [
                'switch',
                'checkbox',
                'radio',
                'readonly',
            ],
        ],

    ];

    public static function defaultValue()
    {
        return false;
    }

    public static function beforeDeleting(Field $field, Node $node)
    {
        return;
    }
}