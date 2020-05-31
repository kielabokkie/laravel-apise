<?php

namespace Kielabokkie\Apise\Tests\Console;

use Kielabokkie\Apise\Models\ApiLog;
use Kielabokkie\Apise\Tests\TestCase;

class PruneCommandTest extends TestCase
{
    /** @test */
    public function prune_command_will_clear_old_logs()
    {
        $this->loadFactoriesUsing($this->app, __DIR__ . '/../../database/factories');

        $recent = factory(ApiLog::class)->create(['created_at' => now()]);
        $old = factory(ApiLog::class)->create(['created_at' => now()->subDays(2)]);

        $this->artisan('apise:prune')
            ->expectsOutput('1 entries pruned.');

        $this->assertDatabaseHas(
            'apise_logs', ['correlation_id' => $recent->correlation_id]
        );
        $this->assertDatabaseMissing(
            'apise_logs', ['correlation_id' => $old->correlation_id]
        );
    }

    /** @test */
    public function prune_command_can_vary_hours()
    {
        $this->loadFactoriesUsing($this->app, __DIR__ . '/../../database/factories');

        $recent = factory(ApiLog::class)->create(['created_at' => now()->subHours(5)]);

        $this->artisan('apise:prune')
            ->expectsOutput('0 entries pruned.');

        $this->artisan('apise:prune', ['--hours' => 4])
            ->expectsOutput('1 entries pruned.');

        $this->assertDatabaseMissing(
            'apise_logs', ['correlation_id' => $recent->correlation_id]
        );
    }
}
