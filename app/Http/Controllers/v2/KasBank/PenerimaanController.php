<?php

namespace App\Http\Controllers\v2\KasBank;

use App\Http\Controllers\Controller;
use App\Models\v2\KasBank\Penerimaan;
use App\Models\v2\Master\Coa;
use App\Services\LogAktifitasService;
use App\Services\v2\Bukubesar\TransaksiBukubesarService;
use App\Services\v2\KasBank\TransaksiBukuBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PenerimaanController extends Controller
{
    protected $logAktifitasService;
    protected $transaksiBukuBankService;
    protected $transaksiBukubesarService;

    public function __construct(LogAktifitasService $logAktifitasService, TransaksiBukuBankService $transaksiBukuBankService, TransaksiBukubesarService $transaksiBukubesarService)
    {
        $this->logAktifitasService = $logAktifitasService;
        $this->transaksiBukuBankService = $transaksiBukuBankService;
        $this->transaksiBukubesarService = $transaksiBukubesarService;

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

    public function index()
    {
        $data['penerimaan'] = Penerimaan::all();

        return view('v2.kasbank.penerimaan.index', compact('data'));
    }

    public function create()
    {
        $data['bank'] = Coa::bank()->get();
        $data['akun'] = Coa::whereIn('coa_tipe_id', [1, 4, 6, 8, 9, 11, 15])->get();

        return view('v2.kasbank.penerimaan.create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'created_by' => Auth::user()->id,
            'nominal' => convertToDouble($request->input('nominal')),
            'rincian' => array_map(function ($item) {
                $item['nominal'] = convertToDouble($item['nominal']);

                return $item;
            }, $request->input('rincian')),
        ]);

        // validasi
        $request->validate(([
            'bank_id' => 'required|numeric',
            'nomer' => 'required|unique:second_mysql.kasbank_penerimaan',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'nominal' => 'required|numeric',
            'rincian.*.coa_id' => 'required|numeric',
            'rincian.*.nominal' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:pdf,word,jpeg,jpg,png|max:2048',
        ]));

        // input data penerimaan
        try {
            $penerimaan = Penerimaan::create($request->except('rincian', 'berkas')); // insert penerimaan
            $this->transaksiBukuBankService->createData($request, 'Debit', 'Penerimaan'); // insert buku bank
            $this->transaksiBukubesarService->createData([ // insert buku besar
                'coa_id' => $penerimaan->bank_id,
                'sumber_id' => $penerimaan->id,
                'tahun' => date('Y', strtotime($penerimaan->tanggal)),
                'tanggal' => $penerimaan->tanggal,
                'nomer_sumber' => $penerimaan->nomer,
                'sumber_transaksi' => 'KASBANK_PENERIMAAN',
                'nominal' => $penerimaan->nominal,
                'tipe_mutasi' => 'D',
                'keterangan' => $penerimaan->keterangan
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }

        // input rincian penerimaan
        try {
            $penerimaan->rincianAkun()->createMany($request->input('rincian'));
            foreach ($penerimaan->rincianAkun as $rincian) { // insert buku besar
                $this->transaksiBukubesarService->createData([
                    'coa_id' => $rincian->coa_id,
                    'sumber_id' => $penerimaan->id,
                    'tahun' => date('Y', strtotime($penerimaan->tanggal)),
                    'tanggal' => $penerimaan->tanggal,
                    'nomer_sumber' => $penerimaan->nomer,
                    'sumber_transaksi' => 'KASBANK_PENERIMAAN',
                    'nominal' => $rincian->nominal,
                    'tipe_mutasi' => 'K',
                    'keterangan' => $rincian->keterangan
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $penerimaan->delete();
            abort(500);
        }

        // jika ada berkas
        if ($request->has('berkas')) {
            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerPenerimaan = str_replace(['/', '-', ' '], '_', $penerimaan->nomer);
                    $berkas['nama_berkas']->storeAs('berkas_kasbank_penerimaan', $nomerPenerimaan . '_' . $filename);
                    $penerimaan->rincianBerkas()->create([
                        'nama_berkas' => $nomerPenerimaan . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    $penerimaan->delete();
                    abort(500);
                }
            }
        }

        // catat log
        $this->logAktifitasService->createLog('Membuat penerimaan kasbank nomer : ' . $penerimaan->nomer);

        return redirect(route('kasbank.penerimaan.index'))->with('sukses', 'DATA BERHASIL DITAMBAHKAN');
    }

    public function edit($id)
    {
        $data['penerimaan'] = Penerimaan::findOrFail($id);
        $data['bank'] = Coa::bank()->get();
        $data['akun'] = Coa::whereIn('coa_tipe_id', [8, 9, 13, 14])->get();

        return view('v2.kasbank.penerimaan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = Penerimaan::findOrFail($id);

        $request->merge([
            'updated_by' => Auth::user()->id,
            'nominal' => convertToDouble($request->input('nominal'))
        ]);

        // validasi
        $request->validate(([
            'bank_id' => 'required|numeric',
            'nomer' => 'required|unique:second_mysql.kasbank_penerimaan,nomer,' . $id,
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'nominal' => 'required|numeric',
            'rincian.*.coa_id' => 'required|numeric',
            'rincian.*.nominal' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:pdf,word,jpeg,jpg,png|max:2048',
        ]));

        // update data penerimaan
        try {
            $this->transaksiBukuBankService->deleteData($penerimaan->nomer, 'Penerimaan'); // delete buku bank
            $this->transaksiBukubesarService->deleteData([
                'sumber_id' => $penerimaan->id,
                'sumber_transaksi' => 'KASBANK_PENERIMAAN',
            ]); // delete buku besar

            $penerimaan->update($request->except('rincian', 'berkas')); // update penerimaan
            $this->transaksiBukuBankService->createData($request, 'Debit', 'Penerimaan'); // insert buku bank
            $this->transaksiBukubesarService->createData([ // insert buku besar
                'coa_id' => $request->input('bank_id'),
                'sumber_id' => $penerimaan->id,
                'tahun' => date('Y', strtotime($request->input('tanggal'))),
                'tanggal' => $request->input('tanggal'),
                'nomer_sumber' => $request->input('nomer'),
                'sumber_transaksi' => 'KASBANK_PENERIMAAN',
                'nominal' => $request->input('nominal'),
                'tipe_mutasi' => 'D',
                'keterangan' => $request->input('keterangan')
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('sukses', 'Error, silahkan hubungi administrator');
        }

        // hapus rincian dan input ulang
        try {
            $penerimaan->rincianAkun()->delete();
            $penerimaan->rincianAkun()->createMany($request->input('rincian'));
            foreach ($penerimaan->rincianAkun as $rincian) { // insert buku besar
                $this->transaksiBukubesarService->createData([
                    'coa_id' => $rincian->coa_id,
                    'sumber_id' => $penerimaan->id,
                    'tahun' => date('Y', strtotime($penerimaan->tanggal)),
                    'tanggal' => $penerimaan->tanggal,
                    'nomer_sumber' => $penerimaan->nomer,
                    'sumber_transaksi' => 'KASBANK_PENERIMAAN',
                    'nominal' => $rincian->nominal,
                    'tipe_mutasi' => 'K',
                    'keterangan' => $rincian->catatan
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        // jika ada berkas
        if ($request->has('berkas')) {
            // delete berkas
            foreach ($penerimaan->rincianBerkas as $berkas) {
                Storage::delete('berkas_kasbank_penerimaan/' . $berkas->nama_berkas);
            }
            $penerimaan->rincianBerkas()->delete();

            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerPenerimaan = str_replace(['/', '-', ' '], '_', $penerimaan->nomer);
                    $berkas['nama_berkas']->storeAs('berkas_kasbank_penerimaan', $nomerPenerimaan . '_' . $filename);
                    $penerimaan->rincianBerkas()->create([
                        'nama_berkas' => $nomerPenerimaan . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    Storage::delete('berkas_kasbank_penerimaan/' . $penerimaan->nomer . '*'); // delete berkas jika gagal input
                    $penerimaan->delete();
                    abort(500);
                }
            }
        }

        // catat log
        $this->logAktifitasService->createLog('Mengubah penerimaan kasbank nomer : ' . $penerimaan->nomer);

        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    public function destroy($id)
    {
        $penerimaan = Penerimaan::findOrFail($id);

        // delete berkas jika ada
        if ($penerimaan->rincianBerkas()->exists()) {
            foreach ($penerimaan->rincianBerkas as $berkas) {
                Storage::delete('berkas_kasbank_penerimaan/' . $berkas->nama_berkas);
            }
        }

        $this->transaksiBukuBankService->deleteData($penerimaan->nomer, 'Penerimaan'); // delete buku bank
        $this->transaksiBukubesarService->deleteData([
            'sumber_id' => $penerimaan->id,
            'sumber_transaksi' => 'KASBANK_PENERIMAAN',
        ]); // delete buku besar
        $penerimaan->delete(); // delete penerimaan

        // catat log
        $this->logAktifitasService->createLog('Menghapus penerimaan kasbank nomer : ' . $penerimaan->nomer);

        return redirect(route('kasbank.penerimaan.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
    }

    public function downloadBerkas($namaBerkas)
    {
        // catat log
        $this->logAktifitasService->createLog('Download berkas penerimaan kasbank : ' . $namaBerkas);

        return Storage::download('berkas_kasbank_penerimaan/' . $namaBerkas);
    }
}
