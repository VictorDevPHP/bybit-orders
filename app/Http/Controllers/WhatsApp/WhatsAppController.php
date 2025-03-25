<?php

namespace App\Http\Controllers\WhatsApp;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WhatsAppController
{
    /**
     * Envia uma mensagem para o número de telefone informado.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMessage($phone, $texto)
    {
        $client = new Client();
        try {
            $response = $client->post('http://localhost:3000/wppconnect/sendMessage', [
                'json' => [
                    'phone' => $phone,
                    'text' => $texto,
                ]
            ]);

            return response()->json([
                'status' => 'success',
                'response' => json_decode($response->getBody()->getContents()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
