<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PengirimanPenjualanExport implements FromView, ShouldAutoSize
{
    protected $pengiriman_penjualan;

    public function __construct($pengiriman_penjualan)
    {
        $this->pengiriman_penjualan = $pengiriman_penjualan;
    }

    public function view(): View
    {
        return view('v2.penjualan.pengiriman.excel', [
            'pengiriman_penjualan' => $this->pengiriman_penjualan
        ]);
    }
}