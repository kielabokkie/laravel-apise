<?php

namespace Kielabokkie\Apise\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Kielabokkie\Apise\Apise;

class ApiseApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the Apise authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Apise::auth(function ($request) {
            return app()->environment('local') ||
                Gate::check('viewApise', [$request->user()]);
        });
    }

    /**
     * Register the Apise gate. This gate determines who can access
     * Apise in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewApise', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
