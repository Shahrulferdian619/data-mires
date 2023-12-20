<?php

namespace App\Http\Controllers\v2\BukuBesar;

use App\Http\Controllers\Controller;
use App\Models\v2\Master\Coa;
use App\Models\v2\Master\Tipecoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $akses_id_granted = array(1, 2, 5, 6, 12, 13);
            $user_id = Auth::user()->id;
            if (in_array($user_id, $akses_id_granted, TRUE)) {
                return $next($request);
            } else {
                abort(403, 'akses ditolak');
            }
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['coa'] = Coa::with('tipe')->active()->get();

        return view('v2.bukubesar.coa.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['tipeCoa'] = Tipecoa::all();

        return view('v2.bukubesar.coa.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataValidated = $request->validate([
            'coa_tipe_id' => 'required|numeric',
            'nomer_coa' => 'required|unique:second_mysql.coa,nomer_coa',
            'nama_coa' => 'required',
            'keterangan' => 'nullable|max:200',
        ]);

        Coa::create($dataValidated);

        return back()->with('sukses', 'DATA COA BERHASIL DITAMBAHKAN');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['tipeCoa'] = Tipecoa::all();
        $data['coa'] = Coa::findOrFail($id);

        return view('v2.bukubesar.edit', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['coa'] = Coa::findOrFail($id);
        $data['tipeCoa'] = Tipecoa::all();

        return view('v2.bukubesar.coa.edit', compact('data'));
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
        $dataValidated = $request->validate([
            'coa_tipe_id' => 'required|numeric',
            'nomer_coa' => 'required|unique:second_mysql.coa,nomer_coa,' . $id,
            'nama_coa' => 'required',
            'keterangan' => 'nullable',
        ]);

        Coa::findOrFail($id)->update($dataValidated);

        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
