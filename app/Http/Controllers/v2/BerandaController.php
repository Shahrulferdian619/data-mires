<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Pelanggan;
use App\Models\v2\Pembelian\PermintaanPembelian;
use App\Models\v2\Pembelian\PesananPembelian;
use App\Models\v2\Penjualan\InvoicePenjualan;
use App\Models\v2\Penjualan\Pesanan;
use App\Models\v2\Persediaan\Barang;

class BerandaController extends Controller
{
    public function index()
    {
        $data['Shopee'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Shopee')->count();
        $data['Tokopedia'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Tokopedia')->count();
        $data['Tiktok'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Tiktok Shop')->count();
        $data['Lazada'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Lazada')->count();
        $data['Offline'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Offline')->count();
        $data['Whatsapp'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Whatsapp')->count();
        $data['Blibli'] = Pesanan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'Blibli')->count();
        $data['Event'] = InvoicePenjualan::whereYear('tanggal', date('Y'))->where('jenis_penjualan', 'EVENT')->count();

        // statistik
        $data['pelanggan'] = Pelanggan::active()->count();
        $data['omset'] = InvoicePenjualan::sum('grandtotal');

        $data['jumlah_pesanan_penjualan'] = Pesanan::whereYear('tanggal', date('Y'))->count();
        $data['jumlah_katalog_produk'] = Barang::where('type', 1)->count();

        $data['permintaan_pembelian_pending'] = PermintaanPembelian::where('approve_direktur', 0)->count();
        $data['pesanan_pembelian_pending'] = PesananPembelian::where('approve_direktur', 0)->orWhere('approve_komisaris', 0)->count();

        return view('v2.beranda.index', compact('data'));
    }
}
