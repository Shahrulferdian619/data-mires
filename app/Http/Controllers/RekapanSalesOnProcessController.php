<?php

namespace App\Http\Controllers;

use App\Models\Penjualan_SO;
use App\Notifications\SendNotificationSalesTelegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapanSalesOnProcessController extends Controller
{
    // Generate Rekapan Bulanan Setiap 3 hari sekali pada bulan berjalan
    public function index()
    {
        $soTercetakBulan = 0;
        $soBelumDikirimBulan = 0;
        $doBelumInvoiceBulan = 0;

        $getBulan = date('m');
        $awalBulan = '2023-'.$getBulan.'01';
        $currentDate = date('Y-m-d');

        $soTercetakBulan = Penjualan_SO::whereBetween('so_tanggal', [$awalBulan, $currentDate])
                            ->count();
        $soBelumDikirimBulan = Penjualan_SO::whereBetween('so_tanggal', [$awalBulan, $currentDate])
                            ->where('status_do', 0)
                            ->count();
        $doBelumInvoiceBulan = Penjualan_SO::whereBetween('so_tanggal', [$awalBulan, $currentDate])
                            ->where('status_invoice', 0)
                            ->count();

        // Kirim notifikasi ke Grup Mires Mahisa via Telegram
        Auth::user()->notify(new SendNotificationSalesTelegram([
            'text' => '*INFORMASI REKAPAN BULAN INI* per Hari ini, Jumlah Sales Order adalah sebanyak : *'
                        .$soTercetakBulan.'*, Sales Order yang belum terkirim adalah sebanyak : *'
                        .$soBelumDikirimBulan.'*, dan Delivery Order belum dibuatkan Invoice adalah sebanyak : *'
                        .$doBelumInvoiceBulan.'*. Sekian informasi rekapan Bulan Ini. Terima Kasih'
        ]));
    }
}
