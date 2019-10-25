# Laravel Guzzle API Service

**Note: This package is still being developed and is not production ready. Use at your own risk!**

## Installation

Install the package via composer:

    composer require kielabokkie/laravel-guzzle-api-service

## Package configuration

Publish the config file by running the following command:

```bash
php artisan vendor:publish --provider="Kielabokkie\GuzzleApiService\GuzzleApiServiceProvider"
```

This is the contents of the file that will be published at `config/api-service.php`:

```php
return [
    /**
     * Enable logging of request and responses to storage/logs/api-service.log
     */
    'logging_enabled' => env('API_SERVICE_LOGGING_ENABLED', false),
];
```

## Setup

To make use of the base API Client class you'll need to add the required `$baseUrl` to set the base URL of your API. You'll also have to call the `$this->setClient();` function in the constructor of your service class.

```php
<?php

namespace App\Support\Services;

use Kielabokkie\GuzzleApiService\ApiClient;

class HttpBinService extends ApiClient
{
    protected $baseUrl = 'https://httpbin.org';

    public function __construct()
    {
        $this->setClient();
    }
}
```

## Usage

### Get request

Now to execute a `GET` request you can simply do the following:

```php
public function yourGetRequest()
{
    $response = $this->get('/get'));

    return json_decode($response->getBody()->getContents());
}
```

This is pretty basic stuff and the same as you would normally do a `GET` request with Guzzle.

### Adding default headers

APIs often require you to add a specific header to every request, for example for authorisation purposes. Instead of having to pass that as an option with every request you can add the following function at the top of your service class:

```php
protected function defaultHeaders()
{
    return [
        'Authorization' => 'Bearer abcdef123456',
    ];
}
```

### Adding default query parameters

You can add default query parameters to every request automatically in a similar way:

```php
protected function defaultQueryParams()
{
    return [
        'token' => 'your-token'
    ];
}
```

This will automatically append the token as a get parameter like so: `https://httpbin.org/get?token=your-token`.
