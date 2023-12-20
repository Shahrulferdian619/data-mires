<?php

namespace App\Http\Controllers\v2\Pembelian;

use PDF;
use App\Http\Controllers\Controller;
use App\Http\Requests\v2\Pembelian\PermintaanPembelianRequest;
use App\Jobs\SendTelegramJob;
use App\Jobs\v2\Telegram\SendNotifikasiJob;
use App\Models\Pmtpembelian;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Pembelian\PermintaanPembelian;
use App\Models\v2\Persediaan\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermintaanPembelianController extends Controller
{
    public $chat_id_purchasing = '-998146755';

    public function index(Request $request)
    {
        if ($request->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        return view('v2.pembelian.permintaan-pembelian.index', [
            'permintaan' => PermintaanPembelian::with('rincianPermintaan.item')->getData()->get(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Pmtpembelian::class)) abort('403', 'access denied');

        return view('v2.pembelian.permintaan-pembelian.create', [
            'item' => Barang::all()
        ]);
    }

    public function store(PermintaanPembelianRequest $request)
    {
        if ($request->user()->cannot('create', Pmtpembelian::class)) abort('403', 'access denied');

        $dataPermintaanPembelian = PermintaanPembelian::create($request->except('rincian', 'berkas')); //insert header permintaan pembelian
        $dataPermintaanPembelian->rincianPermintaan()->createMany($request->input('rincian')); //insert rincian permintaan

        //jika ada berkas, upload dan simpan ke tabel
        if ($request->hasFile('berkas.*.nama_berkas')) {
            foreach ($request->file('berkas') as $file) {
                $fileName = str_replace(['-', '/'], '_', $dataPermintaanPembelian->nomer_permintaan_pembelian) . '_' . uniqid() . '.' . $file['nama_berkas']->getClientOriginalExtension();
                $file['nama_berkas']->storeAs('berkas_permintaan_pembelian/', $fileName);
                $dataPermintaanPembelian->berkasPermintaan()->create([
                    'nama_berkas' => $fileName
                ]);
            }
        }

        // catat log 
        LogAktifitas::create([
            'nama_user' => $dataPermintaanPembelian->dibuatOleh->name,
            'nama_aktifitas' => 'Membuat permintaan pembelian nomer : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian,
        ]);

        //kirim notif ke telegram
        $text = "<strong>PENGAJUAN PR BARU</strong>\n\n"
            . "Nomer : <strong>" . $dataPermintaanPembelian->nomer_permintaan_pembelian . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPermintaanPembelian->tanggal)) . "</strong>\n"
            . "Dibuat oleh : <strong>" . $dataPermintaanPembelian->dibuatOleh->name . "</strong>\n\n"
            . "Mohon dapat dicek untuk ditindaklanjuti.\n"
            . "Terima kasih\n\n"
            . "Link : <strong>" . route('pembelian.permintaan-pembelian.show', $dataPermintaanPembelian->id) . "</strong>";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        return redirect(route('pembelian.permintaan-pembelian.index'))->with('sukses', 'DATA PERMINTAAN PEMBELIAN NOMER : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian . ' BERHASIL DIBUAT');
    }

    public function show(PermintaanPembelian $permintaan_pembelian, Request $request)
    {
        if ($request->user()->cannot('view', Pmtpembelian::class)) abort('403', 'access denied');

        return view('v2.pembelian.permintaan-pembelian.show', [
            'permintaan' => $permintaan_pembelian,
        ]);
    }

    public function edit(PermintaanPembelian $permintaan_pembelian, Request $request)
    {
        if ($request->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        // jika permintaan pembelian sudah diproses/disetujui/tidak disetujui, maka tidak bisa edit
        if ($permintaan_pembelian->status_proses == 1 || $permintaan_pembelian->approve_direktur == 1 || $permintaan_pembelian->approve_direktur == 2) {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP/DITOLAK');
        }

        return view('v2.pembelian.permintaan-pembelian.edit', [
            'permintaan' => $permintaan_pembelian,
            'countRincian' => count($permintaan_pembelian->rincianPermintaan),
            'item' => Barang::all(),
        ]);
    }

    public function update(PermintaanPembelianRequest $request, $id)
    {
        if ($request->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        $dataPermintaanPembelian = PermintaanPembelian::find($id);

        // update header permintaan
        PermintaanPembelian::find($id)->update($request->except('rincian', 'berkas'));

        // hapus rincian permintaan
        $dataPermintaanPembelian->rincianPermintaan()->delete();

        // input ulang rincian permintaan
        $dataPermintaanPembelian->rincianPermintaan()->createMany($request->input('rincian'));

        // upload berkas & hapus berkas jika ada file baru 
        if ($request->hasFile('berkas.*.nama_berkas')) {
            foreach ($dataPermintaanPembelian->berkasPermintaan as $file) { //proses hapus berkas lama
                Storage::delete('berkas_permintaan_pembelian/' . $file->nama_berkas);
            }

            $dataPermintaanPembelian->berkasPermintaan()->delete(); //hapus rincian berkas permintaan

            foreach ($request->file('berkas') as $file) { //proses upload berkas baru
                $fileName = str_replace(['-', '/'], '_', $dataPermintaanPembelian->nomer_permintaan_pembelian) . '_' . uniqid() . '.' . $file['nama_berkas']->getClientOriginalExtension();
                $file['nama_berkas']->storeAs('berkas_permintaan_pembelian/', $fileName);
                $dataPermintaanPembelian->berkasPermintaan()->create([
                    'nama_berkas' => $fileName
                ]);
            }
        }

        // simpan log aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Memperbarui Permintaan Pembelian nomer : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian,
        ]);

        //kirim notif ke telegram
        $text = "<strong>PENGAJUAN PR DIPERBARUI</strong>\n\n"
            . "Nomer : <strong>" . $dataPermintaanPembelian->nomer_permintaan_pembelian . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPermintaanPembelian->tanggal)) . "</strong>\n"
            . "Diubah oleh : <strong>" . $dataPermintaanPembelian->dibuatOleh->name . "</strong>\n\n"
            . "Mohon dapat dicek ulang untuk ditindaklanjuti.\n"
            . "Terima kasih\n\n"
            . "Link : <strong>" . route('pembelian.permintaan-pembelian.show', $dataPermintaanPembelian->id) . "</strong>";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        return redirect(route('pembelian.permintaan-pembelian.show', $dataPermintaanPembelian->id))->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    public function destroy($id, Request $request)
    {
        if ($request->user()->cannot('delete', Pmtpembelian::class)) abort('403', 'access denied');

        $dataPermintaanPembelian = PermintaanPembelian::find($id);

        if ($dataPermintaanPembelian->status_proses != 0) {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP/DITOLAK');
        }

        //hapus berkas
        foreach ($dataPermintaanPembelian->berkasPermintaan as $berkas) {
            Storage::delete('berkas_permintaan_pembelian/' . $berkas->nama_berkas);
        }

        //hapus data rincian dan berkas
        $dataPermintaanPembelian->delete();

        // catat log 
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menghapus permintaan pembelian nomer : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian,
        ]);

        //kirim notif ke telegram
        $text = "<strong>PENGAJUAN PR DIHAPUS</strong>\n\n"
            . "Nomer : <strong>" . $dataPermintaanPembelian->nomer_pesanan_pembelian . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPermintaanPembelian->tanggal)) . "</strong>\n"
            . "Dihapus oleh : <strong>" . Auth::user()->name . "</strong>\n\n"
            . "Terima kasih\n\n";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        return redirect(route('pembelian.permintaan-pembelian.index'))->with('sukses', 'DATA PERMINTAAN PEMBELIAN NOMER : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian . ' BERHASIL DIHAPUS');
    }

    public function revisi(PermintaanPembelian $permintaan_pembelian, Request $request)
    {
        if ($request->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        if ($permintaan_pembelian->status_proses == 10) {
            abort(403, 'DATA SUDAH DIPROSES/DITUTUP/DITOLAK');
        }

        return view('v2.pembelian.permintaan-pembelian.revisi', [
            'permintaan' => $permintaan_pembelian,
            'countRincian' => count($permintaan_pembelian->rincianPermintaan),
            'item' => Barang::all()
        ]);
    }

    public function prosesRevisi(PermintaanPembelianRequest $request, $id)
    {
        if ($request->user()->cannot('update', Pmtpembelian::class)) abort('403', 'access denied');

        $oldDataPermintaanPembelian = PermintaanPembelian::find($id);

        // mengganti nomer permintaan lama dengan format berikut : XXXX_cancel00:00:00
        // dan merubah status menjadi ditutup
        $oldDataPermintaanPembelian->update([
            'status_proses' => 10, // 10 = status ditutup
            'nomer_permintaan_pembelian' => $oldDataPermintaanPembelian->nomer_permintaan_pembelian . '_cancel' . date('His'),
        ]);

        // Membuat ulang header permintaan pembelian
        $newDataPermintaanPembelian = PermintaanPembelian::create($request->except('rincian', 'berkas')); // insert data header permintaan
        $newDataPermintaanPembelian->rincianPermintaan()->createMany($request->input('rincian'));

        // cek apakah ada berkas baru yang diupload
        if ($request->hasFile('berkas.*.nama_berkas')) { // upload berkas jika file baru
            foreach ($request->file('berkas') as $file) {
                $fileName = str_replace(['-', '/'], '_', $newDataPermintaanPembelian->nomer_permintaan_pembelian) . '_' . uniqid() . '.' . $file['nama_berkas']->getClientOriginalExtension();
                $file['nama_berkas']->storeAs('berkas_permintaan_pembelian/', $fileName);
                $newDataPermintaanPembelian->berkasPermintaan()->create([
                    'nama_berkas' => $fileName
                ]);
            }
        } else { // copy isi berkas permintaan lama (yang sudah direvisi)
            foreach ($oldDataPermintaanPembelian->berkasPermintaan as $berkas) {
                $newDataPermintaanPembelian->berkasPermintaan()->create([
                    'nama_berkas' => $berkas->nama_berkas,
                ]);
            }
        }

        // simpan ke log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Revisi PR nomer : ' . $newDataPermintaanPembelian->nomer_permintaan_pembelian,
        ]);

        //kirim notif ke telegram
        $text = "<strong>REVISI PENGAJUAN PR</strong>\n\n"
            . "Nomer : <strong>" . $newDataPermintaanPembelian->nomer_permintaan_pembelian . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($newDataPermintaanPembelian->tanggal)) . "</strong>\n"
            . "Direvisi oleh : <strong>" . $newDataPermintaanPembelian->dibuatOleh->name . "</strong>\n\n"
            . "Mohon dapat dicek untuk ditindaklanjuti.\n"
            . "Terima kasih\n\n"
            . "Link : <strong>" . route('pembelian.permintaan-pembelian.show', $newDataPermintaanPembelian->id) . "</strong>";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        return redirect(route('pembelian.permintaan-pembelian.index'))->with('sukses', 'REVISI PR NOMER : ' . $newDataPermintaanPembelian->nomer_permintaan_pembelian . ' BERHASIL DIBUAT');
    }

    public function downloadBerkas($nama_berkas)
    {
        return Storage::download('berkas_permintaan_pembelian/' . $nama_berkas);
    }

    public function printPdf($id)
    {
        $dataPermintaanPembelian = PermintaanPembelian::where('id', $id)->first();

        if ($dataPermintaanPembelian->approve_direktur == 1) {
            $pdf = PDF::loadHTML(view('v2.pembelian.permintaan-pembelian.print-ttd', [
                'permintaan' => $dataPermintaanPembelian,
                'ttd_user' => $dataPermintaanPembelian->dibuatOleh->signature,
                'ttd_direktur' => $dataPermintaanPembelian->approveDirektur->signature,
            ]));
        } elseif ($dataPermintaanPembelian->approve_direktur == 0) {
            $pdf = PDF::loadHTML(view('v2.pembelian.permintaan-pembelian.print-pdf', [
                'permintaan' => $dataPermintaanPembelian,
            ]));
        }

        //$fileName = str_replace(['-', '/', ' '], '_', $dataPermintaanPembelian->nomer_permintaan_pembelian) . '.pdf';
        //$path = 'tmp/' . $fileName;
        //Storage::put($path, $pdf->output());
        //$file = Storage::path($path); // <-- jangan dihapus

        //return response()->download($file, $fileName); // <-- jangan dihapus
        return $pdf->stream();
    }

    public function approveDirektur(Request $request, $id) //approve direktur
    {
        if (Auth::user()->position != 'direktur') abort(403, 'akses tidak diizinkan');

        $dataPermintaanPembelian = PermintaanPembelian::find($id);
        $dataPermintaanPembelian->update([
            'approve_direktur' => 1,
            'direktur_id' => Auth::user()->id,
            'catatan_direktur' => $request->catatan_direktur 
        ]);

        // simpan ke log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menyetujui Permintaan pembelian nomer : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian
        ]);

        //kirim notif ke telegram
        $text = "<strong>PENGAJUAN PR</strong>\n\n"
            . "Nomer : <strong>" . $dataPermintaanPembelian->nomer_permintaan_pembelian . "</strong>\n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPermintaanPembelian->tanggal)) . "</strong>\n\n"
            . "Sudah DIAPPROVE.\n"
            . "Terima kasih\n\n"
            . "Link : <strong>" . route('pembelian.permintaan-pembelian.show', $dataPermintaanPembelian->id) . "</strong>";

        SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        return redirect(route('pembelian.permintaan-pembelian.index'))->with('sukses', 'PENGAJUAN BERHASIL DISETUJUI');
    }

    public function rejectDirektur(Request $request, $id) //reject direktur
    {
        if (Auth::user()->position != 'direktur') abort(403, 'akses tidak diizinkan');

        $dataPermintaanPembelian = PermintaanPembelian::find($id);
        $dataPermintaanPembelian->update([
            'approve_direktur' => 2, // 2 = ditolak
            'nama_direktur' => Auth::user()->name,
            'catatan_direktur' => $request->catatan_direktur
        ]);

        // simpan ke log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Tidak Menyetujui Permintaan pembelian nomer : ' . $dataPermintaanPembelian->nomer_permintaan_pembelian
        ]);

        // kirim notif ke telegram
        $text = "<strong>PR TIDAK DISETUJUI</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $dataPermintaanPembelian->nomer_permintaan_pembelian . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataPermintaanPembelian->tanggal)) . "</strong> \n"
            . "Tidak disetujui oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "Catatan : <strong>" . $dataPermintaanPembelian->catatan_direktur . "</strong> \n"
            . "\n"
            . "Link pengajuan : \n"
            . route('pembelian.permintaan-pembelian.show', $dataPermintaanPembelian->id) . "\n";

        SendTelegramJob::dispatchSync($text);

        return redirect(route('pembelian.permintaan-pembelian.index'))->with('sukses', 'PENGAJUAN TIDAK DISETUJUI');
    }

    public function getDetil(Request $request)
    {
        $data = PermintaanPembelian::with(['rincianPermintaan' => function ($query) {
            $query->with('item');
            $query->whereColumn('kuantitas', '!=', 'kuantitas_diproses');
        }])->findOrFail($request->input('permintaan_id'));
 
        return response()->json($data, 200);
    }
}
