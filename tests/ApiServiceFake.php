<?php

namespace Kielabokkie\GuzzleApiService\Tests;

use Kielabokkie\GuzzleApiService\ApiClient;

class ApiServiceFake extends ApiClient
{
    /**
     * Base URL of the API.
     *
     * @var string
     */
    protected $baseUrl = 'http://api.test.com/v1/';

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
     * Create EtherscanService instance.
     */
    public function __construct()
    {
        $this->setClient();
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
}
