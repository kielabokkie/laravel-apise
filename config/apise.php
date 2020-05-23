<?php

return [
    /**
     * The namespace where your API Service classes are created under.
     * This will be appended to your base namespace. So the config below
     * will create a class under App\Support\Services.
     */
    'namespace' => 'Support\Services',

    /**
     * Enable logging of requests and responses
     */
    'logging_enabled' => env('APISE_LOGGING_ENABLED', true),

    /**
     * Enable concealing of sensitive data
     */
    'conceal_enabled' => env('APISE_CONCEAL_ENABLED', true),

    /**
     * Keys that should be concealed when displayed on the Apise UI
     */
    'conceal_keys' => [
        'api_key'
    ],

    /**
     * This is the URI path where the UI will be accessible from
     */
    'path' => env('APISE_PATH', 'apise'),
];
