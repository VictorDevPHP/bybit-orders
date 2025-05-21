<?php

use App\Http\Controllers\Discord\DiscordMessagesController;

require 'vendor/autoload.php';

$message = 'Olá, Discord!';

$botService = new DiscordMessagesController;
$botService->sendMessage(env('CANAL_ID'), $message);
