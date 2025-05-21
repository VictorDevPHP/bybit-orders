<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Orders\OrdersController;
use App\Http\Controllers\WhatsApp\WhatsAppController;
use App\Jobs\SendDiscordMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public $orders;

    public $wpp;

    public $numbers = [
        'Thalisson' => '556296646164',
        'Victor' => '556299297031',
        'Fabio' => '556294123173',
    ];

    public function __construct(OrdersController $ordersController)
    {
        $this->orders = $ordersController;
        $this->wpp = new WhatsAppController;
    }

    public function listenTrandingViewEvents(Request $request)
    {
        $response = $request->getContent();
        Log::info($response);
        SendDiscordMessage::dispatch(json_decode($response, true));

        // foreach ($this->numbers as $key => $number) {
        //     Log::info('Enviando para '.$key);
        //     $responseEnvioMensagem = $this->wpp->sendMessage($number, $response);
        //     sleep(5);
        // }

    }
}
