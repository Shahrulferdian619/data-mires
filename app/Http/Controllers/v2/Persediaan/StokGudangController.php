<?php

namespace App\Http\Controllers\v2\Persediaan;

use App\Exports\PersediaanExport;
use App\Http\Controllers\Controller;
use App\Models\v2\Persediaan\StokGudang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StokGudangController extends Controller
{
    public function index()
    {
        return view('v2.persediaan.stok',[
            'stok_produk' => StokGudang::orderBy('gudang_id','asc')->get(),
        ]);
    }

    public function downloadExcel(Request $request)
    {
        $persediaan = StokGudang::orderBy('nama_gudang','desc')->get();

        return Excel::download(new PersediaanExport($persediaan), 'stok_persediaan.xlsx');
    }
}
