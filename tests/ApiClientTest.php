<?php

namespace Kielabokkie\GuzzleApiService\Test;

use Kielabokkie\GuzzleApiService\ApiClient;
use Kielabokkie\GuzzleApiService\Tests\ApiServiceFake;
use PHPUnit\Framework\TestCase;

/**
 * phpcs:disable PSR1.Methods.CamelCapsMethodName
 */
class ApiClientTest extends TestCase
{
    /** @test */
    public function service_is_instance_of_api_client()
    {
        $api = new ApiServiceFake;
        $this->assertInstanceOf(ApiClient::class, $api);
    }

    /** @test */
    public function default_query_parameters_are_added_correctly()
    {
        $api = new ApiServiceFake;

        $this->assertEquals('?apiKey=123xxx&foo=bar', $api->getFullUri('?foo=bar'));
        $this->assertEquals('/test?apiKey=123xxx', $api->getFullUri('/test'));
        $this->assertEquals('/test?apiKey=123xxx&foo=bar&faa=bor', $api->getFullUri('/test?foo=bar&faa=bor'));
    }
}
