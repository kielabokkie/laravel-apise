<?php

namespace Kielabokkie\Apise\Tests\Http;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestResponse as LegacyTestResponse;
use Illuminate\Testing\TestResponse;
use Kielabokkie\Apise\Http\Middleware\Authorize;
use Kielabokkie\Apise\Models\ApiLog;
use Kielabokkie\Apise\Tests\TestCase;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;
use PHPUnit\Framework\Assert as PHPUnit;

class RouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([Authorize::class, VerifyCsrfToken::class]);
        $this->loadFactoriesUsing($this->app, __DIR__ . '/../../database/factories');
        $this->registerAssertJsonExactFragmentMacro();
    }

    /** @test */
    public function list_of_the_latest_logs()
    {
        $log = factory(ApiLog::class)->create(['created_at' => now()]);

        $this->get('/apise/api/logs')
            ->assertSuccessful()
            ->assertJsonExactFragment(1, 'total')
            ->assertJsonExactFragment($log->correlation_id, 'logs.0.correlation_id');
    }

    /** @test */
    public function can_get_older_logs()
    {
        $log = factory(ApiLog::class)->create(['created_at' => now()]);

        // Newer log
        factory(ApiLog::class)->create(['created_at' => now()]);

        $this->get('/apise/api/logs/2')
            ->assertSuccessful()
            ->assertJsonExactFragment(2, 'total')
            ->assertJsonExactFragment($log->correlation_id, 'logs.0.correlation_id');
    }

    /** @test */
    public function latest_logs()
    {
        // Old log
        factory(ApiLog::class)->create(['created_at' => now()]);

        $latest = factory(ApiLog::class)->create(['created_at' => now()]);

        $this->get('/apise/api/logs/latest/1')
            ->assertSuccessful()
            ->assertJsonExactFragment($latest->correlation_id, '0.correlation_id');
    }

    private function registerAssertJsonExactFragmentMacro()
    {
        $assertion = function ($expected, $key) {
            $jsonResponse = $this->json();

            PHPUnit::assertEquals(
                $expected,
                $actualValue = data_get($jsonResponse, $key),
                "Failed asserting that [$actualValue] matches expected [$expected].".PHP_EOL.PHP_EOL.
                json_encode($jsonResponse)
            );

            return $this;
        };

        if (Application::VERSION === '7.x-dev' || version_compare(Application::VERSION, '7.0', '>=')) {
            TestResponse::macro('assertJsonExactFragment', $assertion);
        } else {
            LegacyTestResponse::macro('assertJsonExactFragment', $assertion);
        }
    }
}
