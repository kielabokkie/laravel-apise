<?php

namespace Kielabokkie\GuzzleApiService\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use Kielabokkie\GuzzleApiService\ApiClient;

class ApiServiceFake extends ApiClient
{
    /**
     * Base URL of the API.
     *
     * @var string
     */
    protected $baseUrl = 'https://httpbin.org';

    /**
     * Array of headers that were in the request.
     *
     * @var array
     */
    public $interceptedHeaders;

    /**
     * The request method.
     *
     * @var string
     */
    public $interceptedMethod;

    /**
     * Array of middlewares to be pushed on to the handler stack.
     *
     * @return array
     */
    protected function middelwares()
    {
        $tapMiddleware = Middleware::tap(function ($request, $options) {
            $this->interceptedMethod = $request->getMethod();
            $this->interceptedHeaders = array_keys($options);
        });

        return [$tapMiddleware];
    }

    /**
     * Array of default query parameters.
     *
     * @return array
     */
    protected function defaultQueryParams()
    {
        return [
            'apiKey' => '123xxx',
        ];
    }

    /**
     * Array of default headers.
     *
     * @return array
     */
    protected function defaultHeaders()
    {
        return [
            'X-Foo' => ['Bar', 'Baz'],
        ];
    }

    /**
     * Create ApiServiceFake instance.
     */
    public function __construct(Client $client = null)
    {
        $this->setClient($client);
    }

    /**
     * Get the full uri.
     *
     * @param string $uri
     * @return string
     */
    public function getFullUri($uri)
    {
        return $this->addDefaultQueryParams($uri);
    }

    /**
     * GET request.
     *
     * @param string $uri
     * @return string
     */
    public function getRequest($uri)
    {
        return $this->get($uri);
    }

    /**
     * POST request.
     *
     * @param string $uri
     * @return string
     */
    public function postRequest($uri, $data)
    {
        return $this->post($uri, $data);
    }

    /**
     * PUT request.
     *
     * @param string $uri
     * @return string
     */
    public function putRequest($uri, $data)
    {
        return $this->put($uri, $data);
    }

    /**
     * DELETE request.
     *
     * @param string $uri
     * @return string
     */
    public function deleteRequest($uri)
    {
        return $this->delete($uri);
    }
}
