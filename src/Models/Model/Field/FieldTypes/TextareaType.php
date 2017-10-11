<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class TextareaType
{
    public static $name = 'string';

    public static $displayName = 'textarea';

    public static $needField = true;

    public static $size = ['base' => 512, 'extra' => null];

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
        return '';
    }

    public static function beforeDeleting(Field $field, Node $node)
    {
        return;
    }
}