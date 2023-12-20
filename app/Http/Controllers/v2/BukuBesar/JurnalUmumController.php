<?php

namespace App\Http\Controllers\v2\BukuBesar;

use App\Http\Controllers\Controller;
use App\Models\v2\Bukubesar\JurnalUmum;
use App\Models\v2\Master\Coa;
use App\Services\LogAktifitasService;
use App\Services\v2\Bukubesar\TransaksiBukubesarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalUmumController extends Controller
{
    protected $transaksiBukubesarService;
    protected $logAktifitasService;

    public function __construct(TransaksiBukubesarService $transaksiBukubesarService, LogAktifitasService $logAktifitasService)
    {
        $this->transaksiBukubesarService = $transaksiBukubesarService;
        $this->logAktifitasService = $logAktifitasService;

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
        $data['jurnal'] = JurnalUmum::all();

        return view('v2.bukubesar.jurnal-umum.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['akun'] = Coa::active()->get();

        return view('v2.bukubesar.jurnal-umum.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // prepare validasi
        $request->merge([
            'created_by' => Auth::user()->id,
            'total' => convertToDouble($request->input('total')),
            'total_kredit' => convertToDouble($request->input('total_kredit')),
            'rincian' => array_map(function ($item) {
                $item['debit'] = convertToDouble($item['debit']);
                $item['kredit'] = convertToDouble($item['kredit']);

                return $item;
            }, $request->input('rincian')),
        ]);

        // validasi
        $request->validate([
            'nomer' => 'required|unique:second_mysql.jurnal_umum',
            'total' => 'required|numeric|same:total_kredit',
            'keterangan' => 'nullable',
            'rincian.*.coa_id' => 'required|numeric',
            'rincian.*.debit' => 'required|numeric',
            'rincian.*.kredit' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // proses insert
        try {
            $jurnal = JurnalUmum::create($request->except('rincian', 'berkas', 'total_kredit'));

            try {
                $jurnal->rincian()->createMany($request->input('rincian')); // insert rincian

                foreach ($jurnal->rincian as $rincian) { // insert buku besar
                    $data['coa_id'] = $rincian->coa_id;
                    $data['sumber_id'] = $jurnal->id;
                    $data['tahun'] = date('Y', strtotime($jurnal->tanggal));
                    $data['tanggal'] = $jurnal->tanggal;
                    $data['nomer_sumber'] = $jurnal->nomer;
                    $data['sumber_transaksi'] = 'JURNAL_UMUM';
                    if ($rincian->debit != 0) {
                        $data['nominal'] = $rincian->debit;
                        $data['tipe_mutasi'] = 'D';
                    } else {
                        $data['nominal'] = $rincian->kredit;
                        $data['tipe_mutasi'] = 'K';
                    }
                    $data['keterangan'] = $rincian->catatan;
                    $result = $this->transaksiBukubesarService->createData($data);
                    if ($result == false) { // handling error, revert data
                        $jurnal->delete();
                        exit('Ada kesalahan pada server, silahkan hubungi administrator');
                    }
                }
            } catch (\Exception $e) {
                $jurnal->delete();
                exit($e->getMessage());
            }
        } catch (\Exception $e) {
            $jurnal->delete();
            exit($e->getMessage());
        }

        // jika ada berkas, upload & simpan
        if ($request->has('berkas')) {
            foreach ($request->file('berkas') as $berkas) {
                $fileName = str_replace(['-', '/', ' ', '(', ')'], '_', $berkas['nama_berkas']->getClientOriginalname());
                $nomerJurnal = str_replace(['-', '/', ' '], '_', $jurnal->nomer);
                try {
                    $berkas['nama_berkas']->storeAs('berkas_jurnal_umum', $nomerJurnal . '_' . $fileName);
                } catch (\Exception $e) {
                    Storage::delete('berkas_jurnal_umum/' . $nomerJurnal . $fileName);
                    exit($e->getMessage());
                }

                try {
                    $jurnal->berkas()->create([
                        'nama_berkas' => $nomerJurnal . '_' . $fileName,
                    ]);
                } catch (\Exception $e) {
                    $jurnal->berkas()->delete();
                    exit($e->getMessage());
                }
            }
        }

        // create log
        $this->logAktifitasService->createLog('Membuat jurnal umum : ' . $jurnal->nomer);

        return redirect(route('bukubesar.jurnal-umum.index'))->with('sukses', 'DATA BERHASIL DIBUAT');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(JurnalUmum $jurnal_umum)
    {
        $data['akun'] = Coa::active()->get();
        $data['jurnal_umum'] = $jurnal_umum;

        return view('v2.bukubesar.jurnal-umum.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JurnalUmum $jurnal_umum)
    {
        // prepare validation
        $request->merge([
            'updated_by' => Auth::user()->id,
            'total' => convertToDouble($request->input('total')),
            'total_kredit' => convertToDouble($request->input('total_kredit')),
            'rincian' => array_map(function ($item) {
                $item['debit'] = convertToDouble($item['debit']);
                $item['kredit'] = convertToDouble($item['kredit']);

                return $item;
            }, $request->input('rincian')),
        ]);

        // validasi
        $request->validate([
            'nomer' => 'required|unique:second_mysql.jurnal_umum,nomer,' . $jurnal_umum->id,
            'total' => 'required|numeric|same:total_kredit',
            'rincian.*.coa_id' => 'required|numeric',
            'rincian.*.debit' => 'required|numeric',
            'rincian.*.kredit' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'keterangan' => 'nullable'
        ]);

        // update jurnal umum
        try {
            $jurnal_umum->update($request->except('rincian', 'berkas', 'total_kredit'));

            $this->transaksiBukubesarService->deleteData([
                'sumber_id' => $jurnal_umum->id,
                'sumber_transaksi' => 'JURNAL_UMUM'
            ]); // delete buku besar
            $jurnal_umum->rincian()->delete(); // delete rincian

            $jurnal_umum->rincian()->createMany($request->input('rincian')); // insert rincian
            foreach ($jurnal_umum->rincian as $rincian) { // insert buku besar
                $data['coa_id'] = $rincian->coa_id;
                $data['sumber_id'] = $jurnal_umum->id;
                $data['tahun'] = date('Y', strtotime($jurnal_umum->tanggal));
                $data['tanggal'] = $jurnal_umum->tanggal;
                $data['nomer_sumber'] = $jurnal_umum->nomer;
                $data['sumber_transaksi'] = 'JURNAL_UMUM';
                if ($rincian->debit != 0) {
                    $data['nominal'] = $rincian->debit;
                    $data['tipe_mutasi'] = 'D';
                } else {
                    $data['nominal'] = $rincian->kredit;
                    $data['tipe_mutasi'] = 'K';
                }
                $data['keterangan'] = $rincian->catatan;
                $result = $this->transaksiBukubesarService->createData($data);
                if ($result == false) { // handling error, revert data
                    $jurnal_umum->rincian()->delete();
                    exit('Ada kesalahan pada server, silahkan hubungi administrator');
                }
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        // jika ada berkas baru diupload
        if ($request->has('berkas')) {
            // delete data berkas dulu
            foreach ($jurnal_umum->berkas as $berkas) {
                Storage::delete('berkas_jurnal_umum/' . $berkas->nama_berkas);
            }
            $jurnal_umum->berkas()->delete();

            foreach ($request->file('berkas') as $berkas) {
                $fileName = str_replace(['-', '/', ' ', '(', ')'], '_', $berkas['nama_berkas']->getClientOriginalname());
                $nomerJurnal = str_replace(['-', '/', ' '], '_', $jurnal_umum->nomer);
                try {
                    $berkas['nama_berkas']->storeAs('berkas_jurnal_umum', $nomerJurnal . '_' . $fileName);
                } catch (\Exception $e) {
                    Storage::delete('berkas_jurnal_umum/' . $nomerJurnal . $fileName);
                    exit($e->getMessage());
                }

                try {
                    $jurnal_umum->berkas()->create([
                        'nama_berkas' => $nomerJurnal . '_' . $fileName,
                    ]);
                } catch (\Exception $e) {
                    $jurnal_umum->berkas()->delete();
                    exit($e->getMessage());
                }
            }
        }

        // create log
        $this->logAktifitasService->createLog('Merubah jurnal umum : ' . $jurnal_umum->nomer);

        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(JurnalUmum $jurnal_umum)
    {
        // delete buku besar
        $result = $this->transaksiBukubesarService->deleteData([
            'sumber_id' => $jurnal_umum->id,
            'sumber_transaksi' => 'JURNAL_UMUM'
        ]);

        if ($result === true) {
            // delete data
            $jurnal_umum->delete();

            // create log
            $this->logAktifitasService->createLog('Menghapus jurnal umum : ' . $jurnal_umum->nomer);

            return redirect(route('bukubesar.jurnal-umum.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
        } else {
            exit('Ada kesalahan pada server, silahkan hubungi administrator');
        }
    }

    public function downloadBerkas($nama_berkas)
    {
        //return $nama_berkas;
        return Storage::download('berkas_jurnal_umum/' . $nama_berkas);
    }
}
