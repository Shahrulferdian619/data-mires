<?php

namespace App\Http\Controllers\v2\BukuBesar;

use App\Http\Controllers\Controller;
use App\Models\v2\Bukubesar\Bukubesar;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $akses_id_granted = array(1, 2, 5, 6, 12, 13);
            $user_id = Auth::user()->id;
            if (in_array($user_id, $akses_id_granted, TRUE)) {
                return $next($request);
            } else {
                abort(403, 'akses ditolak');
            }
        });
    }
    public function index()
    {
        $data['bukubesar'] = Bukubesar::with('coa')->get();
        $data['total_debit'] = Bukubesar::where('tipe_mutasi', 'D')->sum('nominal');
        $data['total_kredit'] = Bukubesar::where('tipe_mutasi', 'K')->sum('nominal');

        return view('v2.bukubesar.laporan.index', compact('data'));
    }
}
