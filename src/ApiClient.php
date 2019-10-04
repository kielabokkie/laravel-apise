<?php

namespace Kielabokkie\GuzzleApiService;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class ApiClient
{
    /**
     * Http client.
     *
     * @var Client
     */
    protected $client;

    /**
     * Array of middlewares to be pushed on to the handler stack.
     *
     * @return array
     */
    protected function middelwares()
    {
        return [];
    }

    /**
     * Array of default query parameters.
     *
     * @return array
     */
    protected function defaultQueryParams()
    {
        return [];
    }

    /**
     * Set the Guzzle client.
     *
     * @param Client $client
     */
    protected function setClient(Client $client = null)
    {
        if ($client === null) {
            $stack = HandlerStack::create();

            // Push Guzzle middlewares on to the handler stack
            foreach ($this->middelwares() as $middleware) {
                $stack->push($middleware);
            }

            $client = new Client([
                'base_uri' => $this->baseUrl,
                'handler' => $stack,
            ]);
        }

        $this->client = $client;
    }

    /**
     * Execute a given request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function request($method, $uri, array $options = [])
    {
        $uri = $this->addDefaultQueryParams($uri);

        return $this->client->request($method, $uri, $options);
    }

    /**
     * Shorthand function for GET requests.
     *
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function get($uri, array $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * Add default query parameters to the uri.
     *
     * @param string $uri
     * @return string
     */
    protected function addDefaultQueryParams($uri)
    {
        $defaultParams = http_build_query($this->defaultQueryParams());

        $parts = parse_url($uri);
        $path = $parts['path'] ?? '';
        $queryParams = $parts['query'] ?? null;

        if ($queryParams !== null) {
            $queryParams = sprintf('%s&%s', $defaultParams, $queryParams);
        }

        if (empty($queryParams) === true) {
            $queryParams = $defaultParams;
        }

        return sprintf('%s?%s', $path, $queryParams);
    }
}
