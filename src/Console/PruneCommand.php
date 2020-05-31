<?php

namespace Kielabokkie\Apise\Console;

use Illuminate\Console\Command;
use Kielabokkie\Apise\Models\ApiLog;

class PruneCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'apise:prune
        {--hours=24 : The number of hours of data to retain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old data from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $before = now()->subHours($this->option('hours'));

        $query = ApiLog::where('created_at', '<', $before);

        $totalDeleted = 0;

        do {
            $deleted = $query->take(1000)->delete();
            $totalDeleted += $deleted;
        } while ($deleted !== 0);

        $this->info(sprintf('%s entries pruned.', $totalDeleted));
    }
}
