<?php

namespace Runsite\CMF\Console\Commands\Setup\Steps;

class ClearApp {

    public $message = 'Cleaning app';

    public function handle()
    {
        if(file_exists(app_path('User.php')))
        {
            unlink(app_path('User.php'));
        }

        // Creating model file 
        if(! is_dir(app_path('Models')))
        {
            mkdir(app_path('Models'));
        }

        $this->unlinkPath('database/migrations');
        $this->unlinkPath('app/Http/Controllers');
        $this->unlinkPath('app/Mail');
        $this->unlinkPath('app/Models');
        $this->unlinkPath('resources/views');

        return;
    }

    protected function unlinkPath($path)
    {
        $files = scandir($path);

        foreach($files as $file)
        {
            if($file != '.' and $file != '..')
            {
                if(is_dir($path . '/' . $file))
                {
                    $this->unlinkPath($path . '/' . $file);
                    rmdir($path . '/' . $file);
                }
                else 
                {
                    unlink($path . '/' . $file);
                }
            }
        }
        
    }
}
