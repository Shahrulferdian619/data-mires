<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Penjualan_Invoice;
use App\Models\Penjualan_Invoice_Rinci;
use App\Models\PenjualanCR;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    //

    public function index()
    {
        $pelanggan = Pelanggan::all();
        $getInvoice = Penjualan_Invoice::with('pelanggan')->get();
        $invoice = $this->filterDataInvoice($getInvoice);
        $data = [
            'pelanggan' => $pelanggan,
            'invoice' => $invoice
        ];
        return view('report.sales-order.index', $data);
    }

    public function salesPerCustomer(Request $request)
    {

        $final_data = [];

        $filter_customer = $request->pelanggan_id;
        $filter_from = date($request->start);
        $filter_to = date($request->end);
        $filter_time = $request->filter_time;

        $distinct_customer = Penjualan_Invoice::whereIn('pelanggan_id', $filter_customer)->distinct()->get('pelanggan_id');

        foreach ($distinct_customer as $key => $value) {
            
            $sales = [];

            if(is_null($filter_time)){
                $data_sales = Penjualan_Invoice::whereBetween('created_at', [$filter_from, $filter_to]);
            }else{
                $data_sales = Penjualan_Invoice::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
            }

            $data_sales = $data_sales->where('pelanggan_id', $value->pelanggan_id)->get();
            foreach($data_sales as $valueSales){
                
                $detail_sales = [];
                
                if(is_null($filter_time)){
                    $data_details = Penjualan_Invoice_Rinci::whereBetween('created_at', [$filter_from, $filter_to]);
                }else{
                    $data_details = Penjualan_Invoice_Rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
                }

                $data_details = $data_details->where('penjualan_invoice_id', $valueSales->id)->get();

                foreach($data_details as $valueDetail){

                    array_push($detail_sales, [
                        'item_id' => $valueDetail->barang_id,
                        'item_name' => $valueDetail->barang->nama_barang,
                        'item_quantity' => $valueDetail->qty,
                        'item_price' => $valueDetail->harga,
                        'item_discount' => $valueDetail->dsc,
                        'item_discount_nominal' => $valueDetail->diskon_nominal,
                        'item_discount_admin' => $valueDetail->potongan_admin,
                        'item_cashback' => $valueDetail->cashback_ongkir
                    ]);

                }

                array_push($sales, [
                    'invoice_number' => $valueSales->nomer_invoice,
                    'date' => $this->getDay($valueSales->tanggal),
                    'status_payment' => $valueSales->is_payment,
                    'details' => $detail_sales,
                ]);

            }

            array_push($final_data, [
                'customer_id' => $value->pelanggan_id,
                'customer_name' => Pelanggan::find($value->pelanggan_id)->nama_pelanggan,
                'sales' => $sales
            ]);

        }

        $date_from = $filter_from;
        if($filter_time != ''){
            $date_from = Carbon::now()->subDays($filter_time)->toDateString();
        }

        $data = [
            'form' => $date_from,
            'to' => $filter_to,
            'data' => $final_data,
        ];

        // return response()->json($final_data);
        $pdf = PDF::loadView('report.sales-order.print.per-customer', $data);
        return $pdf->stream('Laporan Penjualan Per Pelanggan.pdf');
    }

    public function salesPerItem(Request $request)
    {

        $filter_from = date($request->start);
        $filter_to = date($request->end);
        $filter_time = $request->filter_time;
        $filter_column = $request->filter_column;

        $items = [];

        if(is_null($filter_time)){
            $item_distinct = Penjualan_Invoice_Rinci::whereBetween('created_at', [$filter_from, $filter_to]);
        }else{
            $item_distinct = Penjualan_Invoice_Rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
        }
        $item_distinct = $item_distinct->distinct()->get('barang_id');

        foreach ($item_distinct as $key => $value) {
            if(is_null($filter_time)){
                $item_quantity = Penjualan_Invoice_Rinci::whereBetween('created_at', [$filter_from, $filter_to])->where('barang_id', $value->barang_id)->sum('qty');
                $item_price = Penjualan_Invoice_Rinci::whereBetween('created_at', [$filter_from, $filter_to])->where('barang_id', $value->barang_id)->sum('harga');
            }else{
                $item_quantity = Penjualan_Invoice_Rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString())->where('barang_id', $value->barang_id)->sum('qty');
                $item_price = Penjualan_Invoice_Rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString())->where('barang_id', $value->barang_id)->sum('harga');
            }

            array_push($items, [
                'item_id' => $value->barang_id,
                'item_name' => $value->barang->nama_barang,
                'item_code' => $value->barang->kode_barang,
                'item_quantity' => $item_quantity,
                'item_price' => $item_price
            ]);

        }

        $date_from = $filter_from;
        if(!is_null($filter_time)){
            $date_from = Carbon::now()->subDays($filter_time)->toDateString();
        }

        // return response()->json($items);
        $data = [
            'order' => $items,
            'form' => $date_from,
            'to' => $filter_to,
            'column' => $filter_column
        ];

        $pdf = PDF::loadView('report.sales-order.print.per-item', $data);
        return $pdf->stream('Laporan Penjualan Per Barang.pdf');
    }

    public function payment(Request $request){
        
        $final_data = [];

        $filter_customer = $request->pelanggan_id;
        $filter_from = date($request->start);
        $filter_to = date($request->end);
        $filter_time = $request->filter_time;

        $distinct_customer = PenjualanCR::whereIn('pelanggan_id', $filter_customer)->distinct()->get('pelanggan_id');

        foreach ($distinct_customer as $key => $value) {
            
            $data_cr = [];
            $distinct_cr = PenjualanCR::where('pelanggan_id', $value->pelanggan_id)->distinct()->get('invoice_id');

            foreach($distinct_cr as $valueCR){

                $detail_payment = [];

                if(is_null($filter_time)){
                    $data_detail = PenjualanCR::whereBetween('created_at', [$filter_from, $filter_to]);
                }else{
                    $data_detail = PenjualanCR::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
                }

                $data_detail = $data_detail->where('invoice_id', $valueCR->invoice_id)->get();

                foreach($data_detail as $valueDetail){

                    array_push($detail_payment, [
                        'customer_receipt_number' => $valueDetail->nomer_cr,
                        'date' => $this->getDay($valueDetail->tanggal_cr),
                        'payment' => $valueDetail->total_payment
                    ]);

                }

                array_push($data_cr, [
                    'invoice_id' => $valueCR->invoice_id,
                    'invoice_number' => Penjualan_Invoice::find($valueCR->invoice_id)->nomer_invoice,
                    'status_payment' => Penjualan_Invoice::find($valueCR->invoice_id)->is_payment,
                    'detail_payment' => $detail_payment
                ]);

            }

            array_push($final_data, [
                'customer_id' => $value->pelanggan_id,
                'customer_name' => Pelanggan::find($value->pelanggan_id)->nama_pelanggan,
                'customer_receipts' => $data_cr
            ]);

        }

        $date_from = $filter_from;
        if($filter_time != ''){
            $date_from = Carbon::now()->subDays($filter_time)->toDateString();
        }

        $data = [
            'form' => $date_from,
            'to' => $filter_to,
            'data' => $final_data,
        ];

        // return response()->json($final_data);
        $pdf = PDF::loadView('report.sales-order.print.payment', $data);
        return $pdf->stream('Laporan Pembayaran Pelanggan.pdf');

    }
}
