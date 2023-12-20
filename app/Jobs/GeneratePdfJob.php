<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $htmlPdf;
    protected $fileName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($htmlPdf, $fileName)
    {
        $this->htmlPdf = $htmlPdf;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // generate PDF
        $pdf = PDF::loadHTML($this->htmlPdf);
        Storage::put('tmp/', $pdf->output());
    }
}