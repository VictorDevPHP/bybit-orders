<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class OrdersController extends Controller
{
    protected $client;

    public $apiKey;

    public $apiSecret;

    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client;
        $this->apiKey = env('API_KEY_BYBIT');
        $this->apiSecret = env('SECRET_KEY_BYBIT');
        $this->baseUrl = 'https://api.bybit.com'; // Troque para 'https://api-testnet.bybit.com' se necessário
    }

    public function getWalletBalance()
    {
        date_default_timezone_set('UTC');
        $timestamp = round(microtime(true) * 1000);
        $recvWindow = 5000; // Valor padrão recomendado pela Bybit
        // Parâmetros obrigatórios da Bybit
        $payload = [
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'recv_window' => $recvWindow,
        ];

        // Assinatura
        $sign = $this->signRequest($payload);

        // Realiza a requisição GET com os parâmetros corretos
        $response = $this->client->get("{$this->baseUrl}/v5/account/wallet-balance", [
            'query' => [
                'accountType' => 'UNIFIED',
                'coin' => 'BTC', // Opcional, se quiser consultar uma moeda específica
            ],
            'headers' => [
                'X-BAPI-API-KEY' => $this->apiKey,
                'X-BAPI-TIMESTAMP' => $timestamp,
                'X-BAPI-SIGN' => $sign,
                'X-BAPI-RECV-WINDOW' => $recvWindow,
            ],
        ]);

        // Depuração para ver a resposta
        dd($response);

        // Processa a resposta
        $data = json_decode($response->getBody()->getContents(), true);

        return $data['result'] ?? [];
    }

    protected function signRequest(array $payload): string    {
        ksort($payload);
        $payload['api_key'] = $this->apiKey;
        $queryString = http_build_query($payload);

        return hash_hmac('sha256', $queryString, $this->apiSecret);
    }

    public function buildQueryString(array $payload): string
    {
        ksort($payload);
        return http_build_query($payload, '', '&', PHP_QUERY_RFC3986);
    }

}
