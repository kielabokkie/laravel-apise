<?php

namespace Kielabokkie\Apise;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use GuzzleHttp\TransferStats;
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
     * Flag to determine if request and response should be logged.
     *
     * @var boolean
     */
    private $shouldLog = false;

    /**
     * Flag to determine if request and response data should be concealed.
     *
     * @var boolean
     */
    private $shouldConceal = false;

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

        if ($this->shouldLog === true) {
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
        $this->shouldLog = config('apise.logging_enabled');
        $this->shouldConceal = config('apise.conceal_enabled');

        if ($client === null) {
            $client = new Client;
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
     * Shorthand function for PATCH requests.
     *
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function patch($uri, array $options = [])
    {
        return $this->request('PATCH', $uri, $options);
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

        if ($this->shouldLog === true) {
            $correlationId = Uuid::uuid4()->toString();

            // Tag the request with a unique id so we can match it with the response
            $options['headers']['X-Apise-ID'] = $correlationId;

            $options['on_stats'] = function (TransferStats $stats) use ($correlationId) {
                ApiLog::where('correlation_id', $correlationId)
                    ->update([
                        'total_time' => $stats->getTransferTime() * 1000
                    ]);
            };
        }

        try {
            $response = $this->client->request($method, $this->baseUrl . $uri, $options);
        } catch (RequestException $th) {
            $response = $th->getResponse();
        }

        if ($this->shouldLog === true) {
            $log = ApiLog::where('correlation_id', $options['headers']['X-Apise-ID']);

            $headers = $this->concealInput(json_encode($response->getHeaders()));
            $body = $this->concealInput($response->getBody()->getContents());

            // Update the log with response data
            $log->update([
                'status_code' => $response->getStatusCode(),
                'reason_phrase' => $response->getReasonPhrase(),
                'response_headers' => $headers,
                'response_body' => $body,
            ]);

            // Rewind the response body or else it will be empty
            $response->getBody()->rewind();
        }

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
            function (RequestInterface $request) {
                $url = $this->parseUrl($request->getUri());

                $headers = $this->concealInput(json_encode($request->getHeaders()));
                $body = $this->concealInput($request->getBody());

                ApiLog::create([
                    'correlation_id' => $request->getHeader('X-Apise-ID')[0],
                    'method' => $request->getMethod(),
                    'protocol_version' => $request->getProtocolVersion(),
                    'host' => $url['host'],
                    'uri' => $url['path'],
                    'query_params' => $url['params'],
                    'request_headers' => $headers,
                    'request_body' => $body,
                    'tag' => empty($this->tag) === false ? $this->tag : null,
                ]);
            }
        );

        return $tapMiddleware;
    }

    /**
     * Parse a given URL and return host, path and query params separately.
     *
     * @param \GuzzleHttp\Psr7\Uri $url
     * @return array
     */
    private function parseUrl($url)
    {
        $host = sprintf('%s://%s', $url->getScheme(), $url->getHost());
        $path = empty($url->getPath()) === false ? $url->getPath() : '/';
        $params = null;

        if (empty($url->getQuery()) === false) {
            $query = ltrim($url->getQuery(), '&');
            $params = json_encode($this->parseQuery($query));
        }

        return [
            'host' => $host,
            'path' => $path,
            'params' => $params,
        ];
    }

    /**
     * Parse the query parameters and convert them to an array.
     *
     * @param string $queryParams
     * @return array
     */
    private function parseQuery($queryParams)
    {
        $queryParams = html_entity_decode($queryParams);
        $queryParams = explode('&', $queryParams);

        $arr = [];

        foreach ($queryParams as $param) {
            $part = explode('=', $param);
            $arr[$part[0]] = $part[1];
        }

        return $arr;
    }

    /**
     * Process the given input and conceal data if needed.
     *
     * @param \GuzzleHttp\Psr7\Stream $input
     * @return string
     */
    private function concealInput($input)
    {
        $input = json_decode($input, true);

        if ($input === null) {
            return null;
        }

        if ($this->shouldConceal === true) {
            $input = conceal((array)$input, config('apise.conceal_keys'));
        }

        return json_encode($input);
    }
}
