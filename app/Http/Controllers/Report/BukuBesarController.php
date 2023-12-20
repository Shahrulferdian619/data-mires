<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class BukuBesarController extends Controller
{
    public function index()
    {
        return view('report.bukubesar.index');
    }

    public function bukuBesarRinci(Request $request)
    {
        $rinci = DB::select('
            SELECT coa_id, tanggal, sumber, nomer, deskripsi, debit, kredit,
            CAST(@balance := @balance + COALESCE(debit, 0) - COALESCE(kredit, 0)
            AS DECIMAL(16, 2)) AS balance
            FROM buku_besar
            JOIN (SELECT @balance := 0) AS tmp
            WHERE is_deleted = 1
            and tanggal BETWEEN "'.$request->start.'" AND "'.$request->end.'"
            ORDER BY coa_id, tanggal
        ');

        $akun = Coa::with('tipeCoa')->whereIsActive(1)->orderBy('coa.nomer_coa')->get();

        $start = $request->start;
        $end = $request->end;

        $pdf = PDF::loadView('report.bukubesar.print.rinci', compact('rinci', 'akun', 'start', 'end'));
        return $pdf->stream('Buku Besar - Rinci.pdf');
    }
}
