<?php

namespace Kielabokkie\Apise\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apise:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Apise resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Apise Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'apise-provider']);

        $this->comment('Publishing Apise Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'apise-config']);

        $this->comment('Publishing Apise Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'apise-assets']);

        $this->registerApiseServiceProvider();

        $this->info('Apise scaffolding installed successfully.');
    }

    /**
     * Register the Apise service provider.
     *
     * @return void
     */
    protected function registerApiseServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\ApiseServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\ApiseServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/ApiseServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/ApiseServiceProvider.php'))
        ));
    }
}
