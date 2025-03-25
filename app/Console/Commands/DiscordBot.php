<?php

namespace App\Console\Commands;

use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\WebSockets\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\VarDumper\VarDumper;

class DiscordBot extends Command
{
    protected $signature = 'discord:bot';
    protected $description = 'Run the Discord bot';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Iniciando o bot...");
        $discord = new Discord([
            'token' => env('BOT_TOKEN'),
        ]);

        $discord->on('init', function (Discord $discord) {
            $this->info("Bot está online!");
            $discord->on(Event::MESSAGE_CREATE, function ($message) use ($discord) {
                Log::info($message);

            });
        });

        $discord->run();
    }

}
