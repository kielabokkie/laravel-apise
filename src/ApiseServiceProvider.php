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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/apise.php' => config_path('apise.php'),
            ], 'config');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/apise.php', 'apise');

        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerCommands();
        $this->loadViews();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the route group configuration array.
     *
     * @return array
     */
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
