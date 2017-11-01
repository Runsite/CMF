<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

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

    // Generating unique filename
    protected static function generateFilename($value)
    {
        return str_random(10).time().str_random(10).'.'.$value->extension();
    }

    public static function beforeCreating($value, Node $node, Field $field, Language $language)
    {
        $name = null;
        if($value)
        {
            $base_path = 'images/'.$node->id.'/'.$field->name.'/'.$language->id;
            $original_path = $base_path.'/original';
            $name = self::generateFilename($value);
            $value->storeAs($original_path, $name, 'public');

            $sizes = explode('/', $field->findSettings('image_size')->value);

            foreach($sizes as $k=>$size)
            {
                // Folder name
                $size_name = $size;

                if(!$k)
                    $size_name = 'max'; // If this is maximum size, it will be named "max"
                elseif(++$k == count($sizes))
                    $size_name = 'min'; // If this is minimum size, it will be named "min"

                // Path to current size folder
                $size_path = $base_path . '/' .$size_name;
                mkdir(storage_path('app/public/' . $size_path));

                $image = Image::make(storage_path('app/public/' . $original_path) . '/' . $name);

                // If original width is larger than current size
                if($image->width() > $size)
                {
                    $image->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $image->save(storage_path('app/public/' . $size_path . '/' . $name));
                
            }
        }
        
        return $name;
    }
}