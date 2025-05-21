<?php

namespace Tests\Unit\Orders;

use Tests\TestCase;
use App\Http\Controllers\Orders\OrdersController;

class OrdersControllerTest extends TestCase
{
    public function testSignRequestGeneratesExpectedSignature()
    {
        // Classe anônima para expor o método protegido
        $controller = new class extends OrdersController {
            public function publicSignRequest(array $payload): string
            {
                return $this->signRequest($payload);
            }
        };

        // Mock de dados de ambiente (ajuste conforme necessário)
        putenv('API_KEY_BYBIT=meuapikey');
        putenv('SECRET_KEY_BYBIT=meusecret');

        // Defina manualmente a chave para o teste
        $controller->apiKey = 'meuapikey';
        $controller->apiSecret = 'meusecret';

        $payload = [
            'timestamp' => 123456789,
            'recv_window' => 5000,
        ];

        // Esperado: adiciona api_key, ordena e assina
        $expectedPayload = $payload;
        $expectedPayload['api_key'] = 'meuapikey';
        ksort($expectedPayload);
        $query = http_build_query($expectedPayload);
        $expectedSignature = hash_hmac('sha256', $query, 'meusecret');

        $actualSignature = $controller->publicSignRequest($payload);

        $this->assertEquals($expectedSignature, $actualSignature);
    }
}
