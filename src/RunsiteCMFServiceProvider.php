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
    Middlewares\Access\Application as ApplicationAccessMiddleware,
    Middlewares\AbortIfLocked,
    Middlewares\CheckAdminIP
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
        config([
            'minify.config.css_build_path' => '/vendor/runsite/asset/builds-css/',
            'minify.config.css_url_path' => '/vendor/runsite/asset/builds-css/',
            'minify.config.js_build_path' => '/vendor/runsite/asset/builds-js/',
            'minify.config.js_url_path' => '/vendor/runsite/asset/builds-js/',
        ]);

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
            __DIR__.'/../publish/config/ip_auth_limit.php' => config_path('runsite/ip_auth_limit.php'),
            __DIR__.'/../publish/config/auth.php' => config_path('auth.php'),
            __DIR__.'/../publish/config/elfinder.php' => config_path('elfinder.php'),
            __DIR__.'/../publish/config/filesystems.php' => config_path('filesystems.php'),
            __DIR__.'/../publish/resources/views/layouts/app.blade.php' => base_path('resources/views/layouts/app.blade.php'),
            __DIR__.'/../publish/resources/views/layouts/resources.blade.php' => base_path('resources/views/layouts/resources.blade.php'),
            __DIR__.'/../publish/resources/views/roots/view.blade.php' => base_path('resources/views/roots/view.blade.php'),
            __DIR__.'/../publish/resources/views/errors/404.blade.php' => base_path('resources/views/errors/404.blade.php'),
            __DIR__.'/../publish/resources/views/errors/500.blade.php' => base_path('resources/views/errors/500.blade.php'),
            __DIR__.'/../publish/resources/views/vendor/elfinder' => base_path('resources/views/vendor/elfinder'),
            __DIR__.'/../publish/app' => app_path(),
            __DIR__.'/../publish/public/asset' => base_path('public/vendor/runsite/asset'),
            __DIR__.'/../publish/public/.htaccess' => base_path('public/.htaccess'),
            __DIR__.'/../publish/resources/lang' => base_path('resources/lang'),
        ]);


        View::composer('runsite::*', AppComposer::class);
        View::composer('runsite::layouts.app', TreeComposer::class);

        
        $this->app['router']->aliasMiddleware('application-access', ApplicationAccessMiddleware::class);
        $this->app['router']->aliasMiddleware('abort-if-locked', AbortIfLocked::class);
        $this->app['router']->aliasMiddleware('check-admin-ip', CheckAdminIP::class);
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
        require_once __DIR__ . '/Helpers/t.php';

        $this->commands([
            Console\Commands\Setup\Setup::class,
            Console\Commands\CPULoad\DumpCPULoadCommand::class,
        ]);
    }
}
