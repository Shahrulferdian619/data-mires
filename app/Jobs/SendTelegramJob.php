<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $body;
    protected $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->url = config('telegram_config.telegram_api_url') . 'api/v2/sendNotifTelegram';
        $this->body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'text' => $text,
            'grup_id' => config('telegram_config.telegram_group_id_sales')
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::post($this->url, $this->body);

        Log::info('Telegram berhasil diproses kirim');
    }
}
