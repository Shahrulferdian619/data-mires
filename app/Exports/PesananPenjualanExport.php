<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PesananPenjualanExport implements FromView, ShouldAutoSize
{
    protected $pesanan_penjualan;

    public function __construct($pesanan_penjualan)
    {
        $this->pesanan_penjualan = $pesanan_penjualan;
    }

    public function view(): View
    {
        return view('v2.penjualan.pesanan.excel', [
            'pesanan_penjualan' => $this->pesanan_penjualan
        ]);
    }
}
