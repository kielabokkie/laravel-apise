<?php

namespace Kielabokkie\Apise\Tests;

use Kielabokkie\Apise\Models\ApiLog;
use Kielabokkie\Apise\Tests\TestCase;

/**
 * phpcs:disable PSR1.Methods.CamelCapsMethodName
 */
class ApiLogTest extends TestCase
{
    /** @test */
    public function accessors_return_correct_type()
    {
        $log = ApiLog::make([
            'query_params' => json_encode(['test' => 'test']),
            'request_headers' => json_encode(['test' => 'test']),
            'request_body' => json_encode(['test' => 'test']),
            'response_headers' => json_encode(['test' => 'test']),
            'response_body' => json_encode(['test' => 'test']),
        ]);

        $this->assertIsObject($log->query_params);
        $this->assertIsObject($log->request_headers);
        $this->assertIsObject($log->request_body);
        $this->assertIsObject($log->response_headers);
        $this->assertIsObject($log->response_body);
    }
}
