<?php

namespace App\Jobs;

use App\Models\v2\Master\Gudang;
use App\Models\v2\Persediaan\Barang;
use App\Models\v2\Service\UpdateStok;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateStokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $action;
    protected $gudang;
    protected $produk;
    protected $kuantitas;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($action, $gudang, $produk, $kuantitas)
    {
        $this->action = $action;
        $this->gudang = $gudang;
        $this->produk = $produk;
        $this->kuantitas = $kuantitas;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (is_numeric($this->gudang)) {
            $gudang_id = $this->gudang;
            $this->gudang = Gudang::find($gudang_id)->nama_gudang;
        } else {
            $gudang = Gudang::where('nama_gudang', $this->gudang)->first();
            $gudang_id = $gudang->id;
        }

        $stok = UpdateStok::where('gudang_id', $gudang_id)
            ->where('produk_id', $this->produk)->first();

        if (!$stok) {
            // buat transaksi baru bila belum ada data sebelumnya
            $stok = new UpdateStok();
            $stok->updated_by = Auth::user()->id;
            $stok->produk_id = $this->produk;
            $stok->gudang_id = $gudang_id;
            $stok->nama_gudang = $this->gudang;
            $stok->kode_produk = Barang::find($this->produk)->kode_barang;
            $stok->nama_produk = Barang::find($this->produk)->nama_barang;
        }

        if ($this->action == 'kurang') {
            $stok->kuantitas -= $this->kuantitas;
        } else if ($this->action == 'tambah') {
            $stok->kuantitas += $this->kuantitas;
        }

        $stok->save();

        Log::info('Update stok berhasil...');
    }
}
