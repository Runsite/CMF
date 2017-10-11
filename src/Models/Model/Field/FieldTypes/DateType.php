<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
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
            'value' => null,
            'variants' => null,
        ],
    ];

    public static function defaultValue()
    {
        return Carbon::now()->format('Y-m-d');
    }

    public static function beforeDeleting(Field $field, Node $node)
    {
        return;
    }
}