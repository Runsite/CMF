<?php

namespace Runsite\CMF;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use View;
use Validator;
use Runsite\CMF\Http\ViewComposers\AppComposer;
use Runsite\CMF\Http\ViewComposers\TreeComposer;
use Runsite\CMF\Http\Middlewares\Access\Application as ApplicationAccessMiddleware;

class RunsiteCMFServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/public.php');
        if(config('runsite.cmf.dynamic_routes.enabled'))
        {
            $this->loadRoutesFrom(__DIR__ . '/routes/public.php');
        }
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'runsite');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/resources/langs', 'runsite');

        $this->publishes([
            __DIR__.'/../publish/config/cmf.php' => config_path('runsite/cmf.php'),
            __DIR__.'/../publish/resources/views/layouts/app.blade.php' => base_path('resources/views/layouts/app.blade.php'),
            __DIR__.'/../publish/resources/views/roots/view.blade.php' => base_path('resources/views/roots/view.blade.php'),
        ]);


        View::composer('runsite::*', AppComposer::class);
        View::composer('runsite::layouts.app', TreeComposer::class);

        
        $this->app['router']->aliasMiddleware('application-access', ApplicationAccessMiddleware::class);
        // $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__ . '/database/factories');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '\Helpers\M.php';

        $this->commands([
            Console\Commands\Setup\Setup::class,
        ]);
    }
}
