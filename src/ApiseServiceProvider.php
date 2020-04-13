<?php

namespace Kielabokkie\Apise;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kielabokkie\Apise\Console\ApiServiceMakeCommand;

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
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'namespace' => 'Kielabokkie\Apise\Http\Controllers',
            'prefix' => config('apise.path', 'apise'),
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
        }
    }

    private function registerCommands()
    {
        $this->commands([
            ApiServiceMakeCommand::class,
        ]);
    }

    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'apise');
    }
}
