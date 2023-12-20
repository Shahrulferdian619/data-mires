<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KonsinyasiExport implements FromView, ShouldAutoSize
{
    protected $konsinyasi;

    public function __construct($konsinyasi)
    {
        $this->konsinyasi = $konsinyasi;
    }

    public function view(): View
    {
        return view('v2.penjualan.konsinyasi.excel', [
            'konsinyasi' => $this->konsinyasi
        ]);
    }
}
