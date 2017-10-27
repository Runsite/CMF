<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class CkeditorType
{
    public static $name = 'text';

    public static $displayName = 'ckeditor';

    public static $needField = true;

    public static $size = ['base' => 65535, 'extra' => null];

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

        'faker_provider' => [
            'value' => null,
            'variants' => [
                null,
                'Text',
            ],
        ],

        'faker_type' => [
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

    public static function beforeCreating($value, Node $node)
    {
        return $value;
    }
}