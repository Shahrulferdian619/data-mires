<?php

namespace App\Http\Controllers\v2\Persediaan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function getHargaProduk($id)
    {
        $hargaProduk = Barang::find($id)->harga_barang1;

        return response()->json($hargaProduk, 200);
    }
}
