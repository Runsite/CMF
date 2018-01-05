<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
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

    public static function defaultValue(): bool
    {
        return false;
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
