<?php

namespace App\Jobs;

use App\Models\v2\Job\TransaksiStok;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransaksiStokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($action, $data)
    {
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->action == 'hapus') {
            TransaksiStok::where('nomer_ref', $this->data['nomer_ref'])->delete();
        } else {
            $transaksiStok = new TransaksiStok();
            $transaksiStok->nomer_ref = $this->data['nomer_ref'];
            $transaksiStok->gudang_id = $this->data['gudang_id'];
            $transaksiStok->produk_id = $this->data['produk_id'];
            $transaksiStok->keterangan = $this->data['keterangan'];

            if ($this->action == 'in') {
                $transaksiStok->in = $this->data['kuantitas'];
                $transaksiStok->out = 0;
            } elseif ($this->action == 'out') {
                $transaksiStok->in = 0;
                $transaksiStok->out = $this->data['kuantitas'];
            }
            $transaksiStok->save();
        }
    }
}
