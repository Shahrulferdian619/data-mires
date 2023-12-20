<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penjualan_Invoice;
use App\Models\Pelanggan;
use App\Models\Pmtpembelian_rinci;
use Illuminate\Http\Request;
use App\Models\Barang;

class APIController extends Controller
{
    //

    public function getPMTPembelianRinciById($id)
    {
        $data = Pmtpembelian_rinci::with('barang')->find($id);
        return response()->json($data);
    }

    public function pieChart(){


        $invoice = Penjualan_Invoice::with('pelanggan')->get();
        $data = $this->filterDataInvoice($invoice);

        return response()->json($data);

    }
    public function getDetailPelanggan($id){
        $pelanggan = Pelanggan::find($id);

        return response()->json($pelanggan);
    }
    public function getProduct($id){
        $product = Barang::find($id);

        return response()->json($product);
    }

}
