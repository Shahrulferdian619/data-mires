<?php

namespace App\Http\Controllers\Hrga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employees;
use PDF;
use Illuminate\Support\Facades\Gate;
use DataTables;
use Carbon\Carbon;
use DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     if(auth()->user()->cannot('viewAny', Employees::class)) abort(403, 'access denied');

    //     $employee = Employees::all();

    //     return view('hrga.employee.index', compact(
    //         'employee'
    //     ));
    // }

    public function index(Request $request)
    {
        if(auth()->user()->cannot('viewAny', Employees::class)) abort(403, 'access denied');

        $employee = Employees::all();

        if($request->ajax()){
            return datatables()->of($employee)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<td>
                <a class="badge badge-light-secondary" href="' . route('admin.employee.show', $row->id) .'"><i data-feather="eye"></i> Lihat</a>            
                </td>';
            })
            ->editColumn('tanggal_masuk_kerja', function ($row) {
                return $row->tanggal_masuk_kerja ? with(new Carbon($row->tanggal_masuk_kerja))->format('d-m-Y') : '';;
            })
            ->rawColumns(['actions','tanggal_masuk_kerja'])->make(true);
        }

        return view('hrga.employee.index', compact(
            'employee'
        ));
    }

    public function exportPDF()
	{
        $employee = Employees::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('hrga.employee.exportpdf',compact('employee'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->download('Karyawan.pdf');
	}

    public function printPDF()
	{
        $employee = Employees::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->loadView('hrga.employee.exportpdf',compact('employee'));
        return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->stream();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create', Employees::class)) abort(403, 'access denied');

        return view('hrga.employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create', Employees::class)) abort(403, 'access denied');

        
        $validate = $this->validation();


        $employee = new Employees;
        $employee->nik = $request->nik;
        $employee->tanggal_lahir_karyawan = $request->tanggal_lahir_karyawan;
        $employee->nama_karyawan = $request->nama_karyawan;
        $employee->nomer_hp_karyawan = $request->nomer_hp_karyawan;
        $employee->email_karyawan = $request->email_karyawan;
        $employee->alamat_karyawan = $request->alamat_karyawan;
        $employee->jabatan_karyawan = $request->jabatan_karyawan;
        $employee->divisi_karyawan = $request->divisi_karyawan;
        $employee->tanggal_masuk_kerja = $request->tanggal_masuk_kerja;
        $employee->tanggal_keluar_kerja = $request->tanggal_keluar_kerja;
        $employee->masa_kontrak = $request->masa_kontrak;
        $employee->gaji_karyawan = explodeRupiah($request->gaji_karyawan);
        $employee->keterangan_tambahan = $request->keterangan_tambahan;
        $file = $request->file('picture');
        if($file != null || !empty($file) || $file != ''){

            $originalName1 = $file->getClientOriginalName();
            $berkas = time().$request->nama_karyawan.$originalName1;
            $file->move('uploads/karyawan', $berkas);
            
            $employee->picture = $berkas;
        }
        $employee->save();

        //redirect ke create lagi setelah create
        if (isset($_POST['lagi'])) {
            return back()->with('success', 'Data berhasil di tambahkan');
        }

        return redirect('/admin/employee')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employees $employee)
    {
        if(auth()->user()->cannot('view', Employees::class)) abort(403, 'access denied');

        return view('hrga.employee.detail', compact(
            'employee'
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
        if(auth()->user()->cannot('update', Employees::class)) abort(403, 'access denied');

        $employee = Employees::find($id);
       
        return view('hrga.employee.edit', compact(
            'employee'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employees $employee)
    {
        if(auth()->user()->cannot('update', Employees::class)) abort(403, 'access denied');

        $validate = $this->validation($employee->id);

        $employee = Employees::find($employee->id);
        $employee->nik = $request->nik;
        $employee->tanggal_lahir_karyawan = $request->tanggal_lahir_karyawan;
        $employee->nama_karyawan = $request->nama_karyawan;
        $employee->nomer_hp_karyawan = $request->nomer_hp_karyawan;
        $employee->email_karyawan = $request->email_karyawan;
        $employee->alamat_karyawan = $request->alamat_karyawan;
        $employee->jabatan_karyawan = $request->jabatan_karyawan;
        $employee->divisi_karyawan = $request->divisi_karyawan;
        $employee->tanggal_masuk_kerja = $request->tanggal_masuk_kerja;
        $employee->tanggal_keluar_kerja = $request->tanggal_keluar_kerja;
        $employee->masa_kontrak = $request->masa_kontrak;
        $employee->gaji_karyawan = explodeRupiah($request->gaji_karyawan);
        $employee->keterangan_tambahan = $request->keterangan_tambahan;
        $employee->save();

        return redirect('/admin/employee')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employees $employee)
    {
        if(auth()->user()->cannot('delete', Employees::class)) abort(403, 'access denied');

        $employee = Employees::find($employee->id);
        $employee->delete();

        return redirect('/admin/employee');
    }

    private function validation($id = null)
    {
        $validate = request()->validate([
            'nik' => 'required|unique:employees,nik, '.$id,
            'tanggal_lahir_karyawan' => 'required',
            'nama_karyawan' => 'required',
            'nomer_hp_karyawan' => 'required',
            'alamat_karyawan' => 'required',
            'jabatan_karyawan' => 'required',
            'tanggal_masuk_kerja' => 'required',
            'divisi_karyawan' => 'required',
            'masa_kontrak' => 'required',
            'picture' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return $validate;
    }
}
