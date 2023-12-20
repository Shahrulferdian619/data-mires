<?php

namespace App\Jobs\v2\Generate;

use App\Models\v2\Pembelian\PermintaanPembelian;
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

    protected $nama_modul;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nama_modul, $data)
    {
        //
        $this->nama_modul = $nama_modul;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->nama_modul == 'permintaan-pembelian') {
            $pdf = PDF::loadView('v2.pembelian.permintaan-pembelian.print-pdf',[
                'permintaan' => $this->data['permintaan'],
            ]);
            $path = public_path('tmp/') . $this->data['filename'];
            Storage::put($path, $pdf->output());
        }
    }
}
