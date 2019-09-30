<?php

namespace Kielabokkie\GuzzleApiService;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class ApiClient
{
    /**
     * Http client
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
                'handler' => $stack,
            ]);
        }

        $this->client = $client;
    }
}
