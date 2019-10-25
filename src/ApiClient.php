<?php

namespace Kielabokkie\GuzzleApiService;

use Concat\Http\Middleware\Logger as GuzzleLogger;
use GuzzleHttp\Client;
use GuzzleHttp\MessageFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

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

    protected function defaultMiddlewares()
    {
        $middlewares = collect();

        if (config('api-service.logging_enabled') === true) {
            $logger = new Logger('api');
            $logger->pushHandler(
                new StreamHandler(storage_path('logs/api.log'), Logger::DEBUG)
            );

            $formatter = new MessageFormatter(
                '{req_header_User-Agent} - "{method} {target} HTTP/{version}" - {req_body} - {code} - {res_body}'
            );

            $loggerMiddleware = new GuzzleLogger($logger, $formatter);

            $middlewares->push($loggerMiddleware);
        }

        return $middlewares->toArray();
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
     * Array of default headers.
     *
     * @return array
     */
    protected function defaultHeaders()
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
            $client = new Client([
                'base_uri' => $this->baseUrl,
            ]);
        }

        $middlewares = array_merge($this->defaultMiddlewares(), $this->middelwares());

        // Push Guzzle middlewares on to the handler stack
        foreach ($middlewares as $middleware) {
            $handlerStack = $client->getConfig('handler');
            $handlerStack->push($middleware);
        }

        $this->client = $client;
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
     * Shorthand function for POST requests.
     *
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function post($uri, array $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * Shorthand function for PUT requests.
     *
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function put($uri, array $options = [])
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * Shorthand function for DELETE requests.
     *
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function delete($uri, array $options = [])
    {
        return $this->request('DELETE', $uri, $options);
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
        $options = $this->addHeaders($options);

        return $this->client->request($method, $uri, $options);
    }

    /**
     * Add the default headers to the headers specified in the request.
     *
     * @param array $options
     * @return array
     */
    protected function addHeaders(array $options)
    {
        if (isset($options['headers']) === false) {
            $options['headers'] = $this->defaultHeaders();

            return $options;
        }

        $options = array_merge($this->defaultHeaders(), $options['headers']);

        return $options;
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
