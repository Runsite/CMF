<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;

class CodeType
{
    public static $name = 'string';

    public static $displayName = 'code';

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

        'theme' => [
            'value' => 'ambiance',
            'variants' => [
                'ambiance',
                'chaos',
                'chrome',
                'clouds_midnight',
                'clouds',
                'cobalt',
                'crimson_editor',
                'dawn',
                'dracula',
                'dreamweaver',
                'eclipse',
                'github',
                'gob',
                'gruvbox',
                'idle_fingers',
                'iplastic',
                'katzenmilch',
                'kr_theme',
                'kuroir',
                'merbivore_soft',
                'merbivore',
                'mono_industrial',
                'monokai',
                'pastel_on_dark',
                'solarized_dark',
                'solarized_light',
                'sqlserver',
                'terminal',
                'tomorrow_night_blue',
                'tomorrow_night_bright',
                'tomorrow_night_eighties',
                'tomorrow_night',
                'tomorrow',
                'twilight',
                'vibrant_ink',
                'xcode',
            ],
        ],

        'language' => [
            'value' => 'javascript',
            'variants' => [
                'javascript',
                'html',
            ],
        ],
    ];

    public static function defaultValue()
    {
        return null;
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
