<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;
use Carbon\Carbon;

class DateTimeType
{
    public static $name = 'datetime';

    public static $displayName = 'datetime';

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

        'faker_provider' => [
            'value' => null,
            'variants' => [
                null,
                'DateTime',
            ],
        ],

        'faker_type' => [
            'value' => null,
            'variants' => null,
        ],
    ];

    public static function defaultValue(): string
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    public static function beforeDeleting(Field $field, Node $node): void
    {
        return;
    }

    public static function beforeCreating($value, Node $node)
    {
        return $value;
    }
}