<?php

namespace App\Exports;

use App\Models\Gl;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportBankBook implements FromView
{

    public $nomer;

    public function __construct($nomer)
    {
        $this->nomer = $nomer;
    }

    public function view(): View
    {
        return view('kasbank.excel', [
            'gl' => Gl::where('nomer', $this->nomer)->get()
        ]);
    }
}
