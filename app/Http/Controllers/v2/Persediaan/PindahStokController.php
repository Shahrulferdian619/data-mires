<?php

namespace App\Http\Controllers\v2\Persediaan;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\Persediaan\PindahStokRequest;
use App\Jobs\UpdateStokJob;
use App\Jobs\v2\Telegram\SendNotifikasiJob;
use App\Models\Packet;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Persediaan\Barang;
use App\Models\v2\Persediaan\PindahStok;
use App\Models\v2\Persediaan\PindahStokBerkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use PDF;

class PindahStokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['pindah_stok'] = PindahStok::all();

        return view('v2.persediaan.pindah-stok.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['produk'] = Barang::produk()->get();
        $data['gudang'] = Gudang::all();
        $data['paket'] = Packet::all();

        return view('v2.persediaan.pindah-stok.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PindahStokRequest $request)
    {
        $dataPindahStok = PindahStok::create($request->except('rincian', 'berkas'));
        $dataPindahStok->rincianProduk()->createMany($request->input('rincian'));

        // cek apakah ada berkas yang diupload
        if ($request->hasFile('berkas.0.nama_berkas')) {
            $get_nama_file = $request->file('berkas.0.nama_berkas')->getClientOriginalName();
            $file = str_replace(['-', '/'], '_', $dataPindahStok->nomer_ref) . '_' . str_replace(' ', '', $get_nama_file);
            $request->file('berkas.0.nama_berkas')->storeAs('berkas_pindah_stok', $file);

            $berkasInvoice = new PindahStokBerkas([
                'nama_berkas' => $file
            ]);
            $dataPindahStok->berkas()->save($berkasInvoice);
        }

        // catat log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat pindah stok nomer : ' . $dataPindahStok->nomer_ref,
        ]);

        // kirim notif ke telegram
        $text = "<strong>PINDAH BARANG BARU</strong>\n\n"
            . "Nomer : <strong>" . $dataPindahStok->nomer_ref . "</strong>\n"
            . "Dibuat oleh : <strong>" . $dataPindahStok->dibuatOleh->name . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPindahStok->tanggal)) . "</strong>\n\n"
            . "Silahkan dicek terlebih dulu untuk selanjutkan bisa diproses.\n";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_sales_id'));

        return redirect(route('pindah-stok.index'))->with('sukses', 'DATA PINDAH STOK NOMER : ' . $dataPindahStok->nomer_ref . ' BERHASIL DIBUAT');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PindahStok $pindah_stok)
    {
        return view('v2.persediaan.pindah-stok.show', [
            'pindah_stok' => $pindah_stok
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PindahStok $pindah_stok)
    {
        return view('v2.persediaan.pindah-stok.edit', [
            'pindah_stok' => $pindah_stok,
            'gudang' => Gudang::all(),
            'produk' => Barang::produk()->get(),
            'paket' => Packet::all(),
            'countRincian' => $pindah_stok->rincianProduk->count(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PindahStokRequest $request, $id)
    {
        $dataPindahStok = PindahStok::find($id);

        // update header pindah stok
        PindahStok::find($id)->update($request->except('rincian', 'berkas'));

        // hapus rincian pindah stok dulu
        $dataPindahStok->rincianProduk()->delete();

        // input ulang rincian produk
        $dataPindahStok->rincianProduk()->createMany($request->input('rincian'));

        // simpan log aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Memperbarui pindah stok nomer : ' . $dataPindahStok->nomer_ref,
        ]);

        // kirim notif ke telegram
        $text = "<strong>PINDAH BARANG DIPERBARUI</strong>\n\n"
            . "Nomer : <strong>" . $dataPindahStok->nomer_ref . "</strong>\n"
            . "Diubah oleh : <strong>" . Auth::user()->name . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPindahStok->tanggal)) . "</strong>\n\n"
            . "Silahkan dicek terlebih dulu untuk selanjutkan bisa diproses.\n";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_sales_id'));

        return redirect(route('pindah-stok.show', $dataPindahStok->id))->with('sukses', 'DATA BERHASIL DIPERBARUI');
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

    public function prosesKirim(PindahStok $pindah_stok)
    {
        $pindah_stok->update([
            'status_proses' => 1,
            'tanggal_kirim' => date('Y-m-d'),
        ]);

        // proses update stok
        foreach ($pindah_stok->rincianProduk as $rincian) {
            UpdateStokJob::dispatchSync('kurang', $pindah_stok->gudang_asal_id, $rincian->produk_id, $rincian->kuantitas);
            UpdateStokJob::dispatchSync('tambah', $pindah_stok->gudang_tujuan_id, $rincian->produk_id, $rincian->kuantitas);
        }

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Memproses pindah stok dengan nomer : ' . $pindah_stok->nomer_ref
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>DATA PINDAH STOK DIPROSES</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $pindah_stok->nomer_ref . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($pindah_stok->tanggal)) . "</strong> \n"
            . "Diproses oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"
            . 'v2';

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_sales_id'));

        return redirect(route('pindah-stok.show', $pindah_stok->id))->with('sukses', 'PINDAH STOK BERHASIL DIPROSES');
    }

    public function printSj(PindahStok $pindah_stok)
    {
        $nomer_sj = 'SJ-' . $pindah_stok->nomer_ref;

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.persediaan.pindah-stok.print-sj', [
                'pindah_stok' => $pindah_stok,
                'nomer_sj' => $nomer_sj
            ]);

        return $pdf->stream();
    }

    public function print(PindahStok $pindah_stok)
    {
        $nomer_sj = 'SJ-' . $pindah_stok->nomer_ref;

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.persediaan.pindah-stok.print', [
                'pindah_stok' => $pindah_stok,
            ]);

        return $pdf->stream();
    }
}
