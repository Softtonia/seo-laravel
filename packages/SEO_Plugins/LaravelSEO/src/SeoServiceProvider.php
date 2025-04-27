<?php

namespace SEO_Plugins\LaravelSEO;

use Illuminate\Support\ServiceProvider;
use SEO_Plugins\LaravelSEO\Console\GenerateSeoMetaCommand;
use SEO_Plugins\LaravelSEO\Console\SeoPluginUninstallCommand;
use SEO_Plugins\LaravelSEO\Console\SeoPluginUpdateCommand;
use SEO_Plugins\LaravelSEO\Http\Middleware\SeoAccessMiddleware;
use SEO_Plugins\LaravelSEO\SeoManager;
use SEO_Plugins\LaravelSEO\Console\SeoPluginInstallCommand;// Ensure this points to the correct path
use SEO_Plugins\LaravelSEO\Observers\SeoObserver;
use App\Models\Post;
use App\Models\Page;
use Spatie\Permission\Models\Role;

class SeoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/seo.php', 'seo');

        $this->app->singleton('seo', function ($app) {
            return new SeoManager();
        });

        if ($this->app->runningInConsole()) {
        $this->commands([
            SeoPluginInstallCommand::class,
            SeoPluginUninstallCommand::class,
            SeoPluginUpdateCommand::class,
            GenerateSeoMetaCommand::class,
        ]);
        }


        // Register middleware alias
        $this->app['router']->aliasMiddleware('seo.access', SeoAccessMiddleware::class);


    }

    public function boot()
    {
        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/seo.php' => config_path('seo.php'),
        ], 'config');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish migrations
        // $this->publishes([
        //     __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        // ], 'migrations');

        // Publish assets
        $assetPath = __DIR__ . '/../../public';
        if (is_dir($assetPath)) {
            $this->publishes([
                $assetPath => public_path('vendor/seo'),
            ], 'assets');
        }


        // Attach observer to all models
        $models = config('seo.models', []);

        foreach ($models as $model) {
            if (class_exists($model)) {
                $model::observe(SeoObserver::class);
            }
        }


        //  Register default SEO roles
        $this->registerDefaultRoles();



    }


    protected function registerDefaultRoles()
    {
        $roles = ['Super Admin', 'SEO Manager', 'Shop Manager'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
