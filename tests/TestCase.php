<?php

namespace Kielabokkie\Apise\Tests;

use Kielabokkie\Apise\Providers\ApiseServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use in-memory sqlite
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiseServiceProvider::class,
        ];
    }
}
