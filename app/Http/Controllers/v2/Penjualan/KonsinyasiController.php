<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Exports\KonsinyasiExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendTelegramJob;
use App\Jobs\UpdateStokJob;
use App\Models\Barang;
use App\Models\Packet;
use App\Models\v2\Master\Pelanggan;
use App\Models\Province;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\Pelanggan as MasterPelanggan;
use App\Models\v2\Penjualan\Konsinyasi;
use App\Models\v2\Penjualan\KonsinyasiBerkas;
use App\Models\v2\Penjualan\KonsinyasiRinci;
use App\Models\v2\Persediaan\Barang as PersediaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class KonsinyasiController extends Controller
{
    public function index()
    {
        if (Auth::user()->cannot('viewAny', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        return view('v2.penjualan.konsinyasi.index', [
            'konsinyasi' => Konsinyasi::with('pelanggan')->latest()->get()
        ]);
    }

    public function create()
    {
        if (Auth::user()->cannot('create', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        // generate nomer konsinyasi
        $bulan = bulan_romawi(date('m'));
        $count_so = Konsinyasi::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $count = str_pad($count_so + 1, 3, "0", STR_PAD_LEFT);

        $nomer = "KONSI-" . $count . "/" . $bulan . "/" . date('Y'); //nomer konsinyasi

        return view('v2.penjualan.konsinyasi.create', [
            'pelanggan' => MasterPelanggan::all(),
            'provinsi' => Province::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::where('type', 1)->get(),
            'paket' => Packet::all(),
            'nomer' => $nomer,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->cannot('create', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        // generate nomer konsinyasi
        $bulan = bulan_romawi(date('m'));
        $count_so = Konsinyasi::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $count = str_pad($count_so + 1, 3, "0", STR_PAD_LEFT);

        $nomer = "KONSI-" . $count . "/" . $bulan . "/" . date('Y'); //nomer konsinyasi

        $grandtotal = 0;

        $validasiKonsinyasi = $request->validate([
            'pelanggan_id' => 'required',
            'nama_pelanggan' => 'nullable',
            'alamat_pelanggan' => 'nullable',
            'nomer_konsinyasi' => 'required',
            'tanggal_konsinyasi' => 'required|date',
            'gudang_asal' => 'required',
            'gudang_tujuan' => 'required',
            'keterangan' => 'nullable',
            'penerima' => 'nullable',
            'alamat_penerima' => 'nullable',
            'grandtotal' => 'nullable'
        ]);

        // insert ke tabel konsinyasi
        $konsinyasi = Konsinyasi::create($validasiKonsinyasi);

        // insert ke tabel konsinyasi rinci
        $request->validate([
            'kuantitas' => 'required',
            'gudang_asal' => 'required',
            'gudang_tujuan' => 'required',
            'harga' => 'required',
            'subtotal' => 'required',
            'catatan' => 'nullable'
        ]);

        foreach ($request->produk_id as $index => $produk) {
            $p = PersediaanBarang::find($produk);

            $konsinyasi_rinci = new KonsinyasiRinci();
            $konsinyasi_rinci->konsinyasi_id = $konsinyasi->id;
            $konsinyasi_rinci->gudang_asal = $request->gudang_asal;
            $konsinyasi_rinci->gudang_tujuan = $request->gudang_tujuan;
            $konsinyasi_rinci->produk_id = $produk;
            $konsinyasi_rinci->kode_produk = $p->kode_barang;
            $konsinyasi_rinci->nama_produk = $p->nama_barang;
            $konsinyasi_rinci->kuantitas = $request->kuantitas[$index];
            $konsinyasi_rinci->harga = $request->harga[$index];
            $konsinyasi_rinci->subtotal = $request->subtotal[$index];
            $grandtotal += $request->subtotal[$index];
            $konsinyasi_rinci->catatan = $request->catatan[$index];
            $konsinyasi_rinci->save();

            UpdateStokJob::dispatchSync('kurang', $request->gudang_asal, $produk, $request->kuantitas[$index]);
            UpdateStokJob::dispatchSync('tambah', $request->gudang_tujuan, $produk, $request->kuantitas[$index]);
        }

        // insert data berkas dan upload data berkas
        if ($request->has('berkas1')) {
            $getFileName = $request->file('berkas1')->getClientOriginalName();
            $berkas1 = str_replace(["-", "/"], "_", $request->nomer_konsinyasi) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas1')->storeAs('berkas_konsinyasi', $berkas1);

            KonsinyasiBerkas::create([
                'konsinyasi_id' => $konsinyasi->id,
                'berkas1' => $berkas1,
            ]);
        }

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat Konsinyasi Baru dengan nomer : ' . $request->nomer_konsinyasi
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>KONSINYASI BARU</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $konsinyasi->nomer_konsinyasi . "</strong> \n"
            . "Gudang Asal : <strong>" . $konsinyasi->gudang_asal . "</strong> \n"
            . "Gudang Tujuan : <strong>" . $konsinyasi->gudang_tujuan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $konsinyasi->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($konsinyasi->tanggal_konsinyasi)) . "</strong> \n"
            . "Dibuat oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"

            . "Silahkan dicek terlebih dulu untuk selanjutnya dibuatkan DO. \n"
            . "\n";
        SendTelegramJob::dispatchSync($text);

        return redirect(route('konsinyasi.index'))->with('sukses', 'DATA KONSINYASI NOMER : ' . $request->nomer_konsinyasi . ' BERHASIL DITAMBAHKAN');
    }

    public function show(Konsinyasi $konsinyasi)
    {
        if (Auth::user()->cannot('view', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        return view('v2.penjualan.konsinyasi.show', [
            'konsinyasi' => $konsinyasi
        ]);
    }

    public function edit(Konsinyasi $konsinyasi)
    {
        if (Auth::user()->cannot('update', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        //return $konsinyasi->with('rinci');
        return view('v2.penjualan.konsinyasi.edit', [
            'konsinyasi' => $konsinyasi,
            'pelanggan' => MasterPelanggan::all(),
            'gudang' => Gudang::all(),
            'produk' => Barang::where('type', 1)->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all(),
        ]);
    }

    public function update(Request $request, Konsinyasi $konsinyasi)
    {
        if (Auth::user()->cannot('update', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        $grandtotal = 0;

        $request->validate([
            'pelanggan_id' => 'required',
            'nama_pelanggan' => 'nullable',
            'alamat_pelanggan' => 'nullable',
            'tanggal_konsinyasi' => 'required|date',
            'gudang_asal' => 'required',
            'gudang_tujuan' => 'required',
            'keterangan' => 'nullable',
            'penerima' => 'nullable',
            'alamat_penerima' => 'nullable',
            'grandtotal' => 'nullable'
        ]);

        Konsinyasi::find($konsinyasi->id)->update([
            'pelanggan_id' => $request->pelanggan_id,
            'gudang_asal' => $request->gudang_asal,
            'gudang_tujuan' => $request->gudang_tujuan,
            'nomer_konsinyasi' => $request->nomer_konsinyasi,
            'tanggal_konsinyasi' => $request->tanggal_konsinyasi,
            'keterangan' => $request->keterangan,
            'penerima' => $request->penerima,
            'alamat_penerima' => $request->alamat_penerima
        ]);

        if ($request->hasFile('berkas1')) {
            // Delete berkas lama
            Storage::delete('berkas_konsinyasi/' . $konsinyasi->berkas->berkas1);

            // Simpan dan upload file baru
            $getFileName = $request->file('berkas1')->getClientOriginalName();
            $berkas1 = str_replace(["-", "/"], "_", $request->nomer_konsinyasi) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas1')->storeAs('berkas_konsinyasi', $berkas1);

            KonsinyasiBerkas::where('konsinyasi_id', $konsinyasi->id)
                ->update([
                    'berkas1' => $berkas1
                ]);
        }

        // validasi rincian konsinyasi
        $request->validate([
            'kuantitas' => 'required',
            'gudang_asal' => 'required',
            'gudang_tujuan' => 'required',
            'harga' => 'required',
            'subtotal' => 'required',
            'catatan' => 'nullable'
        ]);

        foreach ($konsinyasi->rinci as $rinci) { // melakukan proses update stok dulu dengan menambah gudang asal & mengurangi gudang tujuan
            // proses kurangi gudang tujuan dulu
            UpdateStokJob::dispatchSync('kurang', $rinci->gudang_tujuan, $rinci->produk_id, $rinci->kuantitas);
            // proses tambah gudang asal
            UpdateStokJob::dispatchSync('tambah', $rinci->gudang_asal, $rinci->produk_id, $rinci->kuantitas);
        }

        KonsinyasiRinci::where('konsinyasi_id', $konsinyasi->id)->delete(); // hapus rincian konsinyasi
        foreach ($request->produk_id as $index => $produk) {
            $p = PersediaanBarang::find($produk);

            KonsinyasiRinci::create([
                'konsinyasi_id' => $konsinyasi->id,
                'gudang_asal' => $request->gudang_asal,
                'gudang_tujuan' => $request->gudang_tujuan,
                'produk_id' => $produk,
                'kode_produk' => $p->kode_barang,
                'nama_produk' => $p->nama_barang,
                'kuantitas' => $request->kuantitas[$index],
                'harga' => $request->harga[$index],
                'subtotal' => $request->subtotal[$index],
                'catatan' => $request->catatan[$index],
            ]);

            // Sum subtotal
            $grandtotal += $request->subtotal[$index];
            // proses menambah gudang tujuan
            UpdateStokJob::dispatchSync('tambah', $request->gudang_tujuan, $produk, $request->kuantitas[$index]);
            // proses mengurangi gudang asal
            UpdateStokJob::dispatchSync('kurang', $request->gudang_asal, $produk, $request->kuantitas[$index]);
        }

        // update grandtotal
        Konsinyasi::find($konsinyasi->id)->update([
            'grandtotal' => $grandtotal
        ]);

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Merubah Konsinyasi dengan nomer : ' . $konsinyasi->nomer_konsinyasi
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>KONSINYASI UPDATE</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $konsinyasi->nomer_konsinyasi . "</strong> \n"
            . "Gudang Asal : <strong>" . $konsinyasi->gudang_asal . "</strong> \n"
            . "Gudang Tujuan : <strong>" . $konsinyasi->gudang_tujuan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $konsinyasi->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($konsinyasi->tanggal_konsinyasi)) . "</strong> \n"
            . "Diubah oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"

            . "<strong>Silahkan dicek ulang data KONSINYASI di atas.</strong> \n"
            . "\n";

        SendTelegramJob::dispatchSync($text);

        return redirect(route('konsinyasi.show', $konsinyasi->id))->with('sukses', 'DATA KONSINYASI BERHASIL DIPERBARUI');
    }

    public function destroy(Konsinyasi $konsinyasi)
    {
        if (Auth::user()->cannot('delete', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        if ($konsinyasi->status_proses == 1) {
            if (in_array(Auth::user()->id, [16, 13, 9])) {
                // Proses hapus transaksi konsinyasi & pengurangan stok gudang
                $this->prosesDestroy($konsinyasi);
            } else {
                return redirect()->back()->with('sukses', 'DATA KONSINYASI SUDAH DIPROSES TIDAK DAPAT DIHAPUS, ANDA BUKAN SPV!');
            }
        } else {
            $this->prosesDestroy($konsinyasi);
        }

        return redirect(route('konsinyasi.index'))->with('sukses', 'DATA KONSINYASI NOMER : ' . $konsinyasi->nomer_konsinyasi . ' BERHASIL DIHAPUS');
    }

    public function prosesDestroy($dataKonsinyasi)
    {
        if (Auth::user()->cannot('delete', Konsinyasi::class)) abort(403, 'akses tidak diizinkan');

        // proses update stok persediaan
        foreach ($dataKonsinyasi->rinci as $rinci) { // melakukan proses update stok dengan menambah gudang asal & mengurangi gudang tujuan
            // proses kurangi gudang tujuan
            UpdateStokJob::dispatchSync('kurang', $rinci->gudang_tujuan, $rinci->produk_id, $rinci->kuantitas);
            // proses tambah gudang asal
            UpdateStokJob::dispatchSync('tambah', $rinci->gudang_asal, $rinci->produk_id, $rinci->kuantitas);
        }

        if (!empty($dataKonsinyasi->berkas->berkas1)) {
            // Hapus berkas
            Storage::delete('berkas_konsinyasi/', $dataKonsinyasi->berkas->berkas1);
            KonsinyasiBerkas::where('konsinyasi_id', $dataKonsinyasi->id)->delete();
        }

        // Hapus rincian
        KonsinyasiRinci::where('konsinyasi_id', $dataKonsinyasi->id)->delete();

        // Hapus header konsinyasi
        Konsinyasi::destroy($dataKonsinyasi->id);

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menghapus Konsinyasi dengan nomer : ' . $dataKonsinyasi->nomer_konsinyasi
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>DATA KONSINYASI DIHAPUS</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $dataKonsinyasi->nomer_konsinyasi . "</strong> \n"
            . "Gudang Asal : <strong>" . $dataKonsinyasi->gudang_asal . "</strong> \n"
            . "Gudang Tujuan : <strong>" . $dataKonsinyasi->gudang_tujuan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $dataKonsinyasi->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($dataKonsinyasi->tanggal_konsinyasi)) . "</strong> \n"
            . "Dihapus oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n";

        SendTelegramJob::dispatchSync($text);
    }

    // Download berkas
    public function downloadBerkas($berkas)
    {
        return Storage::download('berkas_konsinyasi/' . $berkas);
    }

    // Print
    public function print(Konsinyasi $konsinyasi)
    {
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.penjualan.konsinyasi.print', [
                'konsinyasi' => $konsinyasi,
                'konsinyasi_rinci' => KonsinyasiRinci::with('produk')->where('konsinyasi_id', $konsinyasi->id)->get()
            ]);

        return $pdf->stream();
    }

    // Print SJ
    public function printSj(Konsinyasi $konsinyasi)
    {
        $nomer_sj = str_replace('KONSI', 'SJ', $konsinyasi->nomer_konsinyasi);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.penjualan.konsinyasi.print-sj', [
                'konsinyasi' => $konsinyasi,
                'konsinyasi_rinci' => KonsinyasiRinci::with('produk')->where('konsinyasi_id', $konsinyasi->id)->get(),
                'nomer_sj' => $nomer_sj
            ]);

        return $pdf->stream();
    }

    // Proses Kirim
    public function prosesKirim(Konsinyasi $konsinyasi)
    {
        if (Auth::user()->position != 'gudang') abort(403);

        Konsinyasi::find($konsinyasi->id)->update([
            'status_proses' => 1,
            'tanggal_kirim' => date('Y-m-d')
        ]);

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Memproses Pengiriman Konsinyasi dengan nomer : ' . $konsinyasi->nomer_konsinyasi
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>KONSINYASI SUDAH DIPROSES</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $konsinyasi->nomer_konsinyasi . "</strong> \n"
            . "Gudang Asal : <strong>" . $konsinyasi->gudang_asal . "</strong> \n"
            . "Gudang Tujuan : <strong>" . $konsinyasi->gudang_tujuan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $konsinyasi->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($konsinyasi->tanggal_konsinyasi)) . "</strong> \n"
            . "Diproses oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n";
        SendTelegramJob::dispatchSync($text);

        return redirect(route('konsinyasi.show', $konsinyasi->id))->with('sukses', 'Konsinyasi berhasil diproses');
    }

    // Direct method
    public function storeGudangBaru(Request $request)
    {
        $dataValidated = $request->validate([
            'nama_gudang' => 'required|max:100',
            'pic_gudang' => 'nullable|max:100',
            'alamat_gudang' => 'nullable|max:255',
            'keterangan' => 'nullable|max:255',
        ]);

        Gudang::create($dataValidated);

        return redirect()->back()->with('sukses', 'Gudang : ' . $request->nama_gudang . ' berhasil ditambahkan.');
    }

    public function storePelangganBaru(Request $request)
    {
        // insert ke data tabel lama
        Pelanggan::create($request->all());

        // insert ke data tabel baru
        MasterPelanggan::create([
            'kode_pelanggan' => $request->kode_pelanggan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_handphone' => $request->handphone_pelanggan,
            'provinsi' => Province::find($request->provinsi)->name,
            'kota' => $request->kota,
            'detil_alamat' => $request->detail_alamat,
        ]);

        return redirect(route('konsinyasi.create'))->with('sukses', 'Pelanggan ' . $request->nama_pelanggan . ' berhasil ditambahkan');
    }

    public function downloadExcel(Request $request)
    {
        $konsinyasi = Konsinyasi::with('rinci', 'pelanggan')
            ->whereBetween('tanggal_konsinyasi', [$request->dari_tanggal, $request->sampai_tanggal])
            ->get();

        return Excel::download(new KonsinyasiExport($konsinyasi), 'konsinyasi.xlsx');
    }
}
