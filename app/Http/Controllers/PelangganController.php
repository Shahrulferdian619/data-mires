<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\TipePelanggan;
use App\Models\City;
use App\Models\Province;
use App\Models\v2\Master\Pelanggan as MasterPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use PDF;
use DataTables;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Pelanggan::class)) abort('403', 'access denied');

    //     $pelanggan = Pelanggan::all();
        
    //     return view('pelanggan.index', compact(
    //         'pelanggan'
    //     ));
    // }

    public function index(Request $request)
    {

        return "<h1>Maaf Halaman sedang dalam perbaikan</h1>";

        if(auth()->user()->cannot('viewAny', Pelanggan::class)) abort('403', 'access denied');

        $pelanggan = Pelanggan::all();

        if($request->ajax()){
            return datatables()->of($pelanggan)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.pelanggan.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->editColumn('tipepelanggan', function($row){
                return $row->tipepelanggan ? $row->tipepelanggan->tipepelanggan : $row->tipepelanggan;
            })
            ->editColumn('alamat', function($row){
                return $row->negara.', '.$row->provinsi.', '.$row->kota.', '.$row->kecamatan;
            })
            ->editColumn('kode_area', function($row){
                return $row->kode_area;
            })
            ->rawColumns(['actions','tipepelanggan','alamat'])->make(true);
        }

        return view('pelanggan.index', compact(
            'pelanggan'
        ));
    }

    public function exportPDF()
	{
        $pelanggan = Pelanggan::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('pelanggan.exportpdf',compact('pelanggan'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Pelanggan.pdf');
	}

    public function printPDF()
	{
        $pelanggan = Pelanggan::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('pelanggan.exportpdf',compact('pelanggan'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(auth()->user()->cannot('create', Pelanggan::class)) abort('403', 'access denied');

        $tipepelanggan = TipePelanggan::all();

        $provinces = Province::all();

        return view('pelanggan.create', compact(
            'tipepelanggan','provinces'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Pelanggan::class)) abort('403', 'access denied');

        $validate = $this->validation();

        $pelanggan = new Pelanggan();
        $pelanggan->kode_pelanggan = $request->kode_pelanggan;
        $pelanggan->kode_area = $request->kode_area;
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->handphone_pelanggan = $request->handphone_pelanggan;
        $pelanggan->email_pelanggan = $request->email_pelanggan;
        $pelanggan->negara = $request->negara;
        $pelanggan->provinsi = Province::find($request->provinsi)->name;
        $pelanggan->kota = $request->kota;
        $pelanggan->kecamatan = $request->kecamatan;
        $pelanggan->detail_alamat = $request->detail_alamat;
        $pelanggan->deskripsi_pelanggan = $request->deskripsi_pelanggan;
        $pelanggan->tipepelanggan_id = $request->tipepelanggan_id;
        $pelanggan->save();

        // insert ke data tabel baru
        MasterPelanggan::create([
            'kode_pelanggan' => $request->kode_pelanggan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_handphone' => $request->handphone_pelanggan,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'detil_alamat' => $request->detail_alamat,
        ]);

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/pelanggan')->with('success', 'Data berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        if(auth()->user()->cannot('view', Pelanggan::class)) abort('403', 'access denied');

        return view('pelanggan.detail', compact(
            'pelanggan'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->cannot('update', Pelanggan::class)) abort('403', 'access denied');

        $pelanggan = Pelanggan::find($id);
        $tipepelanggan = TipePelanggan::all();
        
        $provinces = Province::all();

        return view('pelanggan.edit', compact(
            'pelanggan','tipepelanggan','provinces'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->cannot('update', Pelanggan::class)) abort('403', 'access denied');

        $validate = $this->validation($id);

        $pelanggan = Pelanggan::find($id);
        $pelanggan->kode_pelanggan = $request->kode_pelanggan;
        $pelanggan->kode_area = $request->kode_area;
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->handphone_pelanggan = $request->handphone_pelanggan;
        $pelanggan->email_pelanggan = $request->email_pelanggan;
        $pelanggan->negara = $request->negara;
        $pelanggan->provinsi = Province::find($request->provinsi)->name;
        $pelanggan->kota = $request->kota;
        $pelanggan->kecamatan = $request->kecamatan;
        $pelanggan->detail_alamat = $request->detail_alamat;
        $pelanggan->deskripsi_pelanggan = $request->deskripsi_pelanggan;
        $pelanggan->tipepelanggan_id = $request->tipepelanggan_id;
        $pelanggan->save();

        return redirect('/admin/pelanggan')->with('success', 'Data berhasil di ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        if(auth()->user()->cannot('delete', Pelanggan::class)) abort('403', 'access denied');

        try {
            $pelanggan = Pelanggan::find($pelanggan->id);
            $pelanggan->delete();
        } catch (\Throwable $th) {
            return back()->with('fail', 'Data tidak bisa dihapus! sudah digunakan dalam transaksi');
        }

        return redirect('/admin/pelanggan')->with('success', 'Data berhasil di hapus');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'tipepelanggan_id' => 'required',
            'kode_pelanggan' => 'required|unique:pelanggans,kode_pelanggan, '.$id,
            'nama_pelanggan' => 'required',
            'detail_alamat' => 'required',
         ]);

         return $validate;
    }
    public function getCity($id){
        $data = City::where('province_id', $id)->get();
        return response()->json($data);
    } 
}
