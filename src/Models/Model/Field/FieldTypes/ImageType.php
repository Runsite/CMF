<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
use Runsite\CMF\Models\Node\Node;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use ImageOptimizer;
use Abhimanyu003\Conversion\Facades\Conversion;

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

    public static function defaultValue()
    {
        return null;
    }

    public static function beforeDeleting($old_value, Node $node, Field $field, Language $language)
    {
        if($old_value and $old_value->value)
        {
            $base_path = 'images/'.$node->id.'/'.$field->name.'/'.$language->id;
            $original_path = $base_path.'/original';
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

                $old_file_path = storage_path('app/public/' . $size_path . '/' . $old_value->value);
                if(file_exists($old_file_path))
                {
                    unlink($old_file_path);
                }
            }

            $old_original_file_path = storage_path('app/public/' . $original_path . '/' . $old_value->value);
            if(file_exists($old_original_file_path))
            {
                unlink($old_original_file_path);
            }
        }

        // Deleting folders
        $foldersPath = storage_path('app/public/images/' . $node->id);
        self::rrmdir($foldersPath);
    }

    // Generating unique filename
    protected static function generateFilename($value)
    {
        return str_random(5).time().str_random(5).'.'.$value->extension();
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
                ImageOptimizer::optimize(storage_path('app/public/' . $size_path . '/' . $name));
                
            }
        }
        
        return $name;
    }

    public static function beforeUpdating($value, $old_value, Node $node, Field $field, Language $language)
    {
        $name = null;

        if(is_string($value))
        {
            return $value;
        }

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
                $dirPath = storage_path('app/public/' . $size_path);
                if(!is_dir($dirPath))
                {
                    mkdir($dirPath);
                }

                $image = Image::make(storage_path('app/public/' . $original_path) . '/' . $name);

                // If original width is larger than current size
                if($image->width() > $size)
                {
                    $image->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $image->save(storage_path('app/public/' . $size_path . '/' . $name));
                ImageOptimizer::optimize(storage_path('app/public/' . $size_path . '/' . $name));

                // Removing old image
                if($old_value->value)
                {
                    $old_file_path = storage_path('app/public/' . $size_path . '/' . $old_value->value);
                    if(file_exists($old_file_path))
                    {
                        unlink($old_file_path);
                    }
                }
            }

            // Removing old original image
            if($old_value->value)
            {
                $old_original_file_path = storage_path('app/public/' . $original_path . '/' . $old_value->value);
                if(file_exists($old_original_file_path))
                {
                    unlink($old_original_file_path);
                }
            }
        }
        elseif($old_value->value)
        {
            $base_path = 'images/'.$node->id.'/'.$field->name.'/'.$language->id;
            $original_path = $base_path.'/original';
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

                $old_file_path = storage_path('app/public/' . $size_path . '/' . $old_value->value);
                if(file_exists($old_file_path))
                {
                    unlink($old_file_path);
                }
            }

            $old_original_file_path = storage_path('app/public/' . $original_path . '/' . $old_value->value);
            if(file_exists($old_original_file_path))
            {
                unlink($old_original_file_path);
            }
        }
        
        return $name;
    }

    private static function rrmdir($dir) { 
       if (is_dir($dir)) { 
         $objects = scandir($dir); 
         foreach ($objects as $object) { 
           if ($object != "." && $object != "..") { 
             if (is_dir($dir."/".$object))
               self::rrmdir($dir."/".$object);
             else
               unlink($dir."/".$object); 
           } 
         }
         rmdir($dir); 
       } 
    }

    private static function returnBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (float) $val;
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    public static function resolveMaxUploadSize(Field $field)
    {
        $maxSize = 0;
        $validation_rules = explode(',', $field->findSettings('custom_validation_rules')->value);

        foreach($validation_rules as $validation_rule)
        {
            $params = explode(':', $validation_rule);

            if(isset($params[0]) and isset($params[1]) and $params[0] == 'size')
            {
                $maxSize = Conversion::convert($params[1], 'kilobyte')->to('byte')->format(0,'','');
            }
        }

        if(! $maxSize)
        {
            $maxSize = self::returnBytes(ini_get("upload_max_filesize"));
        }


        return Conversion::convert($maxSize, 'byte')->to('kilobyte');
    }


}
