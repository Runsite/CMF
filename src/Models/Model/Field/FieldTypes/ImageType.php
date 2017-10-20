<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Node\Node;

class ImageType
{
    public static $name = 'string';

    public static $displayName = 'image';

    public static $needField = true;

    public static $size = ['base' => 255, 'extra' => null];

    public static $defaultSettings = [
        'control' => [
            'value' => 'default',
            'variants' => [
                'default',
                'readonly',
            ],
        ],

        'image_size' => [
            'value' => '1700/600/150',
            'variants' => null,
        ],

        'custom_validation_rules' => [
            'value' => null,
            'variants' => null,
        ],
    ];

    public static function defaultValue(): string
    {
        return '';
    }

    public static function beforeDeleting(Field $field, Node $node): void
    {
        foreach($node->dynamic() as $languageVersion)
        {
            $image_path = public_path('uploads/fieldtypes/images/'.$languageVersion->{$field->name});
            if(file_exists($image_path))
            {
                unlink($image_path);
            }
        }
        
    }
}