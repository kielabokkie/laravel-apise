<?php

namespace Kielabokkie\GuzzleApiService\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kielabokkie\GuzzleApiService\ApiClient;
use Kielabokkie\GuzzleApiService\Tests\ApiServiceFake;
use PHPUnit\Framework\TestCase;

/**
 * phpcs:disable PSR1.Methods.CamelCapsMethodName
 */
class ApiClientTest extends TestCase
{
    /** @var ApiServiceFake $api */
    private $api;

    protected function setUp()
    {
        $this->api = new ApiServiceFake;
    }

    /** @test */
    public function service_is_instance_of_api_client()
    {
        $this->assertInstanceOf(ApiClient::class, $this->api);
    }

    /** @test */
    public function default_query_parameters_are_added_correctly()
    {
        $this->assertEquals(
            '?apiKey=123xxx&foo=bar',
            $this->api->getFullUri('?foo=bar')
        );

        $this->assertEquals(
            '/test?apiKey=123xxx',
            $this->api->getFullUri('/test')
        );

        $this->assertEquals(
            '/test?apiKey=123xxx&foo=bar&faa=bor',
            $this->api->getFullUri('/test?foo=bar&faa=bor')
        );
    }

    /** @test */
    public function default_headers_get_added_to_request()
    {
        $responses = new MockHandler([
            new Response(200, [], json_encode(['message' => 'hello'])),
        ]);

        $handler = HandlerStack::create($responses);

        $client = new Client(['handler' => $handler]);

        $api = new ApiServiceFake($client);
        $api->getRequest('/get');

        // Check that the default header had been added to the request
        $this->assertTrue(in_array('X-Foo', $api->interceptedHeaders));
    }

    /** @test */
    public function get_request()
    {
        $responses = new MockHandler([
            new Response(200, [], json_encode(['message' => 'hello'])),
        ]);

        $handler = HandlerStack::create($responses);
        $client = new Client(['handler' => $handler]);

        $api = new ApiServiceFake($client);
        $res = $api->getRequest('/get');
        $body = json_decode($res->getBody()->getContents());

        $this->assertEquals('GET', $api->interceptedMethod);

        $this->assertEquals('hello', $body->message);
    }

    /** @test */
    public function post_request()
    {
        $responses = new MockHandler([
            new Response(200),
        ]);

        $handler = HandlerStack::create($responses);

        $client = new Client(['handler' => $handler]);

        $api = new ApiServiceFake($client);
        $res = $api->postRequest('/post', ['json' => ['foo' => 'bar']]);
        $body = json_decode($res->getBody()->getContents());

        $this->assertEquals('POST', $api->interceptedMethod);
    }

    /** @test */
    public function put_request()
    {
        $responses = new MockHandler([
            new Response(200),
        ]);

        $handler = HandlerStack::create($responses);

        $client = new Client(['handler' => $handler]);

        $api = new ApiServiceFake($client);
        $res = $api->putRequest('/put', ['json' => ['foo' => 'bar']]);
        $body = json_decode($res->getBody()->getContents());

        $this->assertEquals('PUT', $api->interceptedMethod);
    }

    /** @test */
    public function delete_request()
    {
        $responses = new MockHandler([
            new Response(200),
        ]);

        $handler = HandlerStack::create($responses);

        $client = new Client(['handler' => $handler]);

        $api = new ApiServiceFake($client);
        $res = $api->deleteRequest('/delete');
        $body = json_decode($res->getBody()->getContents());

        $this->assertEquals('DELETE', $api->interceptedMethod);
    }
}
