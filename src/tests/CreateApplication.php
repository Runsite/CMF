<?php

namespace Runsite\CMF\Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    protected $baseUrl = 'http://runsite.dev';
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // $app->instance(Handler::class, new class extends Handler {
        //     public function __construct() {}
        //     public function report(Exception $exception) {}
        //     public function render($request, Exception $e)
        //     {
        //         throw $e;
        //     }
        // });

        return $app;
    }

    public function disableExceptionHandling()
    {
        // app()->instance(Handler::class, new class extends Handler {
        //     public function __construct() {}
        //     public function report(Exception $e)
        //     {
        //         // no-op
        //     }
        //     public function render($request, Exception $e)
        //     {
        //         throw $e;
        //     }
        // });
    }
}
