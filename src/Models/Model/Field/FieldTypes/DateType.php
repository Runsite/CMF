<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;
use Carbon\Carbon;

class DateType
{
    public static $name = 'date';

    public static $displayName = 'date';

    public static $needField = true;

    public static $size = ['base' => null, 'extra' => null];

    public static $defaultSettings = [
        'control' => [
            'value' => 'calendar',
            'variants' => [
                'calendar',
                'readonly',
            ],
        ],

        'custom_validation_rules' => [
            'value' => 'date',
            'variants' => null,
        ],
    ];

    public static function defaultValue(): string
    {
        return Carbon::now()->format('Y-m-d');
    }

    public static function beforeDeleting(Field $field, Node $node): void
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