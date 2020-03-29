<?php

return [
    /**
     * Enable logging of requests and responses
     */
    'logging_enabled' => env('APISE_LOGGING_ENABLED', false),

    /**
     * This is the URI path where the UI will be accessible from
     */
    'path' => env('APISE_PATH', 'apise'),

    /**
     * The namespace where your API Service classes are created under.
     * This will be appended to your base namespace. So the config below
     * will create a class under App\Support\Services.
     */
    'namespace' => 'Support\Services',
];
