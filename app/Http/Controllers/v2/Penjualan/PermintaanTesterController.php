<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramJob;
use App\Jobs\TransaksiStokJob;
use App\Jobs\UpdateStokJob;
use App\Models\EkspedisiLogistik;
use App\Models\Packet;
use App\Models\Province;
use App\Models\Sales;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan;
use App\Models\v2\Penjualan\PermintaanTester;
use App\Models\v2\Penjualan\PermintaanTesterBerkas;
use App\Models\v2\Penjualan\PermintaanTesterRinci;
use App\Models\v2\Persediaan\Barang;
use App\Services\CekNomerDihapusService;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class PermintaanTesterController extends Controller
{
    protected $generateNomer;

    public function __construct()
    {
        $cekNomer = CekNomerDihapusService::cekNomer('permintaan_tester');

        if ($cekNomer) {
            $this->generateNomer = $cekNomer->nomer;
        } else {
            $count = PermintaanTester::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
            $this->generateNomer = 'TESTER-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT) . '/' . bulan_romawi(date('m')) . '/' . date('Y');
        }
    }
    public function index()
    {
        return view('v2.penjualan.tester.index', [
            'tester' => PermintaanTester::getData()->get(),
        ]);
    }

    public function create()
    {
        return view('v2.penjualan.tester.create', [
            'generateNomer' => $this->generateNomer,
            'pelanggan' => Pelanggan::active()->get(),
            'sales' => Sales::all(),
            'ekspedisi' => EkspedisiLogistik::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::produk()->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all()
        ]);
    }

    public function store(Request $request)
    {
        $rincian = [];

        $request->validate([
            'pelanggan_id' => 'required',
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'nomer_permintaan_tester' => 'required',
            'tanggal' => 'required',
            'gudang_id' => 'required',
            'produk_id' => 'required',
            'kuantitas' => 'required',
        ]);

        // Insert data header permintaan tester
        $tester = PermintaanTester::create([
            'pelanggan_id' => $request->pelanggan_id,
            'sales_id' => $request->sales_id,
            'gudang_id' => $request->gudang_id,
            'created_by' => Auth::user()->id,
            'nomer_permintaan_tester' => $this->generateNomer,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'nomer_pesanan' => $request->nomer_pesanan,
            'ekspedisi' => $request->ekspedisi,
            'resi' => $request->resi,
            'penerima' => $request->penerima,
            'alamat_penerima' => $request->alamat_penerima,
        ]);

        // insert data rincian
        foreach ($request->produk_id as $index => $produk) {
            $rincian[] = [
                'penjualan_tester_id' => $tester->id,
                'gudang_id' => $request->gudang_id,
                'created_by' => Auth::user()->id,
                'produk_id' => $produk,
                'kuantitas' => $request->kuantitas[$index],
                'catatan' => $request->catatan[$index]
            ];
            // Update stok gudang
            UpdateStokJob::dispatchSync('kurang', $request->gudang_id, $produk, $request->kuantitas[$index]);

            // Catat transaksi tabel stok
            TransaksiStokJob::dispatchSync('out', [
                'nomer_ref' => $this->generateNomer,
                'gudang_id' => $request->gudang_id,
                'produk_id' => $produk,
                'keterangan' => 'Tester ke ' . $tester->pelanggan->nama_pelanggan,
                'kuantitas' => $request->kuantitas[$index]
            ]);
        }
        PermintaanTesterRinci::insert($rincian);

        // insert data berkas & upload berkas jika ada
        if ($request->has('berkas')) {
            foreach ($request->berkas as $file) {
                $fileName = str_replace(['-', '/'], '_', $tester->nomer_permintaan_tester) . '_' . str_replace(['-', '/', ' '], '_', $file->getClientOriginalName());
                $file->storeAs('berkas_permintaan_tester', $fileName);

                PermintaanTesterBerkas::create([
                    'penjualan_tester_id' => $tester->id,
                    'berkas' => $fileName,
                ]);
            }
        }

        // Insert ke log aktifitas dan kirim notif ke telegram
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat Permintaan Tester Baru nomer : ' . $tester->nomer_permintaan_tester,
        ]);

        $text = "<strong>PERMINTAAN TESTER BARU</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $tester->nomer_permintaan_tester . "</strong> \n"
            . "Nama pelanggan : <strong>" . $tester->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($tester->tanggal)) . "</strong> \n"
            . "Dibuat oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"

            . "Silahkan dicek terlebih dulu untuk selanjutnya dibuatkan DO. \n"
            . "\n"
            . 'v2';

        SendTelegramJob::dispatchSync($text);

        return redirect(route('permintaan-tester.index'))->with('sukses', 'DATA PERMINTAAN TESTER NO : ' . $tester->nomer_permintaan_tester . ' BERHASIL DIBUAT');
    }

    public function show(PermintaanTester $tester)
    {
        //return $tester;
        return view('v2.penjualan.tester.show', [
            'tester' => $tester,
            'rincian' => PermintaanTesterRinci::with('produk')->where('penjualan_tester_id', $tester->id)->get()
        ]);
    }

    public function edit(PermintaanTester $tester)
    {
        return view('v2.penjualan.tester.edit', [
            'tester' => $tester,
            'pelanggan' => Pelanggan::active()->get(),
            'sales' => Sales::all(),
            'ekspedisi' => EkspedisiLogistik::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::produk()->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all()
        ]);
    }

    public function update(Request $request, PermintaanTester $tester)
    {
        $request->validate([
            'gudang_id' => 'required',
        ]);

        // update header tester dulu
        PermintaanTester::find($tester->id)->update([
            'pelanggan_id' => $request->pelanggan_id,
            'penerima' => $request->penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'tanggal' => $request->tanggal,
            'nomer_pesanan' => $request->nomer_pesanan,
            'sales_id' => $request->sales_id,
            'ekspedisi' => $request->ekspedisi,
            'resi' => $request->resi,
            'keterangan' => $request->keterangan,
            'gudang_id' => $request->gudang_id
        ]);

        // Update stok gudang menggunakan metode ditambah dulu
        // baru nanti dikurangkan diproses selanjutnya
        foreach ($tester->rincian as $rincian) {
            UpdateStokJob::dispatchSync('tambah', $rincian->gudang_id, $rincian->produk_id, $rincian->kuantitas);
        }

        // Hapus riwayat tabel transaksi stok
        TransaksiStokJob::dispatchSync('hapus', [
            'nomer_ref' => $tester->nomer_permintaan_tester
        ]);

        // hapus rincian transaksi tester
        PermintaanTesterRinci::where('penjualan_tester_id', $tester->id)->delete();

        // jika ada berkas, hapus berkas terlebih dulu
        // masih bingung

        // input ulang rincian tester
        $dataRinci = [];
        foreach ($request->produk_id as $index => $produk) {
            $dataRinci[] = [
                'penjualan_tester_id' => $tester->id,
                'gudang_id' => $request->gudang_id,
                'created_by' => Auth::user()->id,
                'produk_id' => $produk,
                'kuantitas' => $request->kuantitas[$index],
                'catatan' => $request->catatan[$index]
            ];
            // Update stok gudang
            UpdateStokJob::dispatchSync('kurang', $request->gudang_id, $produk, $request->kuantitas[$index]);

            // Catat transaksi tabel stok
            TransaksiStokJob::dispatchSync('out', [
                'nomer_ref' => $tester->nomer_permintaan_tester,
                'gudang_id' => $request->gudang_id,
                'produk_id' => $produk,
                'keterangan' => 'Tester ke ' . $tester->pelanggan->nama_pelanggan,
                'kuantitas' => $request->kuantitas[$index]
            ]);
        }
        PermintaanTesterRinci::insert($dataRinci);

        // Catat ke log & kirim ke telegram
        LogAktifitas::created([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Mengubah Permintaan Tester nomer : ' . $tester->nomer_permintaan_tester,
        ]);

        $text = "<strong>DATA PERMINTAAN TESTER DIUBAH</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $tester->nomer_permintaan_tester . "</strong> \n"
            . "Nama pelanggan : <strong>" . $tester->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($tester->tanggal)) . "</strong> \n"
            . "Diubah oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"
            . 'v2';

        SendTelegramJob::dispatchSync($text);

        return redirect(route('permintaan-tester.show', $tester->id))->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    public function destroy(PermintaanTester $tester)
    {
        // Hapus berkas bila ada
        if ($tester->berkas) {
            foreach ($tester->berkas as $berkas) {
                Storage::delete('berkas_permintaan_tester/' . $berkas->berkas);
            }
        }

        // Update stok dengan metode pengurangan
        foreach ($tester->rincian as $rincian) {
            UpdateStokJob::dispatchSync('kurang', $tester->gudang_id, $rincian->produk_id, $rincian->kuantitas);
        }

        // Hapus tabel transaksi stok
        TransaksiStokJob::dispatchSync('hapus', [
            'nomer_ref' => $tester->nomer_permintaan_tester
        ]);

        // Hapus data permintaan tester
        $tester->delete();

        // Catat ke log & kirim ke telegram
        LogAktifitas::created([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menghapus Permintaan Tester nomer : ' . $tester->nomer_permintaan_tester,
        ]);

        $text = "<strong>DATA PERMINTAAN TESTER DIHAPUS</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $tester->nomer_permintaan_tester . "</strong> \n"
            . "Nama pelanggan : <strong>" . $tester->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($tester->tanggal)) . "</strong> \n"
            . "Dihapus oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"
            . 'v2';

        SendTelegramJob::dispatchSync($text);

        return redirect(route('permintaan-tester.index'))->with('sukses', 'DATA PERMINTAAN TESTER NOMER : ' . $tester->nomer_permintaan_tester . ' BERHASIL DIHAPUS');
    }

    public function print(PermintaanTester $tester)
    {
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.penjualan.tester.print', [
                'tester' => $tester,
                'tester_rinci' => PermintaanTesterRinci::with('produk')->where('penjualan_tester_id', $tester->id)->get()
            ]);

        return $pdf->stream();
    }

    public function prosesKirim(PermintaanTester $tester)
    {
        PermintaanTester::find($tester->id)->update([
            'status_proses' => 1
        ]);

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Memproses Permintaan Tester dengan nomer : ' . $tester->nomer_permintaan_tester
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>DATA PERMINTAAN TESTER DIPROSES</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $tester->nomer_permintaan_tester . "</strong> \n"
            . "Nama pelanggan : <strong>" . $tester->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($tester->tanggal)) . "</strong> \n"
            . "Diproses oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"
            . 'v2';

        SendTelegramJob::dispatchSync($text);

        return redirect(route('permintaan-tester.show', $tester->id))->with('sukses', 'PERMINTAAN TESTER BERHASIL DIPROSES');
    }

    public function printSj(PermintaanTester $tester)
    {
        $nomer_sj = str_replace('TESTER-', 'SJ-Tester-', $tester->nomer_permintaan_tester);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.penjualan.tester.print-sj', [
                'tester' => $tester,
                'tester_rinci' => PermintaanTesterRinci::with('produk')->where('penjualan_tester_id', $tester->id)->get(),
                'nomer_sj' => $nomer_sj
            ]);

        return $pdf->stream();
    }
}
