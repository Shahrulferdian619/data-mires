<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\Gl;
use App\Models\JenisPenjualan;
use App\Models\Penjualan_SO;
use App\Models\Penjualan_SO_rinci;
use App\Models\Penjualan_DO;
use App\Models\Penjualan_Invoice;
use App\Models\Penjualan_Invoice_Rinci;
use App\Notifications\SendNotificationSalesTelegram;
use PDF;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Carbon\Carbon;

class SiController extends Controller
{
    
    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Penjualan_Invoice::class)) abort('403', 'access denied');

        //ambil semua data transaksi SO
        $so = Penjualan_SO::where('status_do', 2)->get();

        if($request->ajax()){
            return datatables()->of($so)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/si/' . $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->addColumn('pelanggan', function($row){
                return $row->pelanggan->nama_pelanggan;
            })
            ->editColumn('no_pesanan', function ($row) {
                return $row->no_pesanan;
            })
            ->editColumn('so_tanggal', function ($row) {
                return $row->so_tanggal ? with(new Carbon($row->so_tanggal))->format('d-m-Y') : '';;
            })
            ->editColumn('jenis_penjualan', function($row) {
                $jenis_penjualan = JenisPenjualan::find($row->jenis_penjualan);
                return $jenis_penjualan->jenis_penjualan;
            })
            ->editColumn('status_invoice', function($row){
                if($row->status_invoice == 0){
                    $status_invoice = '<div style="width:120px" class="badge badge-light-warning">Belum Invoice</div>';
                }elseif($row->status_invoice == 1){
                    $status_invoice = '<div style="width:120px" class="badge badge-light-info">Dibuatkan Invoice</div>';
                }elseif($row->status_invoice == 2){
                    $status_invoice = '<div style="width:120px" class="badge badge-light-success">Sudah lunas</div>';
                }else{
                    $status_invoice = "-";
                }
                return $status_invoice;
            })
            ->rawColumns(['actions','so_tanggal','status_do','status_invoice'])->make(true);
        }

        return view('penjualan.si.index', compact('so'));
    }

    public function exportPDF()
	{
        $si = Penjualan_Invoice::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.si.exportpdf',compact('si'));
        return $pdf->download('Tagihan Penjualan.pdf');
	}

    public function printPDF()
	{
        $si = Penjualan_Invoice::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.si.exportpdf',compact('si'));
        return $pdf->stream();
	}

    public function create()
    {
        if(auth()->user()->cannot('create', Penjualan_Invoice::class)) abort('403', 'access denied');

        // Cek ada berapa si pada bulan ini
        $count_si = Penjualan_Invoice::whereMonth('tanggal', date('m'))->whereYear('tanggal', date('Y'))->count();
        $count = str_pad( $count_si+1, 4, "0", STR_PAD_LEFT );
        $nomer_si = 'MMG/'.date('y').'/'.date('m').'/'.$count;

        $pelanggan = [];
        $customer = Pelanggan::all();
        foreach($customer as $cust){
            if(Penjualan_SO::where(['id_pelanggan' => $cust->id])->where('status_invoice', '=', 0)->where('status_do', '!=', '0')->count() != 0){
                array_push($pelanggan, $cust);
            }
        }

        return view('penjualan.si.create', compact('pelanggan', 'nomer_si'));
    }

    public function get_so($id)
    {
        if(auth()->user()->cannot('view', Penjualan_Invoice::class)) abort('403', 'access denied');

        $so = Penjualan_SO::where(['id_pelanggan' => $id, 'status_invoice' => 0])->where('status_do', '!=', '0')->get();

        return response()->json(['data' => $so]);
    }

    public function get_so_rinci($id)
    {
        if(auth()->user()->cannot('view', Penjualan_Invoice::class)) abort('403', 'access denied');

        $data = Penjualan_SO_rinci::where(['id_so' => $id])->with('barang')->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Penjualan_Invoice::class)) abort('403', 'akses ditolak');
        
        $so = Penjualan_SO::find($request->id_so);
        // dd($so);

        // Insert Invoice 
        $invoice = new Penjualan_Invoice;
        $invoice->user_id = Auth::id();
        $invoice->pelanggan_id = $so->id_pelanggan;
        $invoice->so_id = $so->id;
        //$invoice->nomer_invoice = $so->so_nomer; <== JANGAN DIHAPUS

        // === Generate Nomer Invoice Otomatis dengan Format MMGTAHUNBULANNOMER (MMG20230200001) === //
        $getJumlahInvoiceBulanSekarang = Penjualan_Invoice::all()->count();
        $nomerInvoice = str_pad( $getJumlahInvoiceBulanSekarang+1, 5, "0", STR_PAD_LEFT );
        $invoice->nomer_invoice = "MMG".date('y').date('m').$nomerInvoice;
        // === End of Generate Nomer Invoice === //

        $invoice->tanggal = date('Y-m-d');
        $invoice->is_payment = 0;
        $invoice->save();

        $hpp = 0;
        $piutang_dagang = 0;
        $piutang_dagang_all = 0;
        $diskon = 0;
        $cashback_ongkir = 0;
        $potongan_admin = 0;

        $so_rinci = Penjualan_SO_Rinci::where('id_so', $request->id_so)->get();
        foreach ($so_rinci as $rinci) {
            $invoice_rinci = new Penjualan_Invoice_Rinci;
            $invoice_rinci->penjualan_invoice_id = $invoice->id;
            $invoice_rinci->barang_id = $rinci->id_barang;
            $invoice_rinci->qty = $rinci->qty_barang;
            $invoice_rinci->harga = $rinci->harga_barang;
            $invoice_rinci->dsc = $rinci->diskon_barang;
            $invoice_rinci->diskon_nominal = $rinci->diskon_nominal;
            $invoice_rinci->potongan_admin = $rinci->potongan_admin;
            $invoice_rinci->cashback_ongkir = $rinci->cashback_ongkir;
            $invoice_rinci->note = '-';
            $invoice_rinci->save();

            $hpp += Barang::find($rinci->id_barang)->hpp * $rinci->qty_barang;
            $piutang_dagang_all += (($rinci->harga_barang - ($rinci->harga_barang * ($rinci->diskon_barang / 100)) - $rinci->diskon_nominal) * $rinci->qty_barang) - $rinci->potongan_admin + $rinci->cashback_ongkir;
            $piutang_dagang += $rinci->harga_barang * $rinci->qty_barang;
            $potongan_admin += $rinci->potongan_admin;
            $cashback_ongkir += $rinci->cashback_ongkir;
            $diskon += ($rinci->harga_barang * ($rinci->diskon_barang / 100)) + $rinci->diskon_nominal * $rinci->qty_barang;
        }

        // Update SO
        $so = Penjualan_SO::find($request->id_so);
        $so->status_invoice = 1;
        $so->save();

        $piutang_dagang_gl = [
            'tahun' => date('Y', strtotime(date('Y-m-d'))),
            'tanggal' => date('Y-m-d'),
            'nomer' => $so->so_nomer,
            'sumber' => 'sls_si',
            'coa_no' => '1100',
            'coa' => 'Piutang Dagang',
            'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
            'pemasok' => null,
            'debit' => $piutang_dagang_all,
            'kredit' => 0
        ];

        storeGeneralLedger($piutang_dagang_gl);

        if($diskon > 0){
            $diskon_gl = [
                'tahun' => date('Y', strtotime(date('Y-m-d'))),
                'tanggal' => date('Y-m-d'),
                'nomer' => $so->so_nomer,
                'sumber' => 'sls_si',
                'coa_no' => '4000.3',
                'coa' => 'Disc Penjualan',
                'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
                'pemasok' => null,
                'debit' => $diskon,
                'kredit' => 0,
            ];
            storeGeneralLedger($diskon_gl);
        }

        if($cashback_ongkir > 0){
            $cashback = [
                'tahun' => date('Y', strtotime(date('Y-m-d'))),
                'tanggal' => date('Y-m-d'),
                'nomer' => $so->so_nomer,
                'sumber' => 'sls_si',
                'coa_no' => '1700',
                'coa' => 'Cash Back',
                'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
                'pemasok' => null,
                'debit' => $cashback_ongkir,
                'kredit' => 0,
            ];
            storeGeneralLedger($cashback);
        }
        
        if($potongan_admin > 0){
            $potongan_admin = [
                'tahun' => date('Y', strtotime(date('Y-m-d'))),
                'tanggal' => date('Y-m-d'),
                'nomer' => $so->so_nomer,
                'sumber' => 'sls_si',
                'coa_no' => '6200',
                'coa' => 'Biaya Umum & Administrasi',
                'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
                'pemasok' => null,
                'debit' => $potongan_admin,
                'kredit' => 0,
            ];
            storeGeneralLedger($potongan_admin);
        }
        

        $penjualan_gl = [
            'tahun' => date('Y', strtotime(date('Y-m-d'))),
            'tanggal' => date('Y-m-d'),
            'nomer' => $so->so_nomer,
            'sumber' => 'sls_si',
            'coa_no' => '4000.1',
            'coa' => 'Penjualan',
            'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
            'pemasok' => null,
            'debit' => 0,
            'kredit' => $piutang_dagang
        ];
        storeGeneralLedger($penjualan_gl);

        $hpp_gl = [
            'tahun' => date('Y', strtotime(date('Y-m-d'))),
            'tanggal' => date('Y-m-d'),
            'nomer' => $so->so_nomer,
            'sumber' => 'sls_si',
            'coa_no' => '5000',
            'coa' => 'Harga Pokok Penjualan',
            'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
            'pemasok' => null,
            'debit' => $hpp,
            'kredit' => 0
        ];

        storeGeneralLedger($hpp_gl);
        

        $persediaan_gl = [
            'tahun' => date('Y', strtotime(date('Y-m-d'))),
            'tanggal' => date('Y-m-d'),
            'nomer' => $so->so_nomer,
            'sumber' => 'sls_si',
            'coa_no' => '1300',
            'coa' => 'Persediaan Barang Dagang',
            'pelanggan' => Pelanggan::find($so->id_pelanggan)->nama_pelanggan,
            'pemasok' => null,
            'debit' => 0,
            'kredit' => $hpp
        ];

        storeGeneralLedger($persediaan_gl);

        // Kirim notifikasi ke Grup Mires Mahisa via Telegram
        // Auth::user()->notify(new SendNotificationSalesTelegram([
        //     'text' => '*SALES INVOICE BARU*, Sales Order dengan nomer *' . $so->so_nomer . "*, sudah dibuatkan Invoice oleh " . Auth::user()->name . " pada Jam : *" . date('Y-m-d H:i:s') . "*"
        // ]));

        return redirect('admin/si/'.$so->id)->with('success', 'Berhasil Membuat Invoice, Silahkan Cetak Invoice!');
    }

    public function show($id)
    {
        if(auth()->user()->cannot('view', Penjualan_Invoice::class)) abort('403', 'access denied');
        // dd($si);

        $data = [
            'so' => Penjualan_SO::with('rinci')->find($id),
            'do' => Penjualan_DO::where('so_id', $id)->first(),
        ];

        return view('penjualan.si.show', $data);
    }

    public function edit(Penjualan_Invoice $si)
    {
        if(auth()->user()->cannot('edit', Penjualan_Invoice::class)) abort('403', 'access denied');
        // dd($si);

        $data = [
            'si' => $si->with('pelanggan')->with('so')->with('rinci.barang')->find($si->id)
        ];

        return view('penjualan.si.edit', $data);
    }

    public function update(Penjualan_Invoice $si)
    {
        if(auth()->user()->cannot('edit', Penjualan_Invoice::class)) abort('403', 'access denied');
        // dd($si);
        Gl::where(['sumber' => 'sls_si', 'nomer' => $si->nomer_invoice])->update([
            'nomer' => Request('si_nomer'),
            'tahun' => date('Y', strtotime(Request('si_tanggal')))
        ]);

        $si->nomer_invoice = Request('si_nomer');
        $si->tanggal = Request('si_tanggal');
        $si->keterangan = Request('keterangan');
        $si->save();



        return redirect('admin/si/'.$si->id)->with('success', 'Berhasil mengubah data!');
    }

    public function destroy(Penjualan_Invoice $si)
    {
        $so = Penjualan_SO::find($si->so_id);
        $so->status_invoice = 0;
        $so->save();

        Penjualan_Invoice_Rinci::where('penjualan_invoice_id', $si->id)->delete();
        $si->delete();

        Gl::where(['sumber' => 'sls_si', 'nomer' => $si->nomer_invoice])->delete();

        
        return redirect('admin/si/')->with('success', 'Berhasil menghapus data!');
    }

    public function print($id)
    {
        $si = Penjualan_Invoice::where('so_id',$id)->with('so')->first();
        $si_rinci = Penjualan_Invoice_Rinci::where('penjualan_invoice_id', $si->id)->get();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('penjualan.si.print', compact('si','si_rinci'));
        
        return $pdf->stream();
    }
}
