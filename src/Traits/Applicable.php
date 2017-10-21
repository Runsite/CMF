<?php 

namespace Runsite\CMF\Traits;

use Runsite\CMF\Models\Application;

trait Applicable {

    protected $application;

    public function __construct()
    {
        $this->application = Application::where('name', $this->application_name)->first();

        if(method_exists($this, '__boot'))
        {
            $this->__boot();
        }
    }
}