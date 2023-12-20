<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PersediaanExport implements FromView, ShouldAutoSize
{
    protected $persediaan;

    public function __construct($persediaan)
    {
        $this->persediaan = $persediaan;
    }

    public function view(): View
    {
        return view('v2.persediaan.excel', [
            'persediaan' => $this->persediaan
        ]);
    }
}
