<?php

namespace Runsite\CMF\Console\Commands\Setup\Steps;

class ClearApp {

    public $message = 'Cleaning app';

    public function handle($options)
    {
        if(file_exists(app_path('User.php')))
        {
            unlink(app_path('User.php'));
        }

        if(file_exists(app_path('Http/Middleware/RedirectIfAuthenticated.php')))
        {
            unlink(app_path('Http/Middleware/RedirectIfAuthenticated.php'));
        }

        // Creating model folder 
        if(! is_dir(app_path('Models')))
        {
            mkdir(app_path('Models'));
        }

        if(! is_dir(storage_path('app/public/images')))
        {
            mkdir(storage_path('app/public/images'));
        }

        $this->unlinkPath('database/migrations');
        $this->unlinkPath('app/Http/Controllers');
        $this->unlinkPath('app/Mail');
        $this->unlinkPath('app/Models');
        $this->unlinkPath('resources/views');
        $this->unlinkPath('config/runsite');
        $this->unlinkPath('storage/app/public/images');

        // Clearning routes
        file_put_contents(base_path('routes/web.php'), '');

        return;
    }

    protected function unlinkPath($path)
    {
        if(is_dir($path))
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
}
