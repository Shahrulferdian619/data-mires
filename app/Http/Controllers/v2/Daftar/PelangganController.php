<?php

namespace App\Http\Controllers\v2\Daftar;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Pelanggan as MasterPelanggan;

class PelangganController extends Controller
{
    public function getPelanggan($id)
    {
        $pelanggan = MasterPelanggan::findOrFail($id);

        return response()->json($pelanggan, 200);
    }
}
