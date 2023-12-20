<?php

namespace App\Jobs\v2\Telegram;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendNotifikasiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text, $chat_id)
    {
        $this->url = 'https://telegram.altamasoft.com/api/v2/sendNotif';
        //$this->url = config('telegram_config.v2_telegram_url') . 'api/v2/sendNotif';

        $this->body = [
            'api_token' => 'g6W43IYxz4v6OQTtBUh0ketSo32eLtEdGFyALHbyYyjCXMNVWo0zDm9hkPYO3JZHsXn0dc33SFV7MQa5q5jUe9zeVLCJrPd63THd',
            'parse_mode' => 'html',
            'text' => $text,
            'chat_id' => $chat_id
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Http::post($this->url, $this->body);
    }
}
