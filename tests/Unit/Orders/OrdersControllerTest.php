<?php

namespace Tests\Unit\Orders;

use Tests\TestCase;
use App\Http\Controllers\Orders\OrdersController;

class OrdersControllerTest extends TestCase
{
    public function testBuildQueryStringGeneratesCorrectQuery()
    {
        $controller = new class extends OrdersController {
            public function publicBuildQueryString(array $payload): string
            {
                return $this->buildQueryString($payload);
            }
        };

        $payload = [
            'timestamp' => 123456789,
            'recv_window' => 5000,
            'api_key' => 'meuapikey',
        ];

        $expected = 'api_key=meuapikey&recv_window=5000&timestamp=123456789';

        $query = $controller->publicBuildQueryString($payload);

        $this->assertEquals($expected, $query);
    }

}
