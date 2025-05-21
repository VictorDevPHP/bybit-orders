<?php

namespace App\Jobs;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDiscordMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channelId;

    protected $message;

    /**
     * Cria uma nova instância do Job.
     */
    public function __construct(array $message)
    {
        $this->message = $this->formatMessageForDiscord($message);
    }

    public function handle()
    {
        $discord = new Discord([
            'token' => env('BOT_TOKEN'),
        ]);

        if (str_contains($this->message, 'Ação: Buy')) {
            $this->channelId = env('CANAL_ID_BUY');
            Log::info($this->channelId);
        } elseif (str_contains($this->message, 'Ação: Sell')) {
            $this->channelId = env('CANAL_ID_SELL');
            Log::info($this->channelId);
        } else {
            $this->channelId = 'Não encontrado';
            Log::info($this->channelId);
        }

        $discord->on('init', function (Discord $discord) {
            $channel = $discord->getChannel($this->channelId);
            if ($channel) {
                $channel->sendMessage($this->message)->then(
                    function (Message $message) use ($discord) {
                        $discord->close();
                    },
                    function (\Exception $e) use ($discord) {
                        Log::error('Erro ao enviar mensagem: '.$e->getMessage());
                        $discord->close();
                    }
                );
            } else {
                Log::error("Canal não encontrado: {$this->channelId}");
                $discord->close();
            }
        });
        $discord->run();
    }

    /**
     * Formata a mensagem para leitura humana no Discord.
     */
    public function formatMessageForDiscord($message): string
    {
        $formattedTicker = strtoupper(str_replace('USDT', '-USDT', $message['order']['ticker']));
        $formattedMessage = sprintf(
            "**Nova Ordem**\n🟢 Ação: %s\n📜 Contratos: %s\n📊 Ticker: %s\n📉 Novo Tamanho da Posição: %s",
            ucfirst($message['order']['action']),
            $message['order']['contracts'],
            $formattedTicker,
            $message['order']['new_position_size']
        );

        return $formattedMessage;
    }
}
