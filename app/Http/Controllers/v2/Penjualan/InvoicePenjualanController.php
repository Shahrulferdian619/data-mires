<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Http\Controllers\Controller;
use App\Jobs\TransaksiStokJob;
use App\Jobs\UpdateStokJob;
use App\Models\Packet;
use App\Models\Penjualan_Invoice;
use App\Models\Province;
use App\Models\Sales;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Coa;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan;
use App\Models\v2\Penjualan\InvoicePenjualan;
use App\Models\v2\Penjualan\InvoicePenjualanBerkas;
use App\Models\v2\Penjualan\InvoicePenjualanRinci;
use App\Models\v2\Penjualan\Pesanan;
use App\Models\v2\Persediaan\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class InvoicePenjualanController extends Controller
{
    public $nextNomerInvoice;

    public function __construct()
    {
        // generate nomer invoice otomatis
        $nomerInvoice = InvoicePenjualan::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->orderBy('id','desc')->first();
        //dd($nomerInvoice);
        if ($nomerInvoice) {
            $splitNomerInvoice = substr($nomerInvoice->nomer_invoice_penjualan, -5);
            //dd($splitNomerInvoice);
            $this->nextNomerInvoice = sprintf('%05d', intval($splitNomerInvoice) + 1);
        } else {
            $this->nextNomerInvoice = '00001';
        }
    }
    public function index()
    {
        if (auth()->user()->cannot('viewAny', Penjualan_Invoice::class)) abort('403', 'access denied');

        $pesananPenjualanBelumInvoice = Pesanan::belumInvoice()->get();

        return view('v2.penjualan.invoice.index', [
            'invoice' => InvoicePenjualan::with('pelanggan', 'pesanan')->orderBy('tanggal', 'desc')->get(),
            'pesanan_penjualan' => $pesananPenjualanBelumInvoice,
        ]);
    }

    public function show(InvoicePenjualan $invoice_penjualan)
    {
        if (auth()->user()->cannot('view', Penjualan_Invoice::class)) abort('403', 'access denied');

        return view('v2.penjualan.invoice.show', [
            'invoice' => $invoice_penjualan,
            'invoice_rinci' => InvoicePenjualanRinci::with('produk')->where('penjualan_invoice_id', $invoice_penjualan->id)->get()
        ]);
    }

    public function create()
    {
        if (auth()->user()->cannot('create', Penjualan_Invoice::class)) abort('403', 'access denied');

        return view('v2.penjualan.invoice.create', [
            'pelanggan' => Pelanggan::active()->get(),
            'akun_bank' => Coa::bank()->get(),
            'sales' => Sales::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::produk()->get(),
            'akun_diskon' => Coa::pendapatan()->get(),
            'akun_ppn' => Coa::where('status_aktif', 1)->where('nama_coa', 'like', '%ppn%')->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all(),
            'nomer_invoice' => 'MMG/' . date('y') . '/' . date('m') . '/' . $this->nextNomerInvoice,
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->cannot('create', Penjualan_Invoice::class)) abort('403', 'access denied');

        // insert data header invoice
        $dataInvoice = InvoicePenjualan::create([
            'pelanggan_id' => $request->pelanggan_id,
            //'akun_bank_id' => $request->akun_bank_id,
            'akun_ppn_id' => $request->akun_ppn_id,
            'akun_biayakirim_id' => 139,
            'akun_diskon_id' => $request->akun_diskon_id,
            'gudang_id' => $request->gudang_id,
            //'nomer_invoice_penjualan' => 'MMG/' . date('y') . '/' . date('m') . '/' . $this->nextNomerInvoice,
            'nomer_invoice_penjualan' => $request->nomer_invoice_penjualan,
            'nomer_ref' => $request->nomer_ref,
            'tanggal' => $request->tanggal,
            'nomer_pesanan' => $request->nomer_pesanan,
            'jenis_penjualan' => $request->jenis_penjualan,
            'sales_id' => $request->sales_id,
            'created_by' => Auth::user()->id,
            'ppn' => $request->ppn,
            'nilai_ppn' => convertToDouble($request->nilai_ppn),
            'keterangan' => $request->keterangan,
            'diskon_persen_global' => $request->diskon_persen_global,
            'diskon_nominal_global' => convertToDouble($request->diskon_nominal_global),
            'grandtotal' => convertToDouble($request->total_sebelum_diskon),
            'grandtotal_setelah_diskon' => convertToDouble($request->grandtotal),
        ]);

        //insert data rincian invoice
        foreach ($request->produk as $index => $produk) {
            $rincianInvoice = new InvoicePenjualanRinci([
                'produk_id' => $produk,
                'gudang_id' => $request->gudang_id,
                'kuantitas' => $request->kuantitas[$index],
                'harga_produk' => $request->harga_produk[$index],
                'diskon_persen' => $request->harga_produk[$index] == 0 ? 0 : $request->diskon_persen[$index],
                'diskon_nominal' => convertToDouble($request->diskon_nominal[$index]),
                'subtotal' => convertToDouble($request->subtotal[$index]),
                'catatan' => $request->catatan[$index],
            ]);

            $dataInvoice->rincian()->save($rincianInvoice);

            TransaksiStokJob::dispatchSync('out', [
                'nomer_ref' => $dataInvoice->nomer_invoice_penjualan,
                'gudang_id' => $request->gudang_id,
                'produk_id' => $produk,
                'keterangan' => 'Terjual ke ' . $dataInvoice->pelanggan->nama_pelanggan,
                'kuantitas' => $request->kuantitas[$index]
            ]);

            UpdateStokJob::dispatchSync('kurang', $request->gudang_id, $produk, $request->kuantitas[$index]); //update stok gudang
        }

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat Invoice dengan nomer : ' . $dataInvoice->nomer_invoice_penjualan
        ]);

        // jika ada berkas, upload berkasnya dan simpan ke tabel
        if ($request->hasFile('berkas')) {
            $getFileName = $request->file('berkas')->getClientOriginalName();
            $file = str_replace(['-', '/'], '_', $dataInvoice->nomer_invoice_penjualan) . '_' . str_replace(' ', '', $getFileName);
            $request->file('berkas')->storeAs('berkas_invoice', $file);

            $berkasInvoice = new InvoicePenjualanBerkas([
                'nama_berkas' => $file
            ]);

            $dataInvoice->berkas()->save($berkasInvoice);
        }

        return redirect(route('invoice-penjualan.index'))->with('sukses', 'DATA INVOICE NOMER : ' . $dataInvoice->nomer_invoice_penjualan . ' BERHASIL DIBUAT ');
    }

    public function edit(InvoicePenjualan $invoice_penjualan)
    {
        if (auth()->user()->cannot('update', Penjualan_Invoice::class)) abort('403', 'access denied');

        if ($invoice_penjualan->status_proses == 1) abort(403, 'transaksi sudah selesai'); //batalkan transaksi bila status sudah selesai

        return view('v2.penjualan.invoice.edit', [
            'invoice' => $invoice_penjualan,
            'pelanggan' => Pelanggan::active()->get(),
            'akun_bank' => Coa::bank()->get(),
            'sales' => Sales::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::produk()->get(),
            'akun_diskon' => Coa::pendapatan()->get(),
            'akun_ppn' => Coa::where('status_aktif', 1)->where('nama_coa', 'like', '%ppn%')->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all(),
        ]);
    }

    public function update(Request $request, InvoicePenjualan $invoice_penjualan)
    {
        if (auth()->user()->cannot('update', Penjualan_Invoice::class)) abort('403', 'access denied');

        if ($invoice_penjualan->status_proses == 1) abort(403, 'transaksi sudah selesai'); //batalkan transaksi bila status sudah selesai

        // update header invoice
        $invoice_penjualan->update([
            'pelanggan_id' => $request->pelanggan_id,
            //'akun_bank_id' => $request->akun_bank_id,
            'akun_ppn_id' => $request->akun_bank_id,
            'akun_biayakirim_id' => 139,
            'akun_diskon_id' => $request->akun_diskon_id,
            'gudang_id' => $request->gudang_id,
            'nomer_invoice_penjualan' => $request->nomer_invoice_penjualan,
            'nomer_ref' => $request->nomer_ref,
            'tanggal' => $request->tanggal,
            'nomer_pesanan' => $request->nomer_pesanan,
            'jenis_penjualan' => $request->jenis_penjualan,
            'sales_id' => $request->sales_id,
            'ppn' => $request->ppn,
            'nilai_ppn' => convertToDouble($request->nilai_ppn),
            'keterangan' => $request->keterangan,
            'diskon_persen_global' => $request->diskon_persen_global,
            'diskon_nominal_global' => convertToDouble($request->diskon_nominal_global),
            'grandtotal' => convertToDouble($request->total_sebelum_diskon),
            'grandtotal_setelah_diskon' => convertToDouble($request->grandtotal),
        ]);

        // hapus rincian dan update stok dulu
        foreach ($invoice_penjualan->rincian as $rinci) {
            UpdateStokJob::dispatchSync('kurang', $invoice_penjualan->gudang_id, $rinci->produk_id, $invoice_penjualan->kuantitas); //update stok gudang
        }
        $invoice_penjualan->rincian()->delete(); // hapus rincian

        //insert ulang data rincian invoice
        foreach ($request->produk as $index => $produk) {
            $rincianInvoice = new InvoicePenjualanRinci([
                'produk_id' => $produk,
                'gudang_id' => $request->gudang_id,
                'kuantitas' => $request->kuantitas[$index],
                'harga_produk' => $request->harga_produk[$index],
                'diskon_persen' => $request->harga_produk[$index] == 0 ? 0 : $request->diskon_persen[$index],
                'diskon_nominal' => convertToDouble($request->diskon_nominal[$index]),
                'subtotal' => convertToDouble($request->subtotal[$index]),
                'catatan' => $request->catatan[$index],
            ]);

            $invoice_penjualan->rincian()->save($rincianInvoice);

            TransaksiStokJob::dispatchSync('out', [ // insert transaksi stok
                'nomer_ref' => $invoice_penjualan->nomer_invoice_penjualan,
                'gudang_id' => $request->gudang_id,
                'produk_id' => $produk,
                'keterangan' => 'Terjual ke ' . $invoice_penjualan->pelanggan->nama_pelanggan,
                'kuantitas' => $request->kuantitas[$index]
            ]);

            UpdateStokJob::dispatchSync('kurang', $request->gudang_id, $produk, $request->kuantitas[$index]); //update stok gudang
        }

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Merubah Invoice dengan nomer : ' . $invoice_penjualan->nomer_invoice_penjualan
        ]);

        // jika ada berkas, hapus berkas lama & simpan berkas baru
        if ($request->hasFile('berkas')) {
            // hapus berkas lama
            if ($invoice_penjualan->berkas()->exists()) {
                Storage::delete('berkas_invoice/' . $invoice_penjualan->berkas->nama_berkas);
            }

            // hapus data berkas lama
            $invoice_penjualan->berkas()->delete();

            // upload berkas baru
            $getFileName = $request->file('berkas')->getClientOriginalName();
            $file = str_replace(['-', '/'], '_', $invoice_penjualan->nomer_invoice_penjualan) . '_' . str_replace(' ', '', $getFileName);
            $request->file('berkas')->storeAs('berkas_invoice', $file);

            $berkasInvoice = new InvoicePenjualanBerkas([
                'nama_berkas' => $file
            ]);

            $invoice_penjualan->berkas()->save($berkasInvoice);
        }

        return redirect(route('invoice-penjualan.show', $invoice_penjualan->id))->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    public function destroy(InvoicePenjualan $invoice_penjualan)
    {
        if (auth()->user()->cannot('delete', Penjualan_Invoice::class)) abort('403', 'access denied');

        // update stok gudang
        foreach ($invoice_penjualan->rincian as $rinci) {
            UpdateStokJob::dispatchSync('tambah', $invoice_penjualan->gudang_id, $rinci->produk_id, $rinci->kuantitas); //update stok gudang
        }

        // hapus berkas
        if ($invoice_penjualan->berkas()->exists()) {
            Storage::delete('berkas_invoice/' . $invoice_penjualan->berkas->nama_berkas);
        }

        $invoice_penjualan->delete();

        // Hapus tabel transaksi stok
        TransaksiStokJob::dispatchSync('hapus', [
            'nomer_ref' => $invoice_penjualan->nomer_invoice_penjualan,
        ]);

        // Catat ke log & kirim ke telegram
        LogAktifitas::created([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menghapus Invoice nomer : ' . $invoice_penjualan->nomer_invoice_penjualan,
        ]);

        return redirect(route('invoice-penjualan.index'))->with('sukses', 'INVOICE NOMER : ' . $invoice_penjualan->nomer_invoice_penjualan . ' BERHASIL DIHAPUS');
    }

    public function print(InvoicePenjualan $invoice_penjualan)
    {
        if (auth()->user()->cannot('view', Penjualan_Invoice::class)) abort('403', 'access denied');

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('v2.penjualan.invoice.print-invoice', [
            'invoice' => $invoice_penjualan
        ]);

        return $pdf->stream();
    }

    public function downloadBerkas($nama_berkas)
    {
        return Storage::download('berkas_invoice/' . $nama_berkas);
    }
}
