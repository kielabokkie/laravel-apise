<?php

namespace Kielabokkie\GuzzleApiService;

use Illuminate\Support\ServiceProvider;

class GuzzleApiServiceProvider extends ServiceProvider
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
                __DIR__.'/../config/api-service.php' => config_path('api-service.php'),
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
        $this->mergeConfigFrom(__DIR__.'/../config/api-service.php', 'api-service');

        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->commands([
            ApiServiceMakeCommand::class,
        ]);
    }
}
