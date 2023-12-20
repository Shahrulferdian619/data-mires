<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Exports\PengirimanPenjualanExport;
use App\Http\Controllers\Controller;
use App\Models\Penjualan_DO;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Penjualan\PengirimanPenjualan;
use App\Models\v2\Penjualan\PengirimanPenjualanBerkas;
use App\Models\v2\Penjualan\PengirimanPenjualanRinci;
use App\Models\v2\Penjualan\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PengirimanPenjualanController extends Controller
{
    public function index()
    {
        if (auth()->user()->cannot('viewAny', Penjualan_DO::class)) abort('403', 'access denied');

        return view('v2.penjualan.pengiriman.index', [
            'pesanan' => Pesanan::orderBy('created_at', 'desc')
                ->with('pengiriman','pelanggan')
                ->get()
        ]);
    }

    public function show(Pesanan $pengiriman_penjualan)
    {
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');

        return view('v2.penjualan.pengiriman.show', [
            'pesanan' => $pengiriman_penjualan
        ]);
    }

    public function store($data)
    {
        if (auth()->user()->cannot('create', Penjualan_DO::class)) abort('403', 'access denied');

        try {
            // insert data ke tabel pengiriman
            $pengiriman = PengirimanPenjualan::create([
                'penjualan_pesanan_id' => $data->id,
                'nomer_pengiriman_penjualan' => str_replace('SO', 'SJ', $data->nomer_pesanan_penjualan),
                'pelanggan_id' => $data->pelanggan_id,
                'created_by' => Auth::user()->id,
                'tanggal' => date('Y-m-d'),
                'keterangan' => $data->keterangan,
                'jenis_penjualan' => $data->jenis_penjualan,
                'nomer_pesanan' => $data->nomer_pesanan,
                'resi' => $data->resi,
                'ekspedisi' => $data->ekspedisi,
                'penerima' => $data->penerima,
                'alamat_penerima' => $data->alamat_penerima,
                'status_proses' => $data->status_proses,
            ]);

            // insert data rincian pengiriman
            foreach ($data->rincian as $rinci) {
                PengirimanPenjualanRinci::create([
                    'penjualan_pengiriman_id' => $pengiriman->id,
                    'produk_id' => $rinci->produk_id,
                    'kuantitas' => $rinci->kuantitas,
                    'harga_produk' => $rinci->harga_produk,
                    'catatan' => $rinci->catatan,
                    'subtotal' => $rinci->subtotal,
                ]);
            }

            return $pengiriman;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    // 
    public function prosesKirim(Request $request)
    {
        if (auth()->user()->cannot('create', Penjualan_DO::class)) abort('403', 'access denied');

        $data = Pesanan::find($request->pesanan);
        $data->status_proses = 1;
        $data->save();

        // Duplikat data pesanan penjualan ke tabel pengiriman pesanan
        $result = $this->store($data);

        // Insert log aktifitas & kirim notif telegram
        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat Pengiriman Penjualan dengan nomer : ' . $result->nomer_pengiriman_penjualan
        ]);

        // Kirim notifikasi ke telegram
        $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v2/sendNotifTelegram';
        $text = "<strong>DELIVERY ORDER BARU</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $result->nomer_pengiriman_penjualan . "</strong> \n"
            . "Jenis penjualan : <strong>" . $result->jenis_penjualan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $data->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($result->tanggal)) . "</strong> \n"
            . "Dibuat oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"
            . "v2\n";

        $body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'text' => $text,
            'grup_id' => config('telegram_config.telegram_group_id_sales')
        ];
        $response = Http::post($urlApiTelegram, $body);

        return redirect()->back()->with('sukses', 'DATA PESANAN PENJUALAN BERHASIL DIPROSES, SILAHKAN PRINT SJ/DO');
    }

    public function printSj(PengirimanPenjualan $pengiriman_penjualan)
    {
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.penjualan.pengiriman.print-sj', [
                'pengiriman_penjualan' => $pengiriman_penjualan,
                'pengiriman_penjualan_rinci' => PengirimanPenjualanRinci::with('produk')->where('penjualan_pengiriman_id', $pengiriman_penjualan->id)->get(),
            ]);

        return $pdf->stream();
    }

    public function downloadExcel(Request $request)
    {
        if (auth()->user()->cannot('view', Penjualan_DO::class)) abort('403', 'access denied');

        $pengiriman_penjualan = PengirimanPenjualan::with('rincian','pelanggan','pesanan')
                ->whereBetween('tanggal', [$request->dari_tanggal,$request->sampai_tanggal])
                ->get();

        return Excel::download(new PengirimanPenjualanExport($pengiriman_penjualan), 'daftar-pengiriman-penjualan.xlsx');
    }
}
