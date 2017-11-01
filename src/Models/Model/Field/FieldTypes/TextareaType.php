<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
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

        'faker_provider' => [
            'value' => null,
            'variants' => [
                null,
                'Base',
                'Person',
                'Address',
                'PhoneNumber',
                'Company',
                'Text',
                'Internet',
                'UserAgent',
                'Payment',
                'Color',
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

    public static function beforeCreating($value, Node $node, Field $field, Language $language)
    {
        return $value;
    }
}