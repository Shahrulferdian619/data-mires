<?php

namespace App\Exports;

use App\Models\Gl;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExportAllBookBank implements FromView
{
    
    public $sumber;
    public $from;
    public $to;

    public function __construct($sumber, $from, $to)
    {
        $this->sumber = $sumber;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $gl = Gl::distinct()->get('nomer');
        $final_data = [];
        foreach ($gl as $key => $value) {
            $newGL = Gl::where(['nomer' => $value->nomer, 'sumber' => $this->sumber])->whereBetween('created_at', [$this->from, $this->to])->get();
            if(count($newGL) > 0){
                $details = [];
                foreach ($newGL as $keyItem => $item) {
                    
                    array_push($details, [
                        'nomer_akun' => $item->coa_no,
                        'nama_akun' => $item->coa,
                        'debit' => $item->debit,
                        'kredit' => $item->kredit
                    ]);
                }
                array_push($final_data, [
                    'nomer' => $newGL[0]->nomer,
                    'tahun' => $newGL[0]->tahun,
                    'tanggal' => $newGL[0]->tanggal,
                    'detail' => $details
                ]);
            }
        }

        $data = [
            'gl' => $final_data
        ];
        return view('kasbank.excel-all', $data);
    }

}
