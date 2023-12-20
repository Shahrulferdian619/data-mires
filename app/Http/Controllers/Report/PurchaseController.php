<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\fakturpembelian;
use App\Models\fakturpembelian_rinci;
use App\Models\Payment;
use App\Models\PaymentToFaktur;
use App\Models\Pmtpembelian;
use App\Models\Popembelian;
use App\Models\Popembelian_rinci;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    //

    public function index()
    {
        // $vendor = fakturpembelian::distinct()->get('supplier_id');
        // $countAllPurchase = fakturpembelian::count();
        // $dataPercentagePervendor = [];
        // foreach ($vendor as $key => $value) {
        //     $countPerVendor = fakturpembelian::where('supplier_id', $value->supplier_id)->count();
        //     array_push($dataPercentagePervendor, [
        //         'nama_vendor' => Supplier::find($value->supplier_id)->nama_supplier,
        //         'percentage' => number_format(($countPerVendor / $countAllPurchase) * 100, 2, '.', '')
        //     ]);
        // }
        $vendor = Supplier::all();
        $order_per_item_qty = fakturpembelian_rinci::with('faktur')->whereHas('faktur', function ($query) {
            return $query->where(['approve_direktur' => 1, 'approve_komisaris' => 1]);
        })->sum('qty');

        $order_per_item_price = fakturpembelian_rinci::with('faktur')->whereHas('faktur', function ($query) {
            return $query->where(['approve_direktur' => 1, 'approve_komisaris' => 1]);
        })->sum(DB::raw('qty * harga'));

        $order_per_item = fakturpembelian_rinci::with('faktur')->whereHas('faktur', function ($query) {
            return $query->where(['approve_direktur' => 1, 'approve_komisaris' => 1]);
        })->distinct()->count('barang_id');

        $request_not_approve = Pmtpembelian::where('approve_direktur', 0)->get()->count();

        $order_not_approve = Popembelian::where('approve_direktur', 0)->orWhere('approve_komisaris', 0)->get()->count();

        $order_approve = Popembelian::where(['approve_direktur' => 1, 'approve_komisaris' => 1])->get()->count();

        $order_per_pay = PaymentToFaktur::sum('jumlah_bayar');

        $data = [
            'order_per_vendor' => fakturpembelian::where(['approve_direktur' => 1, 'approve_komisaris' => 1])->distinct()->count('supplier_id'),
            'order_per_item_qty' => $order_per_item_qty,
            'order_per_item_price' => $order_per_item_price,
            'order_per_item' => $order_per_item,
            'request' => $request_not_approve,
            'order_not_approve' => $order_not_approve,
            'order_approve' => $order_approve,
            'order_per_pay' => $order_per_pay,
            'vendor' => $vendor,
            // 'percentage' => $dataPercentagePervendor
        ];
        return view('report.purchase-order.index', $data);
    }

    public function orderPerVendor(Request $request)
    {

        // final output data
        $final_data = [];

        // input filter
        $filter_supplier = $request->supplier_id;

        // filter date or all
        $filter_from = date($request->start);
        $filter_to = date($request->end);
        $filter_time = $request->filter_time;

        // distinct tiap supplier
        $distinct_supplier = fakturpembelian::whereIn('supplier_id', $filter_supplier)->distinct()->get('supplier_id');
        // input final data 
        foreach ($distinct_supplier as $key => $value) {
            
            $order = [];

            if($filter_time == ''){
                $data_order = fakturpembelian::whereBetween('created_at', [$filter_from, $filter_to]);
            }else{
                $data_order = fakturpembelian::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
            }

            foreach ($data_order->where('supplier_id', $value->supplier_id)->get() as $OrderKey => $OrderValue) {
                
                $detail = [];
                
                if($filter_time == ''){
                    $data_detail = fakturpembelian_rinci::whereBetween('created_at', [$filter_from, $filter_to]);
                }else{
                    $data_detail = fakturpembelian_rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
                }
                // buat data detail order
                foreach ($data_detail->where('fakturpembelian_id', $OrderValue->id)->get() as $DetailKey => $DetailValue) {
                    array_push($detail, [
                        'item_id' => $DetailValue->barang_id,
                        'item_name' => $DetailValue->barang->nama_barang,
                        'item_code' => $DetailValue->barang->kode_barang,
                        'item_qty' => $DetailValue->qty,
                        'item_price' => $DetailValue->harga,
                        'item_dsc' => $DetailValue->dsc,
                        'item_description' => $DetailValue->description
                    ]);
                }
                
                array_push($order, [
                    'faktur_number' => $OrderValue->nomer_fakturpembelian,
                    'date' => $this->getDay($OrderValue->tanggal),
                    'detail' => $detail
                ]);
            }

            array_push($final_data, [
                'supplier_id' => $value->supplier_id,
                'supplier_name' => Supplier::find($value->supplier_id)->nama_supplier,
                'order' => $order
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
        $pdf = PDF::loadView('report.purchase-order.print.order-per-vendor', $data);
        return $pdf->stream('Laporan Per Vendor.pdf');
    }

    public function orderPerItem(Request $request)
    {
        // variable awal
        $filter_from = date($request->start);
        $filter_to = date($request->end);
        $filter_time = $request->filter_time;
        $filter_column = $request->filter_column;
        $items = [];

        // distinct berdasarkan item
        if(is_null($filter_time)){
            $item_distinct = fakturpembelian_rinci::whereBetween('created_at', [$filter_from, $filter_to]);
        }else{
            $item_distinct = fakturpembelian_rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
        }

        $item_distinct = $item_distinct->distinct()->get('barang_id');

        foreach ($item_distinct as $key => $value) {

            if(is_null($filter_time)){
                $qty = fakturpembelian_rinci::whereBetween('created_at', [$filter_from, $filter_to])->where('barang_id', $value->barang_id)->sum('qty');
                $price = fakturpembelian_rinci::whereBetween('created_at', [$filter_from, $filter_to])->where('barang_id', $value->barang_id)->sum('harga');
            }else{
                $qty = fakturpembelian_rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString())->where('barang_id', $value->barang_id)->sum('qty');
                $price = fakturpembelian_rinci::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString())->where('barang_id', $value->barang_id)->sum('harga');
            }

            array_push($items, [
                'item_id' => $value->barang_id,
                'item_name' => $value->barang->nama_barang,
                'item_code' => $value->barang->kode_barang,
                'item_quantity' => $qty,
                'item_price' => $price
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
        $pdf = PDF::loadView('report.purchase-order.print.order-per-item', $data);
        return $pdf->stream('Laporan Per Barang.pdf');
    }

    public function payment(Request $request){

        $final_data = [];

        $filter_supplier = $request->supplier_id;
        $filter_from = date($request->start);
        $filter_to = date($request->end);
        $filter_time = $request->filter_time;

        $distinct_supplier = Payment::whereIn('supplier_id', $filter_supplier)->distinct()->get('supplier_id');

        foreach ($distinct_supplier as $key => $value) {
            $payments = [];

            if(is_null($filter_time)){
                $data_payment = Payment::whereBetween('created_at', [$filter_from, $filter_to]);
            }else{
                $data_payment = Payment::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
            }

            $data_payment = $data_payment->where('supplier_id', $value->supplier_id)->get();
            foreach($data_payment as $keyPayment => $valuePayment){

                $payment_detail = [];

                if(is_null($filter_time)){
                    $detail_payment = PaymentToFaktur::whereBetween('created_at', [$filter_from, $filter_to]);
                }else{
                    $detail_payment = PaymentToFaktur::where('created_at', '>=',Carbon::now()->subDays($filter_time)->toDateString())->where('created_at', '<=', Carbon::now()->addDay(1)->toDateString());
                }

                $detail_payment = $detail_payment->where('payment_id', $valuePayment->id)->get();
                foreach($detail_payment as $keyDetail => $valueDetail){
                    array_push($payment_detail, $valueDetail->jumlah_bayar);
                }

                array_push($payments, [
                    'payment_number' => $valuePayment->nomer_payment,
                    'total_invoice' => $valuePayment->jumlah_tagihan,
                    'date' => $this->getDay($valuePayment->tanggal),
                    'payment_details' => $payment_detail
                ]);

            }

            array_push($final_data, [
                'supplier_id' => $value->supplier_id,
                'supplier_name' => Supplier::find($value->supplier_id)->nama_supplier,
                'payments' => $payments
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

        $pdf = PDF::loadView('report.purchase-order.print.payment', $data);
        return $pdf->stream('Laporan Pembayaran.pdf');

    }

}
