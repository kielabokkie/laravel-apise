<?php

return [
    /*
     * Enable logging of request and responses to storage/logs/api-service.log
     */
    'logging_enabled' => env('API_SERVICE_LOGGING_ENABLED', false),

    /*
     * The namespace where your API Service classes are created under.
     * This will be appended to your base namespace. So the config below
     * will create a class under App\Support\Services.
     */
    'namespace' => 'Support\Services',
];
