# Laravel Guzzle API Service

**Note: This package is still being developed and is not production ready. Use at your own risk!**

## Installation

Install the package via composer:

    composer require kielabokkie/laravel-guzzle-api-service

## Setup

To make use the base API Client class you'll need to add the required `$baseUrl` to set the base URL of your API. You'll also have to call the `$this->setClient();` function in the constructor of your service class.

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

Now to do a GET request you can simply do the following:

```php
public function yourGetRequest()
{
    $response = $this->get('/get'));

    return json_decode($response->getBody()->getContents());
}
```

### Adding default query parameters

Sometimes you'll have APIs that require you to add a default GET parameter to pass an API token for example. Instead of having to pass that as an option with every request you can add the following function at the top of your service class:

```php
protected function defaultQueryParams()
{
    return [
        'token' => 'your-token'
    ];
}
```

This will automatically append the token as a get parameter like so: `https://httpbin.org?token=your-token`.
