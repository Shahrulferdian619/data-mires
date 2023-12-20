<?php

namespace App\Http\Controllers\Penjualan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\{
    Barang,
    BerkasSalesorder,
    EkspedisiLogistik,
    JenisPenjualan,
    Pelanggan,
    Penjualan_SO,
    Penjualan_SO_rinci,
    Sales,
    TipePelanggan,
    Packet,
    PacketRinci,
    Penjualan_DO,
    Penjualan_DO_Rinci,
    Penjualan_Invoice,
    Penjualan_Invoice_Rinci,
    Province,
    Roleakses,
};
use App\Notifications\SendNotificationSalesTelegram;
use App\PenjualanInvoice;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SoController extends Controller
{

    public function index(Request $request)
    {

        if (Auth::user()->cannot('viewAny', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        //ambil semua data transaksi SO
        $so = Penjualan_SO::all();

        if ($request->ajax()) {
            return datatables()->of($so)

                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<td>
                <a class="badge badge-light-secondary" href="' . url('admin/so/' . $row->id) . '"><i data-feather="eye"></i> Lihat</a>            
                </td>';
                })

                ->editColumn('so_tanggal', function ($row) {
                    return $row->so_tanggal ? with(new Carbon($row->so_tanggal))->format('d-m-Y') : '';;
                })

                ->editColumn('no_pesanan', function ($row) {
                    return $row->no_pesanan;
                })

                ->editColumn('jenis_penjualan', function ($row) {
                    $jenis_penjualan = JenisPenjualan::find($row->jenis_penjualan);
                    return $jenis_penjualan->jenis_penjualan;
                })

                ->editColumn('status_do', function ($row) {
                    if ($row->status_do == 0) {
                        $status_do = '<div style="width:120px" class="badge badge-light-warning">Belum Dikirim</div>';
                    } elseif ($row->status_do == 1) {
                        $status_do = '<div style="width:120px" class="badge badge-light-info">Dikirim Sebagian</div>';
                    } elseif ($row->status_do == 2) {
                        $status_do = '<div style="width:120px" class="badge badge-light-success">Sudah Dikirim</div>';
                    } else {
                        $status_do = "-";
                    }
                    return $status_do;
                })
                ->editColumn('status_invoice', function ($row) {
                    if ($row->status_invoice == 0) {
                        $status_invoice = '<div style="width:120px" class="badge badge-light-warning">Belum Invoice</div>';
                    } elseif ($row->status_invoice == 1) {
                        $status_invoice = '<div style="width:120px" class="badge badge-light-info">Dibuatkan Invoice</div>';
                    } elseif ($row->status_invoice == 2) {
                        $status_invoice = '<div style="width:120px" class="badge badge-light-success">Sudah lunas</div>';
                    } else {
                        $status_invoice = "-";
                    }
                    return $status_invoice;
                })
                ->rawColumns(['actions', 'so_tanggal', 'status_do', 'status_invoice'])->make(true);
        }

        return view('penjualan.so.index', compact('so'));
    }

    public function create()
    {
        if (Auth::user()->cannot('create', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        $bulan = bulan_romawi(date('m'));
        $count_so = Penjualan_SO::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $count = str_pad($count_so + 1, 3, "0", STR_PAD_LEFT);

        $so_number = "SO-" . $count . "/" . $bulan . "/" . date('Y');

        $customer = Pelanggan::all();
        $tipepelanggan = TipePelanggan::all();
        $items = Barang::where('type', 1)->get();
        $sales = Sales::all();
        $packet = Packet::where('status', 1)->get();
        $provinces = Province::all();

        $data = [
            'customer' => $customer,
            'items' => $items,
            'jenis_penjualan' => JenisPenjualan::get(),
            'sales' => $sales,
            'tipe_pelanggan' => $tipepelanggan,
            'packet' => $packet,
            'provinces' => $provinces,
            'so_nomer' => $so_number,
            'ekspedisi' => EkspedisiLogistik::all(),
        ];

        return view('penjualan.so.create', $data);
    }

    public function store(Request $request)
    {
        if (Auth::user()->cannot('create', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');
        
        //validasi nomer sama
        $bulan = bulan_romawi(date('m'));
        $count_so = Penjualan_SO::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $count = str_pad($count_so + 1, 3, "0", STR_PAD_LEFT);

        $so_number = "SO-" . $count . "/" . $bulan . "/" . date('Y');

        // Jika User Memasukkan Pelanggan Baru
        if ($request->kode_pelanggan != '' && $request->nama_pelanggan != '') {
            // Validasi Kode Pelanggan
            $oldcustnumber = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();
            if (!empty($oldcustnumber)) {
                return response()->json(['errors' => "Error! Kode Pelanggan sudah digunakan, Masukan Kode Pelanggan lainnya.", 'nomer' => true]);
            }

            $pelanggan = new Pelanggan;
            $pelanggan->tipepelanggan_id = $request->id_tipepelanggan;
            $pelanggan->kode_pelanggan = $request->kode_pelanggan;
            $pelanggan->kode_area = $request->kode_area;
            $pelanggan->nama_pelanggan = $request->nama_pelanggan;
            $pelanggan->handphone_pelanggan = $request->handphone_pelanggan;
            $pelanggan->negara = 'INDONESIA';
            $pelanggan->provinsi = Province::find($request->provinsi)->name;
            $pelanggan->kota = $request->kota;
            $pelanggan->detail_alamat = $request->detail_alamat;
            $pelanggan->save();
            $request->id_pelanggan = $pelanggan->id;
        }

        // Validasi Input Pelanggan
        if ($request->id_pelanggan == '') {
            return response()->json(['errors' => "Error! Pilih pelanggan terlebih dahulu, jika tidak ada, isikan pelanggan baru!.", 'nomer' => true]);
        }

        if ($request->id_barang == null) {
            return response()->json(['errors' => "Anda Belum Mengisikan Rincian!", 'nomer' => true]);
        }

        // Insert data baru Sales Order
        $data = new Penjualan_SO();
        $data->so_nomer = $so_number;
        $data->so_tanggal = $request->so_tanggal;
        $data->id_pelanggan = $request->id_pelanggan;
        $data->jenis_penjualan = $request->jenis_penjualan;
        $data->keterangan = $request->keterangan;
        $data->is_tax = $request->is_tax;
        $data->no_pesanan = $request->no_pesanan;
        $data->penerima = $request->penerima;
        $data->ekspedisi = $request->ekspedisi;
        $data->resi = $request->resi;
        $data->alamat_pengiriman = $request->alamat_pengiriman;
        if ($request->id_sales != 0) {
            $data->id_sales = $request->id_sales;
        }
        $data->id_user = auth()->id();
        $data->save();
        //dd($data->id);
        // ==================================

        // Generate otomatis Sales Invoice dengan memanggil Controller SiController
        //app('App\Http\Controllers\Penjualan\SiController')->store($data->id);
        // ==================================

        // buat data permintaan pembelian rinci
        $rinci = count($request->id_barang);
        for ($i = 0; $i < $rinci; $i++) {
            Penjualan_SO_rinci::create([
                'id_barang' => $request->id_barang[$i],
                'qty_barang' => $request->qty[$i],
                'harga_barang' => explodeRupiah($request->harga[$i]),
                'diskon_barang' => $request->diskon[$i],
                'diskon_nominal' => $request->diskon_nominal[$i] != '' ? explodeRupiah($request->diskon_nominal[$i]) : '',
                'potongan_admin' => $request->potongan_admin[$i] != '' ? explodeRupiah($request->potongan_admin[$i]) : '',
                'cashback_ongkir' => $request->cashback_ongkir[$i] != '' ? explodeRupiah($request->cashback_ongkir[$i]) : '',
                'note' => $request->note[$i],
                'id_so' => $data->id,
            ]);
        }

        if (!empty($request->berkas)) {
            // buat data berkas
            $totalBerkas = count($request->berkas);
            $collectNamaBerkas = [];

            for ($i = 0; $i < $totalBerkas; $i++) {
                if ($request->hasFile('berkas.' . $i)) {
                    $file = $request->file('berkas')[$i];
                    $originalName = $file->getClientOriginalName();
                    $berkas = str_replace(" ", "", $originalName);
                    $file->move('uploads/so_penjualan', $berkas);

                    array_push($collectNamaBerkas, $berkas);
                }
            }

            $saveBerkas = count($collectNamaBerkas);

            $berkas_so = new BerkasSalesorder();
            $berkas_so->penjualan_so_id = $data->id;

            for ($i = 0; $i < $saveBerkas; $i++) {
                $flag = $i + 1;
                $var = "berkas_" . $flag;
                $berkas_so->$var = $collectNamaBerkas[$i];
            }
            $berkas_so->save();
        }

        // Kirim notifikasi ke Grup Mires Mahisa
        // menggunakan api telegram
        $urlApiTelegram = config('telegram_config.telegram_api_url') . 'api/v1/send_notif_telegram/sales/send_new_sales_order';
        $body = [
            'api_token' => config('telegram_config.telegram_api_token'),
            'nomerSo' => $data->so_nomer,
            'jenisPenjualan' => JenisPenjualan::find($data->jenis_penjualan)->jenis_penjualan,
            'namaPelanggan' => Pelanggan::find($data->id_pelanggan)->nama_pelanggan,
            'tanggal' => $data->so_tanggal,
            'user' => Auth::user()->name
        ];
        $response = Http::post($urlApiTelegram, $body);

        // JANGAN DIHAPUS CODE DIBAWAH INI //
        // Auth::user()->notify(new SendNotificationSalesTelegram([
        //     'text' => '*SALES ORDER BARU* dengan nomer *' . $data->so_nomer . "*, dibuat oleh " . Auth::user()->name . " pada Jam : *" . date('Y-m-d H:i:s') . "*"
        // ]));
        // =============================== //

        return response()->json("OKE");
    }

    public function show(Penjualan_SO $so)
    {
        if (Auth::user()->cannot('view', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');
        $accountant = false;
        if (Roleakses::where(['user_id' => auth()->id(), 'nama_controller' => 'btn_acc_checked'])->first()) {
            $accountant = true;
        }

        $data = [
            'so' => $so->selectRaw('penjualan_so.*, jenis_penjualan.jenis_penjualan as jenis_penjualan')
                ->join('jenis_penjualan', 'penjualan_so.jenis_penjualan', 'jenis_penjualan.id')
                ->with('rinci.barang')->with('berkas')->with('pelanggan')->find($so->id),
            'acc' => $accountant
        ];

        return view('v2.sales.so.show', $data); //return view('penjualan.so.show', $data);
    }

    public function edit($id)
    {
        // cek user yang bisa edit 
        if (Auth::user()->cannot('update', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        // jika status so belum diubah, maka akses diizinkan
        if (Penjualan_SO::find($id)->status_do == 0) {
            $customer = Pelanggan::all();
            $jenis_penjualan = JenisPenjualan::get();
            $so = Penjualan_SO::find($id);
            $berkas = BerkasSalesorder::wherePenjualanSoId($id)->first();
            $items = Barang::all();
            return view('penjualan.so.edit', compact('customer', 'so', 'berkas', 'jenis_penjualan', 'items'));
        }
        // jika status so sudah dibuatkan do, maka tolak semua akses kecuali spv dan accounting
        else {
            // cek apakah user tersebut spv atau accounting finance, 
            // user id : Mia=13, Adi=16, Superadmin=1
            // agar bisa melakukan edit data walaupun sudah diproses
            // fitur edit tidak dapat melakukan perubahan qty produk, harga produk, menghapus produk
            // kode di sini...
            if (Auth::user()->id == 13 || Auth::user()->id == 16 || Auth::user()->id == 1) {

                // load data sales order
                $sales_order = Penjualan_SO::find($id);
                $data_pelanggan = Pelanggan::all();
                $data_jenis_penjualan = JenisPenjualan::all();
                $data_sales = Sales::all();
                $data_ekspedisi = EkspedisiLogistik::all();

                return view('v2.sales.so.edit', compact(
                    'sales_order',
                    'data_pelanggan',
                    'data_jenis_penjualan',
                    'data_sales',
                    'data_ekspedisi',
                ));
            }
            // tolak akses edit
            if (Penjualan_SO::find($id)->status_do != 0) {
                return redirect('admin/so/' . $id)->with('fail', 'SO yang sudah dikirim tidak dapat diubah!');
            }
        }
    }

    public function edit_v2($id)
    {
        // cek user yang bisa edit 
        if (Auth::user()->cannot('update', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        // jika status so belum diubah, maka akses diizinkan
        if (Penjualan_SO::find($id)->status_do == 0) {
            $customer = Pelanggan::all();
            $jenis_penjualan = JenisPenjualan::get();
            $so = Penjualan_SO::find($id);
            $berkas = BerkasSalesorder::wherePenjualanSoId($id)->first();
            $items = Barang::all();
            return view('v2.sales.so.old_edit', compact(
                'customer',
                'so',
                'berkas',
                'jenis_penjualan',
                'items'
            ));
        }
        // jika status so sudah dibuatkan do, maka tolak semua akses kecuali spv dan accounting
        else {
            // cek apakah user tersebut spv atau accounting finance, 
            // user id : Mia=13, Adi=16, Superadmin=1
            // agar bisa melakukan edit data walaupun sudah diproses
            // fitur edit tidak dapat melakukan perubahan qty produk, harga produk, menghapus produk
            // kode di sini...
            if (Auth::user()->id == 13 || Auth::user()->id == 16 || Auth::user()->id == 1) {

                // load data sales order
                $sales_order = Penjualan_SO::find($id);
                $data_pelanggan = Pelanggan::all();
                $data_jenis_penjualan = JenisPenjualan::all();
                $data_sales = Sales::all();
                $data_ekspedisi = EkspedisiLogistik::all();

                return view('v2.sales.so.edit', compact(
                    'sales_order',
                    'data_pelanggan',
                    'data_jenis_penjualan',
                    'data_sales',
                    'data_ekspedisi',
                ));
            }
            // tolak akses edit
            if (Penjualan_SO::find($id)->status_do != 0) {
                return redirect('admin/so/' . $id)->with('fail', 'SO yang sudah dikirim tidak dapat diubah!');
            }
        }
    }

    public function update_v2(Request $request)
    {
        // cek user yang bisa update
        if (Auth::user()->cannot('update', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        // hapus cr invoice

        // hapus invoice
        Penjualan_Invoice::where('so_id', $request->so_id)->delete();

        // update header so
        $so = Penjualan_SO::find($request->so_id);
        $so->status_do = 2;
        $so->id_user = Auth::user()->id;
        $so->so_nomer = $request->so_nomer;
        $so->so_tanggal = $request->so_tanggal;
        $so->id_pelanggan = $request->id_pelanggan;
        $so->jenis_penjualan = $request->jenis_penjualan;
        $so->no_pesanan = $request->no_pesanan;
        $so->id_sales = $request->id_sales;
        $so->is_tax = $request->is_tax;
        $so->ekspedisi = $request->ekspedisi;
        $so->resi = $request->resi;
        $so->keterangan = $request->keterangan;
        $so->penerima = $request->penerima;
        $so->alamat_pengiriman = $request->alamat_pengiriman;
        $so->save();

        // hapus rincian dulu, kemudian insert baru rincian
        Penjualan_SO_rinci::where('id_so', $so->id)->delete();
        foreach ($request->id_barang as $key => $value) {

            $so_rinci = new Penjualan_SO_rinci();

            $so_rinci->id_so = $so->id;
            $so_rinci->id_barang = $value;
            $so_rinci->qty_barang = $request->qty_barang[$key];
            $so_rinci->harga_barang = $request->harga_barang[$key];
            $so_rinci->diskon_barang = $request->diskon_barang[$key];
            $so_rinci->diskon_nominal = $request->diskon_nominal[$key];
            $so_rinci->potongan_admin = $request->potongan_admin[$key];
            $so_rinci->cashback_ongkir = $request->cashback_ongkir[$key];
            $so_rinci->subtotal = $request->subtotal[$key];
            $so_rinci->note = $request->note[$key];

            $so_rinci->save();
        }

        // update header do
        $do = Penjualan_DO::where('so_id', $request->so_id)->first();
        $do->so_id = $request->so_id;
        $do->id_user = Auth::user()->id;
        $do->do_nomer = $request->so_nomer;
        $do->do_tanggal = $request->so_tanggal;
        $do->id_pelanggan = $request->id_pelanggan;
        $do->alamat_do = $request->alamat_do;
        $do->pic_do = $request->penerima;
        $do->save();

        // hapus rincian do dulu, baru insert data rincian do baru
        Penjualan_DO_Rinci::where('do_id', $request->so_id)->delete();
        foreach ($request->id_barang as $key => $value) {
            $do_rinci = new Penjualan_DO_Rinci();

            $do_rinci->do_id = $do->id;
            $do_rinci->id_barang = $value;
            $do_rinci->qty = $request->qty_barang[$key];
            $do_rinci->note = $request->note[$key];
            $do_rinci->so_rinci_id = $so_rinci->id;

            $do_rinci->save();
        }

        // redirect ke halaman daftar so
        return redirect()->route('admin.so.index')->with('success', 'Berhasil memperbarui data yang sudah diproses.');
    }

    public function destroy($id)
    {
        if (Auth::user()->cannot('delete', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        $so = Penjualan_SO::find($id);
        if ($so->status_do != 0) {
            return redirect('admin/so/' . $id)->with('fail', 'SO Tidak Dapat Dihapus Karena Barang Sudah Dikirim!');
        }
        $berkas_so = BerkasSalesorder::where('penjualan_so_id', $id)->first();

        if (!empty($berkas_so)) {
            if ($berkas_so->berkas_1 != '') {
                if ($berkas_so->berkas_1 != '') {
                    File::delete('uploads/so_penjualan/' . $berkas_so->berkas_1);
                }
            }
            if ($berkas_so->berkas_2 != '') {
                if ($berkas_so->berkas_2 != '') {
                    File::delete('uploads/so_penjualan/' . $berkas_so->berkas_2);
                }
            }
            if ($berkas_so->berkas_3 != '') {
                if ($berkas_so->berkas_3 != '') {
                    File::delete('uploads/so_penjualan/' . $berkas_so->berkas_3);
                }
            }
            if ($berkas_so->berkas_4 != '') {
                if ($berkas_so->berkas_4 != '') {
                    File::delete('uploads/so_penjualan/' . $berkas_so->berkas_4);
                }
            }
            if ($berkas_so->berkas_5 != '') {
                if ($berkas_so->berkas_5 != '') {
                    File::delete('uploads/so_penjualan/' . $berkas_so->berkas_5);
                }
            }
            $berkas_so->delete();
        }

        Penjualan_SO_rinci::where('id_so', $id)->delete();
        $so->delete();

        return redirect('admin/so')->with('success', 'Berhasil Menghapus SO!');
    }
    ///// END OF CRUD /////

    // Print DO
    public function print_do($id)
    {
        $so = Penjualan_SO::find($id);
        $so_rinci = Penjualan_SO_rinci::where('id_so', $id)->get();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('v2.sales.so.print_do_v2', compact('so', 'so_rinci'));

        return $pdf->stream();
    }

    // Print SO
    public function print_so($id)
    {
        $so = Penjualan_SO::find($id);
        $so_rinci = Penjualan_SO_rinci::where('id_so', $id)->get();


        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadView('v2.sales.so.print_so_v2', compact('so', 'so_rinci'));

        return $pdf->stream();
    }

    public function exportPDF()
    {
        $so = Penjualan_SO::selectRaw('penjualan_so.*, jenis_penjualan.jenis_penjualan as jenis_penjualan')
            ->join('jenis_penjualan', 'penjualan_so.jenis_penjualan', 'jenis_penjualan.id')
            ->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.so.exportpdf', compact('so'));
        return $pdf->download('Pesanan Penjualan.pdf');
    }

    public function printPDF()
    {
        $so = Penjualan_SO::selectRaw('penjualan_so.*, jenis_penjualan.jenis_penjualan as jenis_penjualan')
            ->join('jenis_penjualan', 'penjualan_so.jenis_penjualan', 'jenis_penjualan.id')
            ->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('penjualan.so.exportpdf', compact('so'));
        return $pdf->stream();
    }

    public function _store(Request $request)
    {
        if (Auth::user()->cannot('create', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        if (isset($_POST['add_items'])) {
            $request->validate([
                'so_nomer' => 'required',
                'so_tanggal' => 'required',
                'id_pelanggan' => 'required',
                'jenis_penjualan' => 'required',
                'is_tax' => 'required',
                'id_barang' => 'required',
                'qty_barang' => 'required',
                'harga_barang' => 'required',
                'diskon_barang' => 'required'
            ]);

            $barang = Barang::find($request->id_barang);

            if (session()->has('so_penjualan')) {
                session()->push('so_penjualan.items', [
                    'id_barang' => $request->id_barang,
                    'qty_barang' => $request->qty_barang,
                    'harga_barang' => $request->harga_barang,
                    'diskon_barang' => $request->diskon_barang,
                    'keterangan_barang' => $request->keterangan_barang,
                    'nama_barang' => $barang->nama_barang
                ]);
            } else {
                $items[] = [
                    'id_barang' => $request->id_barang,
                    'qty_barang' => $request->qty_barang,
                    'harga_barang' => $request->harga_barang,
                    'diskon_barang' => $request->diskon_barang,
                    'keterangan_barang' => $request->keterangan_barang,
                    'nama_barang' => $barang->nama_barang
                ];
                $dataSOPenjualan = [
                    'so_nomer' => $request->so_nomer,
                    'so_tanggal' => $request->so_tanggal,
                    'keterangan' => $request->keterangan,
                    'id_pelanggan' => $request->id_pelanggan,
                    'jenis_penjualan' => $request->jenis_penjualan,
                    'is_tax' => $request->is_tax,
                    'items' => $items
                ];
                session()->put('so_penjualan', $dataSOPenjualan);
            }
            return back()->with('success', 'Berhasil menambahkan barang');
        } else if (isset($_POST['create-so-penjualan'])) {
            $request->validate([
                'so_nomer' => 'required',
                'so_tanggal' => 'required',
                'id_pelanggan' => 'required',
                'jenis_penjualan' => 'required',
                'is_tax' => 'required'
            ]);

            if (!session()->has('so_penjualan')) {
                return back();
            }

            if (count(session()->get('so_penjualan')['items']) == 0) {
                return back();
            }

            $data = new Penjualan_SO();
            $data->so_nomer = session()->get('so_penjualan')['so_nomer'];
            $data->so_tanggal = session()->get('so_penjualan')['so_tanggal'];
            $data->id_pelanggan = session()->get('so_penjualan')['id_pelanggan'];
            $data->jenis_penjualan = session()->get('so_penjualan')['jenis_penjualan'];
            $data->keterangan = session()->get('so_penjualan')['keterangan'];
            $data->is_tax = session()->get('so_penjualan')['is_tax'];
            $data->id_user = auth()->id();
            $data->save();

            foreach (session()->get('so_penjualan')['items'] as $key => $value) {
                $soRinci = new Penjualan_SO_rinci();
                $soRinci->id_barang = $value['id_barang'];
                $soRinci->qty_barang = $value['qty_barang'];
                $soRinci->harga_barang = $value['harga_barang'];
                $soRinci->diskon_barang = $value['diskon_barang'];
                $soRinci->note = $value['keterangan_barang'];
                $soRinci->id_so = $data->id;
                $soRinci->save();
            }

            session()->forget('so_penjualan');

            $berkas1 = '';
            $berkas2 = '';
            $berkas3 = '';
            $berkas4 = '';
            $berkas5 = '';

            if ($request->hasFile('berkas_1')) {
                $file = $request->file('berkas_1');
                $originalName1 = $file->getClientOriginalName();
                $berkas1 = $originalName1;
                $file->move('uploads/so_penjualan', $berkas1);
            }
            if ($request->hasFile('berkas_2')) {
                $file = $request->file('berkas_2');
                $originalName2 = $file->getClientOriginalName();
                $berkas2 = $originalName2;
                $file->move('uploads/so_penjualan', $berkas2);
            }
            if ($request->hasFile('berkas_3')) {
                $file = $request->file('berkas_3');
                $originalName3 = $file->getClientOriginalName();
                $berkas3 = $originalName3;
                $file->move('uploads/so_penjualan', $berkas3);
            }
            if ($request->hasFile('berkas_4')) {
                $file = $request->file('berkas_4');
                $originalName4 = $file->getClientOriginalName();
                $berkas4 = $originalName4;
                $file->move('uploads/so_penjualan', $berkas4);
            }
            if ($request->hasFile('berkas_5')) {
                $file = $request->file('berkas_5');
                $originalName5 = $file->getClientOriginalName();
                $berkas5 = $originalName5;
                $file->move('uploads/so_penjualan', $berkas5);
            }

            // buat berkas permintaan pembelian
            $berkas_so = new BerkasSalesorder();
            $berkas_so->penjualan_so_id = $data->id;
            $berkas_so->berkas_1 = $berkas1;
            $berkas_so->berkas_2 = $berkas2;
            $berkas_so->berkas_3 = $berkas3;
            $berkas_so->berkas_4 = $berkas4;
            $berkas_so->berkas_5 = $berkas5;
            $berkas_so->save();

            return redirect('admin/so')->with('success', 'Berhasil membuat penjualan order');
        }
    }

    public function _update($id)
    {

        $request = Request();
        $request->validate([
            'so_nomer' => 'required',
            'so_tanggal' => 'required',
            'id_pelanggan' => 'required',
            'jenis_penjualan' => 'required',
            'is_tax' => 'required'
        ]);
        $so = Penjualan_SO::find($id);
        $so->so_nomer = $request->so_nomer;
        $so->so_tanggal = $request->so_tanggal;
        $so->id_pelanggan = $request->id_pelanggan;
        $so->jenis_penjualan = $request->jenis_penjualan;
        $so->keterangan = $request->keterangan;
        $so->is_tax = $request->is_tax;
        $so->id_user = auth()->id();
        $so->save();


        // update berkas permintaan pembelian
        $berkas_so = BerkasSalesorder::where('penjualan_so_id', $id)->first();

        if ($request->hasFile('berkas_1')) {
            $file = $request->file('berkas_1');
            $originalName1 = $file->getClientOriginalName();
            $berkas1 = $originalName1;
            $file->move('uploads/so_penjualan', $berkas1);
            if ($berkas_so->berkas_1 != '') {
                File::delete('uploads/so_penjualan/' . $berkas_so->berkas_1);
            }
            $berkas_so->berkas_1 = $berkas1;
        }
        if ($request->hasFile('berkas_2')) {
            $file = $request->file('berkas_2');
            $originalName2 = $file->getClientOriginalName();
            $berkas2 = $originalName2;
            $file->move('uploads/so_penjualan', $berkas2);
            if ($berkas_so->berkas_2 != '') {
                File::delete('uploads/so_penjualan/' . $berkas_so->berkas_2);
            }
            $berkas_so->berkas_2 = $berkas2;
        }
        if ($request->hasFile('berkas_3')) {
            $file = $request->file('berkas_3');
            $originalName3 = $file->getClientOriginalName();
            $berkas3 = $originalName3;
            $file->move('uploads/so_penjualan', $berkas3);
            if ($berkas_so->berkas_3 != '') {
                File::delete('uploads/so_penjualan/' . $berkas_so->berkas_3);
            }
            $berkas_so->berkas_3 = $berkas3;
        }
        if ($request->hasFile('berkas_4')) {
            $file = $request->file('berkas_4');
            $originalName4 = $file->getClientOriginalName();
            $berkas4 = $originalName4;
            $file->move('uploads/so_penjualan', $berkas4);
            if ($berkas_so->berkas_4 != '') {
                File::delete('uploads/so_penjualan/' . $berkas_so->berkas_4);
            }
            $berkas_so->berkas_4 = $berkas4;
        }
        if ($request->hasFile('berkas_5')) {
            $file = $request->file('berkas_5');
            $originalName5 = $file->getClientOriginalName();
            $berkas5 = $originalName5;
            $file->move('uploads/so_penjualan', $berkas5);
            if ($berkas_so->berkas_5 != '') {
                File::delete('uploads/so_penjualan/' . $berkas_so->berkas_5);
            }
            $berkas_so->berkas_5 = $berkas5;
        }

        $berkas_so->save();

        return redirect('admin/so/' . $id)->with('success', 'Berhasil Mengupdate Sales Order!');
    }

    public function update($id)
    {
        // cek user yang bisa update
        if (Auth::user()->cannot('update', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        $request = Request();
        $request->validate([
            'so_nomer' => 'required',
            'so_tanggal' => 'required',
            'id_pelanggan' => 'required',
            'jenis_penjualan' => 'required',
            'is_tax' => 'required'
        ]);


        $so = Penjualan_SO::find($id);
        $so->so_nomer = $request->so_nomer;
        $so->so_tanggal = $request->so_tanggal;
        $so->id_pelanggan = $request->id_pelanggan;
        $so->jenis_penjualan = $request->jenis_penjualan;
        $so->keterangan = $request->keterangan;
        $so->is_tax = $request->is_tax;
        $so->no_pesanan = $request->no_pesanan;
        $so->ekspedisi = $request->ekspedisi;
        $so->resi = $request->resi;
        if ($request->id_sales != 0) {
            $so->id_sales = $request->id_sales;
        }
        $so->id_user = auth()->id();

        if ($so->status_do == 0) {

            if (is_null($request->id_barang[0])) {
                return redirect('admin/so/' . $id . '/edit')->with('fail', 'Data rincian tidak boleh kosong!');
            }

            $rinci = count($request->id_barang);
            for ($i = 0; $i < $rinci; $i++) {
                if (!is_null($request->id_barang[$i])) {
                    if (!is_null($request->penjualan_so_rinci_id[$i])) {
                        Penjualan_SO_rinci::whereId($request->penjualan_so_rinci_id[$i])
                            ->update([
                                'id_barang' => $request->id_barang[$i],
                                'qty_barang' => $request->qty[$i],
                                'harga_barang' => explodeRupiah($request->harga[$i]),
                                'diskon_barang' => $request->diskon[$i],
                                'diskon_nominal' => $request->diskon_nominal[$i] != '' ? explodeRupiah($request->diskon_nominal[$i]) : '',
                                'potongan_admin' => $request->potongan_admin[$i] != '' ? explodeRupiah($request->potongan_admin[$i]) : '',
                                'cashback_ongkir' => $request->cashback_ongkir[$i] != '' ? explodeRupiah($request->cashback_ongkir[$i]) : '',
                                'note' => $request->note[$i],
                            ]);
                    } else {
                        Penjualan_SO_rinci::create([
                            'id_barang' => $request->id_barang[$i],
                            'qty_barang' => $request->qty[$i],
                            'harga_barang' => explodeRupiah($request->harga[$i]),
                            'note' => $request->note[$i],
                            'diskon_barang' => $request->diskon[$i],
                            'diskon_nominal' => $request->diskon_nominal[$i] != '' ? explodeRupiah($request->diskon_nominal[$i]) : '',
                            'potongan_admin' => $request->potongan_admin[$i] != '' ? explodeRupiah($request->potongan_admin[$i]) : '',
                            'cashback_ongkir' => $request->cashback_ongkir[$i] != '' ? explodeRupiah($request->cashback_ongkir[$i]) : '',
                            'id_so' => $id,
                        ]);
                    }
                }
            }


            // update berkas permintaan pembelian
            $berkas_so = BerkasSalesorder::where('penjualan_so_id', $id)->first();

            if (!empty($request->berkas)) {
                $totalBerkas = count($request->berkas);
                $collectNamaBerkas = [];

                for ($i = 0; $i < $totalBerkas; $i++) {
                    $flag = $i + 1;
                    $var = "berkas_" . $flag;

                    if ($request->hasFile('berkas.' . $i)) {
                        $file = $request->file('berkas')[$i];
                        $originalName = $file->getClientOriginalName();
                        $berkas = $originalName;
                        $file->move('uploads/so_penjualan', $berkas);

                        if (!empty($berkas_so)) {
                            File::delete('uploads/so_penjualan/' . $berkas_so->$var);
                        }

                        array_push($collectNamaBerkas, $berkas);
                    }
                }

                $saveBerkas = count($collectNamaBerkas);

                if (!empty($berkas_so)) {
                    for ($i = 0; $i < $saveBerkas; $i++) {
                        $flag = $i + 1;
                        $var = "berkas_" . $flag;
                        $berkas_so->$var = $collectNamaBerkas[$i];
                    }
                } else {
                    $berkas_so = new BerkasSalesorder();
                    $berkas_so->penjualan_so_id = $id;

                    for ($i = 0; $i < $saveBerkas; $i++) {
                        $flag = $i + 1;
                        $var = "berkas_" . $flag;
                        $berkas_so->$var = $collectNamaBerkas[$i];
                    }
                }

                $berkas_so->save();
            }
        }

        // save so
        $so->save();

        return redirect('admin/so/' . $id)->with('success', 'Berhasil Mengupdate Sales Order!');
    }

    public function deleteCart($key)
    {
        if (Auth::user()->cannot('delete', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        if (session()->has('so_penjualan')) {
            $so_penjualan_items = session()->get('so_penjualan')['items'];
            unset($so_penjualan_items[$key]);
            session()->put('so_penjualan.items', $so_penjualan_items);
        }

        return back();
    }

    public function updateChecked($id)
    {

        $so = Penjualan_SO::find($id);
        $so->update(['is_checked' => 1]);
        return redirect('admin/so')->with('success', 'Order sudah dikoreksi!');
    }

    public function destroyRinci(Request $request)
    {
        if (Auth::user()->cannot('delete', Penjualan_SO::class)) abort(403, 'akses tidak diizinkan');

        Penjualan_SO_rinci::whereId($request->id)->delete();

        return response()->json("OKE");
    }

    public function printdo($id)
    {
        if (Auth::user()->cannot('view', Penjualan_SO::class)) abort('403', 'akses tidak diizinkan');

        $so = Penjualan_SO::find($id);
        $so_rinci = Penjualan_SO_rinci::where('id_so', $id)->get();


        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('penjualan.so.print', compact('so', 'so_rinci'));

        return $pdf->stream();
    }

    public function getPacket($id)
    {
        $data = PacketRinci::where('id_packet', $id)->with('barang')->get();
        $data[0]['count'] = count($data);
        $data[0]['nama'] = Packet::find($id)->packet_name;
        return response()->json($data);
    }
}
