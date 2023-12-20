<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendNotifTelegramJob implements ShouldQueue
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
        $this->url = 'http://127.0.0.1:8001/api/v2/sendNotif';

        $this->body = [
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
