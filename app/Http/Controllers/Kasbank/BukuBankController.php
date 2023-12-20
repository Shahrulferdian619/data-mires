<?php

namespace App\Http\Controllers\Kasbank;

use App\Http\Controllers\Controller;
use App\Models\BukuBankRinci;
use App\Models\Coa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class BukuBankController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', BukuBankRinci::class)) abort(403, 'access denied');

        $akun = BukuBankRinci::with('coa')
                ->join('coa', 'coa.id', 'buku_bank_rinci.coa_id')
                ->join('coatype', 'coa.id_coatype', 'coatype.id')
                ->whereIn('coatype.id', [1]) // ubah ini untuk menampilkan report akun
                ->groupBy('coa_id')->get();

        return view('kasbank.bukubank.index', compact('akun'));
    }

    public function getData(Request $request)
    {
        if(auth()->user()->cannot('view', BukuBankRinci::class)) abort(403, 'access denied');

        if (!empty($request->dari_tanggal) && !empty($request->sampai_tanggal) && !empty($request->coa_id)) {
               $bukuBankRinci = DB::select('
                    SELECT 
                    "---" AS nomer, "SA" AS sumber, "Saldo Awal" AS memo,
                    coa.created_at AS tanggal, 0 AS debit, 0 AS kredit, saldo_awal AS nominal_balance, coa.`id` AS coa_id FROM `coa`
                    WHERE coa.`id` = "'.$request->coa_id.'"
                    UNION
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
                    CAST((@balance := @balance + COALESCE((SELECT debit), 0) - COALESCE((SELECT kredit), 0))
                    AS DECIMAL(16, 2)) AS nominal_balance,
                    (SELECT coa_id FROM buku_bank_rinci 
                    JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
                    JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
                    WHERE `buku_bank_id` = a.`id`
                    AND `coatype`.`id` = 1
                    LIMIT 1
                    ) AS coa_id
                    FROM buku_bank AS a
                    JOIN (SELECT @balance := 0) AS tmp
                    where (SELECT coa_id FROM buku_bank_rinci 
                    JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
                    JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
                    WHERE `buku_bank_id` = a.`id`
                    AND `coatype`.`id` = 1
                    LIMIT 1
                    ) = "'.$request->coa_id.'"
                    and a.tanggal BETWEEN "'.$request->dari_tanggal.'" AND "'.$request->sampai_tanggal.'"
                    AND is_deleted = 1
                ');
                        
        }else if (empty($request->dari_tanggal) && empty($request->sampai_tanggal) && !empty($request->coa_id)){
                $bukuBankRinci = DB::select('
                    SELECT 
                    "---" AS nomer, "SA" AS sumber, "Saldo Awal" AS memo,
                    coa.created_at AS tanggal, 0 AS debit, 0 AS kredit, saldo_awal AS nominal_balance, coa.`id` AS coa_id FROM `coa`
                    WHERE coa.`id` = "'.$request->coa_id.'"
                    UNION
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
                    CAST((@balance := @balance + COALESCE((SELECT debit), 0) - COALESCE((SELECT kredit), 0))
                    AS DECIMAL(16, 2)) AS nominal_balance,
                    (SELECT coa_id FROM buku_bank_rinci 
                    JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
                    JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
                    WHERE `buku_bank_id` = a.`id`
                    AND `coatype`.`id` = 1
                    LIMIT 1
                    ) AS coa_id
                    FROM buku_bank AS a
                    JOIN (SELECT @balance := 0) AS tmp
                    where (SELECT coa_id FROM buku_bank_rinci 
                    JOIN coa ON buku_bank_rinci.`coa_id` = coa.`id`
                    JOIN `coatype` ON coa.`id_coatype` = coatype.`id`
                    WHERE `buku_bank_id` = a.`id`
                    AND `coatype`.`id` = 1
                    LIMIT 1
                    ) = "'.$request->coa_id.'"
                    AND is_deleted = 1
                ');
                
        }else{
            
            $bukuBankRinci = DB::select('
            SELECT 
            "---" AS nomer, "SA" AS sumber, "Saldo Awal" AS memo,
            coa.created_at AS tanggal, 0 AS debit, 0 AS kredit, saldo_awal AS nominal_balance, coa.`id` AS coa_id FROM `coa`
            WHERE coa.`id` = "999999999"'); // set default null
        }
        //yajra setup
        if ($request->ajax()) {
            return DataTables()->of($bukuBankRinci)
                ->addColumn('debit', function ($val) {
                    $result = !empty($val->debit) ? rupiah($val->debit) : "-"; // GET DEBIT

                    return $result;
                })
                ->addColumn('tanggal_indonesia', function ($val) {
                    $result = tanggal($val->tanggal); // GET TANGGAL INDO

                    return $result;
                })
                ->addColumn('kredit', function ($val) {
                    $result = !empty($val->kredit) ? rupiah($val->kredit) : "-";  // GET KREDIT

                    return $result;
                })
                ->addColumn('balance', function ($val) {
                    $result = rupiah($val->nominal_balance); 
                
                    return $result;
                })
                ->editColumn('action', function ($val) {

                    if($val->sumber == "JV"){
                        $btn = '<a href="/admin/jurnal-voucher/show/'.$val->nomer.'" class="badge badge-light-secondary">
                            Lihat
                        </a>';
                    }elseif($val->sumber == "PMT"){
                        $btn = '<a href="/admin/pembayaran/show/'.$val->nomer.'" class="badge badge-light-secondary">
                            Lihat
                        </a>';
                    }elseif($val->sumber == "DPT"){
                        $btn = '<a href="/admin/penerimaan/show/'.$val->nomer.'" class="badge badge-light-secondary">
                            Lihat
                        </a>';
                    }else{
                        $btn = 'N/A';
                    }
                   
                
                    return $btn;
                })
                ->rawColumns(['debit', 'kredit', 'balance', 'tanggal_indonesia', 'action'])
                ->make(true);
        }
    }
}
