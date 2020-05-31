<?php

namespace Kielabokkie\Apise\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kielabokkie\Apise\Console\ApiseMakeCommand;
use Kielabokkie\Apise\Console\PruneCommand;

class ApiseServiceProvider extends ServiceProvider
{
    private $root = __DIR__ . '/../..';

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
        $this->loadViews();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->root . '/config/apise.php', 'apise');

        $this->registerCommands();
    }

    private function registerRoutes()
    {
        Route::middlewareGroup('apise', config('apise.middleware', []));

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'namespace' => 'Kielabokkie\Apise\Http\Controllers',
            'prefix' => config('apise.path', 'apise'),
            'middleware' => 'apise',
        ];
    }

    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom($this->root . '/database/migrations');
        }
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->root . '/config/apise.php' => config_path('apise.php'),
            ], 'apise-config');

            $this->publishes([
               $this->root . '/public' => public_path('vendor/kielabokkie/laravel-apise'),
            ], 'apise-assets');

            $this->publishes([
               $this->root . '/stubs/ApiseServiceProvider.stub' => app_path('Providers/ApiseServiceProvider.php'),
            ], 'apise-provider');
        }
    }

    private function registerCommands()
    {
        $this->commands([
            ApiseMakeCommand::class,
            PruneCommand::class,
        ]);
    }

    private function loadViews()
    {
        $this->loadViewsFrom($this->root . '/resources/views', 'apise');
    }
}
