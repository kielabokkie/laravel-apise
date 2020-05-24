<?php

namespace Kielabokkie\Apise;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kielabokkie\Apise\Console\ApiseMakeCommand;
use Kielabokkie\Apise\Console\PruneCommand;

class ApiseServiceProvider extends ServiceProvider
{
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
        $this->mergeConfigFrom(__DIR__.'/../config/apise.php', 'apise');

        $this->registerCommands();
    }

    private function registerRoutes()
    {
        Route::middlewareGroup('apise', config('apise.middleware', []));

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
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
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/apise.php' => config_path('apise.php'),
            ], 'apise-config');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/kielabokkie/laravel-apise'),
            ], 'apise-assets');

            $this->publishes([
                __DIR__.'/../stubs/ApiseServiceProvider.stub' => app_path('Providers/ApiseServiceProvider.php'),
            ], 'apise-provider');
        }
    }

    private function registerCommands()
    {
        $this->commands([
            ApiseMakeCommand::class,
            PruneCommand::class
        ]);
    }

    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'apise');
    }
}
