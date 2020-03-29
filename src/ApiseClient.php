<?php

namespace Kielabokkie\Apise;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use Kielabokkie\Apise\Models\ApiLog;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;

class ApiseClient
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
     * The Guzzle Middlewares that are always loaded for the package.
     *
     * @return array
     */
    protected function defaultMiddlewares()
    {
        $middlewares = collect();

        if (config('api-service.logging_enabled') === true) {
            $middlewares->push($this->dbLoggerMiddleware());
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

        // Tag the request with a unique id so we can match it with the response
        $options['headers']['X-Api-LogID'] = Uuid::uuid4()->toString();

        $response = $this->client->request($method, $uri, $options);

        $log = ApiLog::where('correlation_id', $options['headers']['X-Api-LogID']);

        // Update the log with response data
        $log->update([
            'status_code' => $response->getStatusCode(),
            'reason_phrase' => $response->getReasonPhrase(),
            'response_headers' => json_encode($response->getHeaders()),
            'response_body' => $response->getBody()->getContents(),
        ]);

        // Rewind the response body or else it will be empty
        $response->getBody()->rewind();

        return $response;
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

        $fullUri = sprintf('%s?%s', $path, $queryParams);

        return rtrim($fullUri, '?');
    }

    /**
     * Use the Guzzle middleware to save requests to the database.
     *
     * @return \GuzzleHttp\Middleware
     */
    private function dbLoggerMiddleware()
    {
        $tapMiddleware = Middleware::tap(
            function (RequestInterface $request, $options) {
                ApiLog::create([
                    'correlation_id' => $request->getHeader('X-Api-LogID')[0],
                    'method' => $request->getMethod(),
                    'protocol_version' => $request->getProtocolVersion(),
                    'uri' => $request->getUri(),
                    'request_headers' => json_encode($request->getHeaders()),
                    'request_body' => $request->getBody(),
                    'tag' => empty($this->tag) === false ? $this->tag : null,
                ]);
            }
        );

        return $tapMiddleware;
    }
}
