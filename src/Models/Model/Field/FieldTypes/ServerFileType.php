<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class ServerFileType
{
    public static $name = 'string';

    public static $displayName = 'server_file';

    public static $needField = true;

    public static $size = ['base' => 255, 'extra' => null];

    public static $defaultSettings = [
        'control' => [
            'value' => 'explore',
            'variants' => [
                'explore',
                'readonly',
            ],
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