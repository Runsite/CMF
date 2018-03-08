<?php 

namespace Runsite\CMF\Models\Model\Field\FieldTypes;

use Runsite\CMF\Models\Model\Field\Field;
use Runsite\CMF\Models\Dynamic\Language;
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
				'upload',
				'readonly',
			],
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
			$path = storage_path('app/public/files/nodes/'.$node->id.'/'.$field->name.'/'.$language->id.'/'.$old_value->value);
			if(file_exists($path))
			{
				unlink($path);
			}
		}

		// Deleting folders
		$foldersPath = storage_path('app/public/files/nodes/' . $node->id);
		self::rrmdir($foldersPath);
	}

	public static function beforeCreating($value, Node $node, Field $field, Language $language)
	{
		$name = null;

		if(is_string($value))
		{
			return $value;
		}
		
		if($value)
		{
			$path = 'files/nodes/'.$node->id.'/'.$field->name.'/'.$language->id;
			$name = self::generateFilename($value);
			$value->storeAs($path, $name, 'public');
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
			$path = 'files/nodes/'.$node->id.'/'.$field->name.'/'.$language->id;
			$name = self::generateFilename($value);
			$value->storeAs($path, $name, 'public');
		}

		if($old_value->value)
		{
			$path = storage_path('app/public/files/nodes/'.$node->id.'/'.$field->name.'/'.$language->id.'/'.$old_value->value);
			if(file_exists($path))
			{
				unlink($path);
			}
		}

		return $name;
	}

	// Generating unique filename
	protected static function generateFilename($value)
	{
		return str_random(5).time().str_random(5).'.'.$value->extension();
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
}
