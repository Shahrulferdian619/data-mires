<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendDocTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doc_path, $text, $chat_id)
    {
        $this->url = 'https://telegram.altamasoft.com/api/v2/sendDoc';

        $this->body = [
            'api_token' => env('TELEGRAM_API_TOKEN'),
            'doc_path' => $doc_path,
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
