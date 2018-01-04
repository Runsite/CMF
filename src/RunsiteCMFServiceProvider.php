<?php

namespace Runsite\CMF;

use View;
use Validator;

use Illuminate\Support\{
    ServiceProvider,
    Facades\Config
};

use Runsite\CMF\Http\{
    ViewComposers\AppComposer,
    ViewComposers\TreeComposer,
    Middlewares\Access\Application as ApplicationAccessMiddleware
};

class RunsiteCMFServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        if(config('runsite.cmf.dynamic_routes.enabled'))
        {
            $this->loadRoutesFrom(__DIR__ . '/routes/public.php');
        }
        
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'runsite');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/resources/langs', 'runsite');

        $this->publishes([
            __DIR__.'/../publish/config/cmf.php' => config_path('runsite/cmf.php'),
            __DIR__.'/../publish/config/auth.php' => config_path('auth.php'),
            __DIR__.'/../publish/resources/views/layouts/app.blade.php' => base_path('resources/views/layouts/app.blade.php'),
            __DIR__.'/../publish/resources/views/layouts/resources.blade.php' => base_path('resources/views/layouts/resources.blade.php'),
            __DIR__.'/../publish/resources/views/roots/view.blade.php' => base_path('resources/views/roots/view.blade.php'),
            __DIR__.'/../publish/resources/views/errors/404.blade.php' => base_path('resources/views/errors/404.blade.php'),
            __DIR__.'/../publish/resources/views/errors/500.blade.php' => base_path('resources/views/errors/500.blade.php'),
            __DIR__.'/../publish/app/Http/Kernel.php' => app_path('Http/Kernel.php'),
            __DIR__.'/../publish/app/Console/Kernel.php' => app_path('Console/Kernel.php'),
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
        require_once __DIR__ . '/Helpers/M.php';
        require_once __DIR__ . '/Helpers/lPath.php';

        $this->commands([
            Console\Commands\Setup\Setup::class,
        ]);
    }
}
