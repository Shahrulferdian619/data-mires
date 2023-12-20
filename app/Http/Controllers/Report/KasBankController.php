<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\BukuBankRinci;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class KasBankController extends Controller
{
    public function index()
    {
        return view('report.kasbank.index');
    }

    public function arusKas(Request $request)
    {
        $rinci = DB::select('
        SELECT a.`sumber`, a.`tanggal`, b.`memo`, IF(b.tipe="D", b.nominal, NULL) AS debit,
           IF(b.tipe="K", b.nominal, NULL) AS kredit, b.`tipe`, b.`coa_id` as coa_id_rinci,
           (SELECT coa_id FROM buku_bank_rinci 
            JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
            JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
            WHERE `buku_bank_id` = a.`id`
            AND `coatype`.`id` = 1
            LIMIT 1
            ) AS coa_id_bank
           FROM buku_bank AS a
           JOIN buku_bank_rinci AS b ON a.`id` = b.`buku_bank_id`
           JOIN coa AS c ON b.`coa_id` = c.`id`
           JOIN coatype AS d ON c.`id_coatype` = d.`id`
           WHERE a.`is_deleted` = 1
           AND d.`id` != 1
           and a.tanggal BETWEEN "'.$request->start.'" AND "'.$request->end.'"
           ORDER BY coa_id_bank, b.`coa_id`
        ');

        $coaRinci = DB::select('
            SELECT
            (SELECT coa_id FROM buku_bank_rinci 
            JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
            JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
            WHERE `buku_bank_id` = a.`id`
            AND `coatype`.`id` = 1
            LIMIT 1
            ) AS coa_id_bank, b.`coa_id` AS coa_id_rinci, d.`tipecoa`, d.`id` AS tipecoa_id, c.`nama_coa`
            FROM buku_bank AS a
            JOIN buku_bank_rinci AS b ON a.`id` = b.`buku_bank_id`
            JOIN coa AS c ON b.`coa_id` = c.`id`
            JOIN coatype AS d ON c.`id_coatype` = d.`id`
            WHERE is_deleted = 1
            AND d.id != 1
            and a.tanggal BETWEEN "'.$request->start.'" AND "'.$request->end.'"
            GROUP BY coa_id_rinci, coa_id_bank
            ORDER BY coa_id_bank, coa_id_rinci
        ');

        // dd($coaRinci);

        $coa = Coa::join('buku_bank_rinci', 'coa.id', 'buku_bank_rinci.coa_id')
                ->whereIdCoatype(1)->whereIsActive(1)->orderBy('coa.nomer_coa')
                ->groupBy('buku_bank_rinci.coa_id')
                ->get();

        $start = $request->start;
        $end = $request->end;

        // $pdf = PDF::loadView('report.bukubesar.print.rinci', compact('rinci', 'akun', 'start', 'end'));
        $pdf = PDF::loadView('report.kasbank.print.aruskas', compact('rinci', 'coaRinci', 'coa', 'start', 'end'));
        return $pdf->stream('Arus Kas per Akun.pdf');
    }

    public function bukuBank(Request $request)
    {
        $rinci = DB::select('
            SELECT a.nomer, a.sumber, a.deskripsi AS memo, a.tanggal,
            (SELECT SUM(nominal) FROM buku_bank_rinci 
            JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
            JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
            WHERE `buku_bank_id` = a.`id`
            AND `coatype`.`id` = 1
            AND tipe = "D"
            ) AS debit,
            (SELECT SUM(nominal) FROM buku_bank_rinci 
            JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
            JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
            WHERE `buku_bank_id` = a.`id`
            AND `coatype`.`id` = 1
            AND tipe = "K"
            ) AS kredit,
            (SELECT coa_id FROM buku_bank_rinci 
            JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
            JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
            WHERE `buku_bank_id` = a.`id`
            AND `coatype`.`id` = 1
            LIMIT 1
            ) AS coa_id
            FROM buku_bank AS a
            WHERE is_deleted = 1
            and a.tanggal BETWEEN "'.$request->start.'" AND "'.$request->end.'"
            ORDER BY sumber
        ');

        $coa = Coa::join('buku_bank_rinci', 'coa.id', 'buku_bank_rinci.coa_id')
                ->whereIdCoatype(1)->whereIsActive(1)->orderBy('coa.nomer_coa')
                ->groupBy('buku_bank_rinci.coa_id')
                ->get();

        $start = $request->start;
        $end = $request->end;

        $pdf = PDF::loadView('report.kasbank.print.bukubank', compact('rinci', 'coa', 'start', 'end'));
        return $pdf->stream('Buku Bank.pdf');
        
    }
}
