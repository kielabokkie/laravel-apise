# Apise for Laravel

The Apise package for Laravel can be used to simplify creating an API service for integrating with an external JSON API.

## Installation

Install the package via composer:

    composer require kielabokkie/laravel-apise

## Package configuration

Publish the config file by running the following command:

```bash
php artisan vendor:publish --provider="Kielabokkie\Apise\ApiseServiceProvider"
```

This is the contents of the file that will be published at `config/apise.php`:

```php
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
```

## Setup

To make use of the base API Client class you'll need to add the required `$baseUrl` to set the base URL of your API. You'll also have to call the `$this->setClient();` function in the constructor of your service class.

```php
<?php

namespace App\Support\Services;

use Kielabokkie\Apise\ApiseClient;

class HttpBinService extends ApiseClient
{
    protected $baseUrl = 'https://httpbin.org';

    public function __construct()
    {
        $this->setClient();
    }
}
```

To make it easy to get started you can use the following command to scaffold your API Service class:

```bash
php artisan make:api-service HttpBinService
```

This will create a class called `HttpBinService.php` in the `app/Support/Services` folder. All you have to do is set your `$baseUrl` and you are good to go.

Note: If you would like your classes to be placed somewhere else you can overwrite the `namespace` variable in the `apise.php` config file.

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

## Development

When working on the view that shows the logs you can run the Webpack dev server:

```
npm run hot
```

This will run the dev server on http://127.0.0.1:8080 with hot reload enabled.
