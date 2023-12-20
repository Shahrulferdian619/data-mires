<?php

namespace App\Jobs;

use App\Models\v2\Penjualan\InvoicePenjualan;
use App\Models\v2\Penjualan\InvoicePenjualanRinci;
use App\Models\v2\Penjualan\Pesanan;
use App\Models\v2\Penjualan\PesananRinci;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoicePenjualanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $idPesananPenjualan;
    protected $action;
    protected $nextNomerInvoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($action, $idPesananPenjualan)
    {
        $this->action = $action;
        $this->idPesananPenjualan = $idPesananPenjualan;

        // generate nomer invoice otomatis
        $nomerInvoice = InvoicePenjualan::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('id','desc')->first();
        if ($nomerInvoice) {
            $splitNomerInvoice = substr($nomerInvoice->nomer_invoice_penjualan, -5); // mengambil 5 digit terakhir nomer invoice
            $this->nextNomerInvoice = sprintf('%05d', intval($splitNomerInvoice) + 1); // selanjutnya menambahkan + 1
        } else {
            $this->nextNomerInvoice = '00001'; // jika belum ada nomer invoice di bulan berjalan, buat menjadi 00001
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataPesananPenjualan = Pesanan::find($this->idPesananPenjualan);

        if ($this->action == 'baru') {
            $nomerInvoice = 'MMG/' . date('y') . '/' . date('m') . '/' . $this->nextNomerInvoice;

            $invoicePenjualan = InvoicePenjualan::create([
                'penjualan_pesanan_id' => $dataPesananPenjualan->id,
                'akun_ppn_id' => $dataPesananPenjualan->akun_ppn_id,
                'akun_biayakirim_id' => $dataPesananPenjualan->akun_biayakirim_id,
                'akun_diskon_id' => $dataPesananPenjualan->akun_diskon_id,
                'pelanggan_id' => $dataPesananPenjualan->pelanggan_id,
                'sales_id' => $dataPesananPenjualan->sales_id,
                'created_by' => $dataPesananPenjualan->created_by,
                'gudang_id' => $dataPesananPenjualan->gudang_id,
                'nomer_invoice_penjualan' => $nomerInvoice,
                'nomer_ref' => $dataPesananPenjualan->nomer_pesanan_penjualan,
                'tanggal' => $dataPesananPenjualan->tanggal,
                'keterangan' => $dataPesananPenjualan->keterangan,
                'jenis_penjualan' => $dataPesananPenjualan->jenis_penjualan,
                'ppn' => $dataPesananPenjualan->ppn,
                'nilai_ppn' => $dataPesananPenjualan->nilai_ppn,
                'nomer_pesanan' => $dataPesananPenjualan->nomer_pesanan,
                'resi' => $dataPesananPenjualan->resi,
                'ekspedisi' => $dataPesananPenjualan->ekspedisi,
                'penerima' => $dataPesananPenjualan->penerima,
                'alamat_penerima' => $dataPesananPenjualan->alamat_penerima,
                'diskon_persen_global' => $dataPesananPenjualan->diskon_persen,
                'diskon_nominal_global' => $dataPesananPenjualan->diskon_global,
                'biaya_kirim' => $dataPesananPenjualan->biaya_kirim,
                'grandtotal' => $dataPesananPenjualan->grandtotal,
                'grandtotal_setelah_diskon' => $dataPesananPenjualan->grandtotal_setelah_diskon,
            ]);

            //$dataRinciPesananPenjualan = PesananRinci::where('penjualan_pesanan_id', $this->idPesananPenjualan)->get();
            $dataRinciArray = [];
            foreach ($dataPesananPenjualan->rincian as $rinci) {
                $dataRinciArray[] = [
                    'penjualan_invoice_id' => $invoicePenjualan->id,
                    'produk_id' => $rinci->produk_id,
                    'gudang_id' => $rinci->gudang_id,
                    'kuantitas' => $rinci->kuantitas,
                    'harga_produk' => $rinci->harga_produk,
                    'diskon_persen' => $rinci->diskon_persen,
                    'diskon_nominal' => $rinci->diskon_nominal,
                    'potongan_admin' => $rinci->potongan_admin,
                    'cashback' => $rinci->cashback,
                    'subtotal' => $rinci->subtotal,
                    'catatan' => $rinci->catatan,
                ];
            }
            InvoicePenjualanRinci::insert($dataRinciArray);
        } elseif ($this->action == 'update') {
            $invoicePenjualan = InvoicePenjualan::where('penjualan_pesanan_id', $this->idPesananPenjualan); // mencari id invoice yang diupdate

            $invoicePenjualan->update([
                'akun_ppn_id' => $dataPesananPenjualan->akun_ppn_id,
                'akun_biayakirim_id' => $dataPesananPenjualan->akun_biayakirim_id,
                'akun_diskon_id' => $dataPesananPenjualan->akun_diskon_id,
                'pelanggan_id' => $dataPesananPenjualan->pelanggan_id,
                'sales_id' => $dataPesananPenjualan->sales_id,
                'created_by' => $dataPesananPenjualan->created_by,
                'gudang_id' => $dataPesananPenjualan->gudang_id,
                'tanggal' => $dataPesananPenjualan->tanggal,
                'keterangan' => $dataPesananPenjualan->keterangan,
                'jenis_penjualan' => $dataPesananPenjualan->jenis_penjualan,
                'ppn' => $dataPesananPenjualan->ppn,
                'nilai_ppn' => $dataPesananPenjualan->nilai_ppn,
                'nomer_pesanan' => $dataPesananPenjualan->nomer_pesanan,
                'resi' => $dataPesananPenjualan->resi,
                'ekspedisi' => $dataPesananPenjualan->ekspedisi,
                'penerima' => $dataPesananPenjualan->penerima,
                'alamat_penerima' => $dataPesananPenjualan->alamat_penerima,
                'diskon_persen_global' => $dataPesananPenjualan->diskon_persen,
                'diskon_nominal_global' => $dataPesananPenjualan->diskon_global,
                'biaya_kirim' => $dataPesananPenjualan->biaya_kirim,
                'grandtotal' => $dataPesananPenjualan->grandtotal,
                'grandtotal_setelah_diskon' => $dataPesananPenjualan->grandtotal_setelah_diskon,
            ]);

            // hapus rincian invoice penjualan dulu
            InvoicePenjualanRinci::where('penjualan_invoice_id', $invoicePenjualan->first()->id)->delete();

            // kemudian input ulang rincian invoice penjualan
            $dataRinciArray = [];
            foreach ($dataPesananPenjualan->rincian as $rinci) {
                $dataRinciArray[] = [
                    'penjualan_invoice_id' => $invoicePenjualan->first()->id,
                    'produk_id' => $rinci->produk_id,
                    'gudang_id' => $rinci->gudang_id,
                    'kuantitas' => $rinci->kuantitas,
                    'harga_produk' => $rinci->harga_produk,
                    'diskon_persen' => $rinci->diskon_persen,
                    'diskon_nominal' => $rinci->diskon_nominal,
                    'potongan_admin' => $rinci->potongan_admin,
                    'cashback' => $rinci->cashback,
                    'subtotal' => $rinci->subtotal,
                    'catatan' => $rinci->catatan,
                ];
            }
            InvoicePenjualanRinci::insert($dataRinciArray);
        } elseif ($this->action == 'hapus') {
            InvoicePenjualan::where('nomer_ref', $dataPesananPenjualan->nomer_pesanan_penjualan)->delete();
        }
    }
}
