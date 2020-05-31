<?php

namespace Kielabokkie\Apise\Tests\Http;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Kielabokkie\Apise\Apise;
use Kielabokkie\Apise\Providers\ApiseApplicationServiceProvider;
use Kielabokkie\Apise\Tests\TestCase;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;

class AuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([VerifyCsrfToken::class]);
    }

    protected function getPackageProviders($app)
    {
        return array_merge(
            parent::getPackageProviders($app),
            [ApiseApplicationServiceProvider::class]
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Apise::auth(null);
    }

    /** @test */
    public function clean_installation_denies_access_by_default()
    {
        $this->get('/apise')
            ->assertStatus(403);
    }

    /** @test */
    public function clean_installation_denies_access_by_default_for_any_auth_user()
    {
        $this->actingAs(new Authenticated);

        $this->get('/apise')
            ->assertStatus(403);
    }

    /** @test */
    public function guests_gets_unauthorized_by_gate()
    {
        Apise::auth(function (Request $request) {
            return Gate::check('viewApise', [$request->user()]);
        });

        Gate::define('viewApise', function ($user) {
            return true;
        });

        $this->get('/apise')
            ->assertStatus(403);
    }

    /** @test */
    public function authenticated_user_gets_authorized_by_gate()
    {
        $this->actingAs(new Authenticated);

        Apise::auth(function (Request $request) {
            return Gate::check('viewApise', [$request->user()]);
        });

        Gate::define('viewApise', function (Authenticatable $user) {
            return $user->getAuthIdentifier() === 'apise-test';
        });

        $this->get('/apise')
            ->assertStatus(200);
    }

    /** @test */
    public function guests_can_be_authorized()
    {
        Apise::auth(function (Request $request) {
            return Gate::check('viewApise', [$request->user()]);
        });

        Gate::define('viewApise', function (?Authenticatable $user) {
            return true;
        });

        $this->get('/apise')
            ->assertStatus(200);
    }

    /** @test */
    public function unauthorized_requests()
    {
        Apise::auth(function () {
            return false;
        });

        $this->get('/apise')
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_requests()
    {
        Apise::auth(function () {
            return true;
        });

        $this->get('/apise')
            ->assertSuccessful();
    }
}

class Authenticated implements Authenticatable
{
    public $email;

    public function getAuthIdentifierName()
    {
        return 'Apise Test';
    }

    public function getAuthIdentifier()
    {
        return 'apise-test';
    }

    public function getAuthPassword()
    {
        return 'secret';
    }

    public function getRememberToken()
    {
        return 'apise';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        //
    }
}
