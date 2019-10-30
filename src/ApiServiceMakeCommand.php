<?php

namespace Kielabokkie\GuzzleApiService;

use Illuminate\Console\GeneratorCommand;

class ApiServiceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:api-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API Service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ApiService';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return sprintf('%s/../stubs/api-service.stub', __DIR__);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = config('api-service.namespace', 'Support\Services');

        return sprintf('%s\%s', $rootNamespace, trim($namespace, '\\'));
    }
}
