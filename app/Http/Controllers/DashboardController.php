<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Pmtpembelian,
    Popembelian,
    Penjualan_Invoice,
    Penjualan_Invoice_Rinci,
    Pelanggan,
    Barang as Produk,
    Penjualan_SO,
    Penjualan_SO_rinci,
    fakturpembelian,
    Employees,
    JenisPenjualan
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil jumlah invoice
        //$sales = Penjualan_Invoice::all()->count();
        $sales = Penjualan_SO::all()->count();

        // Ambil jumlah customer / pelanggan
        $customers = Pelanggan::all()->count();

        // Ambil jumlah produk
        $products = Produk::all()->count();

        // Ambil jumlah omset berdasarkan Invoice
        $revenue = 0;
        $rinci_penjualan = Penjualan_Invoice_Rinci::all();
        foreach($rinci_penjualan as $value){
            $total = $value->harga * $value->qty;
            $subtotal = $total - ($total * $value->dsc / 100) - $value->diskon_nominal - $value->potongan_admin + $value->cashback_ongkir;
            $revenue += $subtotal;
        }
        $revenue = 0; // utk sementara, nanti dihapus
        
        $jenis_penjualan = JenisPenjualan::all();
        $index = 0;
        foreach($jenis_penjualan as $jenis){
            $jenis_penjualan[$index]->count = Penjualan_SO::where('jenis_penjualan', $jenis->id)->count();
            $index++;
        }

        $pmtpembelian = Pmtpembelian::where(['approve_direktur' => 0])->get();

        $popembelian = Popembelian::all()->count() - Popembelian::where(['approve_direktur' => 1, 'approve_komisaris' => 1])->count();

        $fakturpembelian = count(fakturpembelian::where(['approve_direktur' => 0, 'approve_komisaris' => 0])->get());

        //employee
        $employee = Employees::all();

        $po_running = Popembelian::all()->count();
        $po_ri = Popembelian::where('status', '!=', 2)->count();
        $so_do = Penjualan_SO::where('status_do', '!=',2)->count();
        
        return view('dashboard.index', compact(
            'pmtpembelian',
            'popembelian',
            'sales',
            'customers',
            'products',
            'fakturpembelian',
            'revenue',
            'employee',
            'jenis_penjualan',
            'po_running',
            'po_ri',
            'so_do'
        ));
    }

    public function getOmset(Request $request)
    {
        if (!empty($request->tanggal_awal) && !empty($request->tanggal_akhir)) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $omset = Penjualan_Invoice::selectRaw('
                penjualan_invoice.tanggal, penjualan_invoice_rinci.`qty`, penjualan_invoice_rinci.`harga`, penjualan_invoice_rinci.`dsc`, 
                SUM(ROUND((penjualan_invoice_rinci.harga - (penjualan_invoice_rinci.harga * (penjualan_invoice_rinci.dsc / 100))) * penjualan_invoice_rinci.`qty`, 0)) AS sub_total
                ')
                ->join('penjualan_invoice_rinci', 'penjualan_invoice.id', 'penjualan_invoice_rinci.penjualan_invoice_id')
                ->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                    $query->whereBetween('tanggal', array($tanggalAwal, $tanggalAkhir));
                })
                ->groupBy('tanggal')
                ->get();
        } else {
            //omset penjualan
            $now = Carbon::now();
            $lastWeekDate = Carbon::now()->subDays(7);

            $omset = Penjualan_Invoice::selectRaw('
                penjualan_invoice.tanggal, penjualan_invoice_rinci.`qty`, penjualan_invoice_rinci.`harga`, penjualan_invoice_rinci.`dsc`, 
                SUM(ROUND((penjualan_invoice_rinci.harga - (penjualan_invoice_rinci.harga * (penjualan_invoice_rinci.dsc / 100))) * penjualan_invoice_rinci.`qty`, 0)) AS sub_total
                ')
                ->join('penjualan_invoice_rinci', 'penjualan_invoice.id', 'penjualan_invoice_rinci.penjualan_invoice_id')
                ->where(function ($query) use ($lastWeekDate, $now) {
                    $query->whereBetween('tanggal', array($lastWeekDate, $now));
                })
                ->groupBy('tanggal')
                ->get();
        }

        //Data manipulation ubah total menjadi rupiah
        $omset = $omset->map(function ($val) {
            $val['sub_total_custom'] = rupiah($val->sub_total);
            $val['tanggal_custom'] = date('d/M', strtotime($val->tanggal));

            return $val;
        });

        return response()->json($omset);
    }

    public function filterrevenue($month,$year){
        
        if($month != 0){
            if($year != 0){
                $data = Penjualan_Invoice_Rinci::whereYear('created_at' , $year)->whereMonth('created_at' , $month)->get();
            }else{
                $data = Penjualan_Invoice_Rinci::whereMonth('created_at' , $month)->get();
            }
        }else{
            if($year != 0){
                $data = Penjualan_Invoice_Rinci::whereYear('created_at' , $year)->get();
            }else{
                $data = Penjualan_Invoice_Rinci::all();
            }
        }
        $revenue = 0;
        foreach($data as $value){
            $total = $value->harga * $value->qty;
            $subtotal = $total - ($total * $value->dsc / 100) - $value->diskon_nominal - $value->potongan_admin + $value->cashback_ongkir;
            $revenue += $subtotal;
        }

        return response()->json(number_format($revenue));
    }
}
