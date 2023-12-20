<?php

namespace App\Http\Controllers\v2\Pembelian;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\Pembelian\StorePesananPembelianRequest;
use App\Http\Requests\v2\Pembelian\UpdatePesananPembelianRequest;
use App\Models\v2\Master\Supplier;
use App\Models\v2\Pembelian\PermintaanPembelian;
use App\Models\v2\Pembelian\PesananPembelian;
use App\Models\v2\Pembelian\PesananPembelianRinci;
use App\Services\v2\Pembelian\PesananPembelianService;
use Illuminate\Http\Request;
use PDF;

class PesananPembelianController extends Controller
{
    protected $pesananService;

    public function __construct(PesananPembelianService $pesananService)
    {
        $this->pesananService = $pesananService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pesananPembelian'] = PesananPembelian::with('rincianItem.item')
            ->where('status_delete', 0)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('v2.pembelian.pesanan-pembelian.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['permintaanPembelian'] = PermintaanPembelian::diapprove()->belumDiproses()->get();
        $data['supplier'] = Supplier::active()->get();

        return view('v2.pembelian.pesanan-pembelian.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePesananPembelianRequest $request)
    {
        $po = $this->pesananService->createData($request);

        if ($po instanceof PesananPembelian) {
            return redirect(route('pembelian.pesanan-pembelian.show', $po->id))
                ->with('sukses', 'DATA BERHASIL DIBUAT');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pesananPembelian = PesananPembelian::with('rincianItem.item', 'rincianBerkas')->findOrFail($id);

        return view('v2.pembelian.pesanan-pembelian.show', compact('pesananPembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pesananPembelian = PesananPembelian::findOrFail($id);
        $data['permintaanPembelian'] = PermintaanPembelian::diapprove()->belumDiproses()->get();
        $data['supplier'] = Supplier::active()->get();
        $data['rincianItem'] = PesananPembelianRinci::where('pesanan_pembelian_id', $id)->get();

        if ($pesananPembelian->approve_direktur == 1 || $pesananPembelian->approve_komisaris == 1) {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP');
        }

        if ($pesananPembelian->status_proses == 0) {
            return view('v2.pembelian.pesanan-pembelian.edit', compact('pesananPembelian', 'data'));
        } else {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePesananPembelianRequest $request, $id)
    {
        $result = $this->pesananService->updateData($request, $id);

        if ($result === true) {
            return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesananPembelian = PesananPembelian::findOrFail($id);
        $result = $this->pesananService->deleteData($pesananPembelian);

        if ($result === true) {
            return redirect(route('pembelian.pesanan-pembelian.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
        } else {
            abort('403', 'DATA SUDAH DIPROSES');
        }
    }

    public function revisiPengajuan($id)
    {
        $pesananPembelian = PesananPembelian::findOrFail($id);
        $data['permintaanPembelian'] = PermintaanPembelian::diapprove()->belumDiproses()->get();
        $data['supplier'] = Supplier::active()->get();
        $data['rincianItem'] = PesananPembelianRinci::where('pesanan_pembelian_id', $id)->get();

        if ($pesananPembelian->status_proses == 10) {
            abort(403, 'DATA SUDAH DITUTUP');
        }

        return view('v2.pembelian.pesanan-pembelian.revisi', compact('pesananPembelian', 'data'));
    }

    public function createRevisi(UpdatePesananPembelianRequest $request, $id)
    {
        $po = $this->pesananService->revisiData($request, $id);

        return redirect(route('pembelian.pesanan-pembelian.show', $po->id))->with('sukses', 'REVISI DATA BERHASIL');
    }

    public function downloadBerkas($nama_berkas)
    {
        return $this->pesananService->downloadFile($nama_berkas);
    }

    public function approvePengajuan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|max:255',
        ]);

        $pengajuanPO = PesananPembelian::findOrFail($id);
        if ($pengajuanPO->status_proses != 0) {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP');
        }

        $result = $this->pesananService->approvePengajuan($request, $id);

        if ($result === true) {
            return back()->with('sukses', 'PENGAJUAN BERHASIL DIPROSES');
        } else {
            return $result;
        }
    }

    public function rejectPengajuan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|max:255',
        ]);

        $pengajuanPO = PesananPembelian::findOrFail($id);
        if ($pengajuanPO->status_proses != 0 || $pengajuanPO->status_proses != 2) {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP');
        }

        $result = $this->pesananService->rejectPengajuan($request, $id);

        if ($result === true) {
            return back()->with('sukses', 'PENGAJUAN BERHASIL DIPROSES');
        } else {
            return $result;
        }
    }

    public function printTtd($id)
    {
        $po = PesananPembelian::findOrFail($id);

        if ($po->approve_direktur == 0 || $po->approve_komisaris == 0) {
            abort(403, 'PENGAJUAN BELUM DISETUJUI');
        }

        $pdf = PDF::loadHTML(view('v2.pembelian.pesanan-pembelian.print-ttd', compact('po')));

        return $pdf->stream();
    }

    public function printNonTtd($id)
    {
        return $id; 
    }

    public function getDetil($id)
    {
        $po = PesananPembelian::with(['rincianItem' => function ($query) {
            return $query->with('item');
        }])->findOrFail($id);

        return response()->json($po, 200);
    }
}
