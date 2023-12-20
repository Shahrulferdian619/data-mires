<?php

namespace App\Http\Controllers\v2\KasBank;

use App\Http\Controllers\Controller;
use App\Models\v2\KasBank\Pembayaran;
use App\Models\v2\Master\Coa;
use App\Services\LogAktifitasService;
use App\Services\v2\Bukubesar\TransaksiBukubesarService;
use App\Services\v2\KasBank\TransaksiBukuBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
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
        $data['pembayaran'] = Pembayaran::all();

        return view('v2.kasbank.pembayaran.index', compact('data'));
    }

    public function create()
    {
        $data['bank'] = Coa::bank()->get();
        $data['akun'] = Coa::whereIn('coa_tipe_id', [1, 4, 6, 8, 9, 13, 14])->get();

        return view('v2.kasbank.pembayaran.create', compact('data'));
    }

    public function store(Request $request)
    {
        // sebelum validasi
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
            'nomer' => 'required|unique:second_mysql.kasbank_pembayaran',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'nominal' => 'required|numeric',
            'rincian.*.coa_id' => 'required|numeric',
            'rincian.*.nominal' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:pdf,word,jpeg,jpg,png|max:2048',
        ]));

        // input data pembayaran
        try {
            $pembayaran = Pembayaran::create($request->except('rincian', 'berkas')); // insert pembayaran
            $this->transaksiBukuBankService->createData($request, 'Kredit', 'Pembayaran'); // insert buku bank
            $this->transaksiBukubesarService->createData([ // insert buku besar
                'coa_id' => $pembayaran->bank_id,
                'sumber_id' => $pembayaran->id,
                'tahun' => date('Y', strtotime($pembayaran->tanggal)),
                'tanggal' => $pembayaran->tanggal,
                'nomer_sumber' => $pembayaran->nomer,
                'sumber_transaksi' => 'KASBANK_PEMBAYARAN',
                'nominal' => $pembayaran->nominal,
                'tipe_mutasi' => 'K',
                'keterangan' => $pembayaran->keterangan
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }

        // insert rincian pembayaran
        try {
            $pembayaran->rincianAkun()->createMany($request->input('rincian'));
            foreach ($pembayaran->rincianAkun as $rincian) { // insert buku besar
                $this->transaksiBukubesarService->createData([
                    'coa_id' => $rincian->coa_id,
                    'sumber_id' => $pembayaran->id,
                    'tahun' => date('Y', strtotime($pembayaran->tanggal)),
                    'tanggal' => $pembayaran->tanggal,
                    'nomer_sumber' => $pembayaran->nomer,
                    'sumber_transaksi' => 'KASBANK_PEMBAYARAN',
                    'nominal' => $rincian->nominal,
                    'tipe_mutasi' => 'D',
                    'keterangan' => $rincian->catatan
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $pembayaran->delete();
            abort(500);
        }

        // jika ada berkas
        if ($request->has('berkas')) {
            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerPembayaran = str_replace(['/', '-', ' '], '_', $pembayaran->nomer);
                    $berkas['nama_berkas']->storeAs('berkas_kasbank_pembayaran', $nomerPembayaran . '_' . $filename);
                    $pembayaran->rincianBerkas()->create([
                        'nama_berkas' => $nomerPembayaran . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    Storage::delete('berkas_kasbank_pembayaran/' . $pembayaran->nomer . '*'); // delete berkas jika gagal input
                    $pembayaran->delete();
                    abort(500);
                }
            }
        }

        // catat log
        $this->logAktifitasService->createLog('Membuat pembayaran kasbank nomer : ' . $pembayaran->nomer);

        return redirect(route('kasbank.pembayaran.index'))->with('sukses', 'DATA BERHASIL DITAMBAHKAN');
    }
    public function edit($id)
    {
        $data['pembayaran'] = Pembayaran::findOrFail($id);
        $data['bank'] = Coa::bank()->get();
        $data['akun'] = Coa::whereIn('coa_tipe_id', [1, 4, 6, 8, 9, 13, 14])->get();

        return view('v2.kasbank.pembayaran.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        // sebelum validasi
        $request->merge([
            'updated_by' => Auth::user()->id,
            'nominal' => convertToDouble($request->input('nominal'))
        ]);

        // validasi
        $request->validate(([
            'bank_id' => 'required|numeric',
            'nomer' => 'required|unique:second_mysql.kasbank_pembayaran,nomer,' . $id,
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'nominal' => 'required|numeric',
            'rincian.*.coa_id' => 'required|numeric',
            'rincian.*.nominal' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:pdf,word,jpeg,jpg,png|max:2048',
        ]));

        // update data pembayaran
        try {
            $this->transaksiBukuBankService->deleteData($pembayaran->nomer, 'Pembayaran'); // delete buku bank
            $this->transaksiBukubesarService->deleteData([
                'sumber_id' => $pembayaran->id,
                'sumber_transaksi' => 'KASBANK_PEMBAYARAN',
            ]); // delete buku besar

            $pembayaran->update($request->except('rincian', 'berkas')); // update pembayaran
            $this->transaksiBukuBankService->createData($request, 'Kredit', 'Pembayaran'); // insert buku bank
            $this->transaksiBukubesarService->createData([ // insert buku besar
                'coa_id' => $request->input('bank_id'),
                'sumber_id' => $pembayaran->id,
                'tahun' => date('Y', strtotime($request->input('tanggal'))),
                'tanggal' => $request->input('tanggal'),
                'nomer_sumber' => $request->input('nomer'),
                'sumber_transaksi' => 'KASBANK_PEMBAYARAN',
                'nominal' => $request->input('nominal'),
                'tipe_mutasi' => 'K',
                'keterangan' => $request->input('keterangan')
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }

        // hapus rincian dan input ulang
        try {
            $pembayaran->rincianAkun()->delete(); // delete rincian
            $pembayaran->rincianAkun()->createMany($request->input('rincian')); // insert rincian
            foreach ($pembayaran->rincianAkun as $rincian) { // insert buku besar
                $this->transaksiBukubesarService->createData([
                    'coa_id' => $rincian->coa_id,
                    'sumber_id' => $pembayaran->id,
                    'tahun' => date('Y', strtotime($pembayaran->tanggal)),
                    'tanggal' => $pembayaran->tanggal,
                    'nomer_sumber' => $pembayaran->nomer,
                    'sumber_transaksi' => 'KASBANK_PEMBAYARAN',
                    'nominal' => $rincian->nominal,
                    'tipe_mutasi' => 'D',
                    'keterangan' => $rincian->keterangan
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        // jika ada berkas
        if ($request->has('berkas')) {
            // delete berkas
            foreach ($pembayaran->rincianBerkas as $berkas) {
                Storage::delete('berkas_kasbank_pembayaran/' . $berkas->nama_berkas);
            }
            $pembayaran->rincianBerkas()->delete();

            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerPembayaran = str_replace(['/', '-', ' '], '_', $pembayaran->nomer);
                    $berkas['nama_berkas']->storeAs('berkas_kasbank_pembayaran', $nomerPembayaran . '_' . $filename);
                    $pembayaran->rincianBerkas()->create([
                        'nama_berkas' => $nomerPembayaran . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    Storage::delete('berkas_kasbank_pembayaran/' . $pembayaran->nomer . '*'); // delete berkas jika gagal input
                    $pembayaran->delete();
                    abort(500);
                }
            }
        }

        // catat log
        $this->logAktifitasService->createLog('Mengubah pembayaran kasbank nomer : ' . $pembayaran->nomer);

        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        // delete berkas jika ada
        if ($pembayaran->rincianBerkas()->exists()) {
            foreach ($pembayaran->rincianBerkas as $berkas) {
                Storage::delete('berkas_kasbank_pembayaran/' . $berkas->nama_berkas);
            }
        }

        $this->transaksiBukuBankService->deleteData($pembayaran->nomer, 'Pembayaran'); // delete buku bank
        $this->transaksiBukubesarService->deleteData([
            'sumber_id' => $pembayaran->id,
            'sumber_transaksi' => 'KASBANK_PEMBAYARAN',
        ]); // delete buku besar
        $pembayaran->delete(); // delete data

        // catat log
        $this->logAktifitasService->createLog('Menghapus pembayaran kasbank nomer : ' . $pembayaran->nomer);

        return redirect(route('kasbank.pembayaran.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
    }

    public function downloadBerkas($namaBerkas)
    {
        // catat log
        $this->logAktifitasService->createLog('Download berkas pembayaran kasbank : ' . $namaBerkas);

        return Storage::download('berkas_kasbank_pembayaran/' . $namaBerkas);
    }

    public function printPdf($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $tipeKasbank = substr($pembayaran->nomer, 0, 1);

        if ($tipeKasbank == "B") {
            // format print BBK
            return view("v2.kasbank.pembayaran.print.bbk", compact('pembayaran'));
        } elseif ($tipeKasbank == "K") {
            // format print BKK
            return view("v2.kasbank.pembayaran.print.bkk", compact('pembayaran'));
        }
    }
}
