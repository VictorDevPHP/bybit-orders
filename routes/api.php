<?php

use App\Http\Controllers\Orders\OrdersController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/listenTradingView', [WebhookController::class, 'listenTrandingViewEvents']);

Route::get('/getWalletBalance', [OrdersController::class, 'getWalletBalance']);
