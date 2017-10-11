<?php 

use Runsite\CMF\Tests\TestCase;
use Runsite\CMF\Models\Model\Field\Field;
use Illuminate\Support\Facades\View;

class FieldControlsTest extends TestCase
{
    public function testControls()
    {
        $field = new Field;

        $view_path = 'runsite::models.fields.field_types.base';
        if(!View::exists($view_path))
        {
            throw new Exception("View not found: " . $view_path);
            $this->assertTrue(false);
        }
        else 
        {
            $this->assertTrue(true);
        }

        foreach($field->types as $type)
        {
            foreach($type::$defaultSettings as $parameter=>$setting)
            {
                if($parameter == 'control')
                {
                    $view_path = 'runsite::models.fields.field_types.'.$type::$displayName.'._view';
                    if(!View::exists($view_path))
                    {
                        throw new Exception("View not found: " . $view_path);
                        $this->assertTrue(false);
                    }
                    else 
                    {
                        $this->assertTrue(true);
                    }

                    foreach($setting['variants'] as $variant)
                    {
                        $view_path = 'runsite::models.fields.field_types.'.$type::$displayName.'.'.$variant;
                        if(!View::exists($view_path))
                        {
                            throw new Exception("View not found: " . $view_path);
                            $this->assertTrue(false);
                        }
                        else 
                        {
                            $this->assertTrue(true);
                        }
                    }
                }
            }
        }
    }
}