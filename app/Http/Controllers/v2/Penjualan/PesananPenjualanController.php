<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Exports\PesananPenjualanExport;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateInvoicePenjualanJob;
use App\Jobs\SendTelegramJob;
use App\Jobs\TransaksiStokJob;
use App\Jobs\UpdateStokJob;
use App\Models\EkspedisiLogistik;
use App\Models\Packet;
use App\Models\Province;
use App\Models\Sales;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Coa;
use App\Models\v2\Master\Gudang;
use App\Models\v2\Master\JenisPenjualan;
use App\Models\v2\Master\Pelanggan as MasterPelanggan;
use App\Models\v2\NomerDihapus;
use App\Models\v2\Penjualan\PengirimanPenjualan;
use App\Models\v2\Penjualan\Pesanan;
use App\Models\v2\Penjualan\PesananBerkas;
use App\Models\v2\Penjualan\PesananRinci;
use App\Models\v2\Persediaan\Barang;
use App\Services\CekNomerDihapusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PesananPenjualanController extends Controller
{
    protected $nomerPesananPenjualan;
    protected $rincianPesanan = [];

    public function __construct()
    {
        $cekNomer = CekNomerDihapusService::cekNomer('pesanan_penjualan');

        if ($cekNomer) {
            $this->nomerPesananPenjualan = $cekNomer->nomer;
        } else {
            $bulan = bulan_romawi(date('m'));
            $count_so = Pesanan::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
            $count = str_pad($count_so + 1, 3, "0", STR_PAD_LEFT);
            $this->nomerPesananPenjualan = "SO-" . $count . "/" . $bulan . "/" . date('Y'); //nomer pesanan penjualan
        }
    }

    public function index()
    {
        $pesanan = Pesanan::with(
            'pelanggan',
            'pengiriman'
        )->orderBy('created_at', 'desc')->get();

        return view('v2.penjualan.pesanan.index', compact(
            'pesanan'
        ));
    }

    public function create()
    {
        if (Auth::user()->cannot('create', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        return view('v2.penjualan.pesanan.create2', [
            'nomer' => $this->nomerPesananPenjualan,
            'pelanggan' => MasterPelanggan::all(),
            'produk' => Barang::produk()->get(),
            'provinsi' => Province::all(),
            'sales' => Sales::all(),
            'jenis_penjualan' => JenisPenjualan::all(),
            'ekspedisi' => EkspedisiLogistik::all(),
            'paket' => Packet::all(),
            'gudang' => Gudang::all(),
            'akun_bank' => Coa::bank()->get(),
            'akun_biayakirim' => Coa::where('status_aktif', 1)->whereIn('coa_tipe_id', [13, 14])->get(),
            'akun_diskon' => Coa::pendapatan()->get(),
            'akun_ppn' => Coa::where('status_aktif', 1)->where('nama_coa', 'like', '%ppn%')->get(),
        ]);
    }

    public function store(Request $request)
    {
        //return $request->all();
        if (Auth::user()->cannot('create', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        $request->validate([
            'pelanggan_id' => 'required',
            'gudang_id' => 'required',
            'ppn' => 'required',
            'penerima' => 'required',
            'alamat_penerima' => 'required',
            'tanggal' => 'required|date',
            'jenis_penjualan' => 'required',
            'pelanggan_id' => 'required',
            'produk_id' => 'required',
            'harga_produk' => 'required'
        ]);

        // input header penjualan_pesanan
        $pesanan_penjualan = Pesanan::create([
            'akun_ppn_id' => $request->akun_ppn,
            'akun_biayakirim_id' => $request->akun_biayakirim,
            'akun_diskon_id' => $request->akun_diskon,
            'pelanggan_id' => $request->pelanggan_id,
            'sales_id' => $request->sales_id,
            'created_by' => Auth::user()->id,
            'gudang_id' => $request->gudang_id,
            'nomer_pesanan_penjualan' => $this->nomerPesananPenjualan,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jenis_penjualan' => $request->jenis_penjualan,
            'ppn' => $request->ppn,
            'nilai_ppn' => convertToDouble($request->nilai_ppn),
            'nomer_pesanan' => $request->nomer_pesanan,
            'resi' => $request->resi,
            'ekspedisi' => $request->ekspedisi,
            'penerima' => $request->penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'diskon_persen' => $request->diskon_persen_global,
            'diskon_global' => convertToDouble($request->diskon_nominal_global),
            'biaya_kirim' => convertToDouble($request->biaya_kirim),
            'grandtotal' => convertToDouble($request->grandtotal),
            'grandtotal_setelah_diskon' => convertToDouble($request->grandtotal_setelah_diskon),
        ]);

        // Ubah status pada tabel nomer_dihapus
        NomerDihapus::where('nomer', $this->nomerPesananPenjualan)->update([
            'sudah_dipakai' => 1
        ]);

        // input rincian pesanan
        foreach ($request->produk_id as $index => $produk) {
            $this->rincianPesanan[] = [
                'produk_id' => $produk,
                'penjualan_pesanan_id' => $pesanan_penjualan->id,
                'gudang_id' => $request->gudang_id,
                'kuantitas' => $request->kuantitas[$index],
                'harga_produk' => $request->harga_produk[$index],
                'diskon_persen' => $request->diskon_persen[$index],
                'diskon_nominal' => convertToDouble($request->diskon_nominal[$index]),
                'potongan_admin' => convertToDouble($request->potongan[$index]),
                'cashback' => convertToDouble($request->cashback[$index]),
                'subtotal' => convertToDouble($request->subtotal[$index]),
                'catatan' => $request->catatan[$index],
            ];

            // Proses update stok gudang
            UpdateStokJob::dispatchSync('kurang', $request->gudang_id, $produk, $request->kuantitas[$index]);

            // Catat transaksi stok produk
            TransaksiStokJob::dispatchSync('out', [
                'nomer_ref' => $this->nomerPesananPenjualan,
                'gudang_id' => $request->gudang_id,
                'produk_id' => $produk,
                'keterangan' => 'Terjual ke ' . $pesanan_penjualan->pelanggan->nama_pelanggan,
                'kuantitas' => $request->kuantitas[$index]
            ]);
        }

        PesananRinci::insert($this->rincianPesanan); // insert ke tabel pesanan rinci

        // Generate invoice otomatis ke tabel penjualan invoice
        GenerateInvoicePenjualanJob::dispatchSync('baru', $pesanan_penjualan->id);

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat Pesanan Penjualan Baru dengan nomer : ' . $this->nomerPesananPenjualan
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>SALES ORDER BARU</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $this->nomerPesananPenjualan . "</strong> \n"
            . "Jenis penjualan : <strong>" . $pesanan_penjualan->jenis_penjualan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $pesanan_penjualan->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($pesanan_penjualan->tanggal)) . "</strong> \n"
            . "Dibuat oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"

            . "Silahkan dicek terlebih dulu untuk selanjutnya dibuatkan DO. \n"
            . "\n"

            . "MARKET Selain Konsinyasi & Event\n"
            . "<strong>SALES INVOICE sudah digenerate otomatis oleh sistem.</strong> v2\n";

        SendTelegramJob::dispatchSync($text);

        return redirect(route('pesanan-penjualan.index'))->with('sukses', 'PESANAN BARU NOMER : ' . $this->nomerPesananPenjualan . ' BERHASIL DITAMBAHKAN');
    }

    public function show(Pesanan $pesanan_penjualan)
    {
        return view('v2/penjualan.pesanan.show', [
            'pesanan_penjualan' => $pesanan_penjualan,
            'pesanan_penjualan_rinci' => PesananRinci::with('produk')->where('penjualan_pesanan_id', $pesanan_penjualan->id)->get()
        ]);
    }

    public function edit(Pesanan $pesanan_penjualan)
    {
        if (Auth::user()->cannot('update', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        return view('v2.penjualan.pesanan.edit2', [
            'pesanan_penjualan' => $pesanan_penjualan,
            'pesanan_penjualan_rinci' => PesananRinci::with('produk')->where('penjualan_pesanan_id', $pesanan_penjualan->id)->get(),
            'jenis_penjualan' => JenisPenjualan::all(),
            'sales' => Sales::all(),
            'ekspedisi' => EkspedisiLogistik::all(),
            'produk' => Barang::produk()->get(),
            'provinsi' => Province::all(),
            'paket' => Packet::all(),
            'pelanggan' => MasterPelanggan::all(),
            'gudang' => Gudang::all(),
            'akun_bank' => Coa::bank()->get(),
            'akun_biayakirim' => Coa::where('status_aktif', 1)->whereIn('coa_tipe_id', [13, 14])->get(),
            'akun_diskon' => Coa::pendapatan()->get(),
            'akun_ppn' => Coa::where('status_aktif', 1)->where('nama_coa', 'like', '%ppn%')->get(),
        ]);
    }

    public function update(Request $request, Pesanan $pesanan_penjualan)
    {
        //return $request->all();

        if (Auth::user()->cannot('update', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        $request->validate([
            'gudang_id' => 'required',
        ]);

        // Simpan update header pesanan penjualan
        Pesanan::find($pesanan_penjualan->id)->update([
            'akun_ppn_id' => $request->akun_ppn,
            'akun_biayakirim_id' => $request->akun_biayakirim,
            'akun_diskon_id' => $request->akun_diskon,
            'pelanggan_id' => $request->pelanggan_id,
            'sales_id' => $request->sales_id,
            'created_by' => Auth::user()->id,
            'gudang_id' => $request->gudang_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jenis_penjualan' => $request->jenis_penjualan,
            'ppn' => $request->ppn,
            'nilai_ppn' => convertToDouble($request->nilai_ppn),
            'nomer_pesanan' => $request->nomer_pesanan,
            'resi' => $request->resi,
            'ekspedisi' => $request->ekspedisi,
            'penerima' => $request->penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'diskon_persen' => $request->diskon_persen_global,
            'diskon_global' => convertToDouble($request->diskon_nominal_global),
            'biaya_kirim' => convertToDouble($request->biaya_kirim),
            'grandtotal' => convertToDouble($request->grandtotal),
            'grandtotal_setelah_diskon' => convertToDouble($request->grandtotal_setelah_diskon),
        ]);

        // Update stok gudang
        // dengan metode menambah stok persediaan
        // terlebih dulu
        foreach ($pesanan_penjualan->rincian as $rincian) {
            UpdateStokJob::dispatchSync('tambah', $pesanan_penjualan->gudang_id, $rincian->produk_id, $rincian->kuantitas);
        }

        // Hapus transaksi tabel stok
        TransaksiStokJob::dispatchSync('hapus', [
            'nomer_ref' => $pesanan_penjualan->nomer_pesanan_penjualan,
        ]);

        // Delete rincian pesanan penjualan
        PesananRinci::where('penjualan_pesanan_id', $pesanan_penjualan->id)->delete();

        // Simpan ulang rincian pesanan penjualan
        foreach ($request->produk_id as $index => $produk) {
            PesananRinci::create([
                'produk_id' => $produk,
                'penjualan_pesanan_id' => $pesanan_penjualan->id,
                'gudang_id' => $request->gudang_id,
                'kuantitas' => $request->kuantitas[$index],
                'harga_produk' => $request->harga_produk[$index],
                'diskon_persen' => $request->diskon_persen[$index],
                'diskon_nominal' => convertToDouble($request->diskon_nominal[$index]),
                'potongan_admin' => convertToDouble($request->potongan[$index]),
                'cashback' => convertToDouble($request->cashback[$index]),
                'subtotal' => convertToDouble($request->subtotal[$index]),
                'catatan' => $request->catatan[$index],
            ]);

            // Update stok gudang
            // dengan metode pengurangan 
            // setelah proses penambahan tadi selesai
            UpdateStokJob::dispatchSync('kurang', $pesanan_penjualan->gudang_id, $produk, $request->kuantitas[$index]);

            // Catat ulang riwayat transaksi stok
            TransaksiStokJob::dispatchSync('out', [
                'nomer_ref' => $pesanan_penjualan->nomer_pesanan_penjualan,
                'gudang_id' => $request->gudang_id,
                'produk_id' => $produk,
                'keterangan' => 'Terjual ke ' . $pesanan_penjualan->pelanggan->nama_pelanggan,
                'kuantitas' => $request->kuantitas[$index]
            ]);
        }

        // Jika pesanan sudah diproses == 1
        // maka update juga transaksi pengiriman 
        if ($pesanan_penjualan->status_proses == 1) {
            $dataUpdatePesananPenjualan = Pesanan::find($pesanan_penjualan->id);

            // Hapus data pengiriman penjualan
            PengirimanPenjualan::where('penjualan_pesanan_id', $pesanan_penjualan->id)->delete();

            // Duplikat ulang isi pesanan penjualan ke tabel pengiriman pesanan
            $pengiriman_pesanan = app(PengirimanPenjualanController::class);
            $pengiriman_pesanan->store($dataUpdatePesananPenjualan);
        }

        // Generate ulang invoice penjualan otomatis
        GenerateInvoicePenjualanJob::dispatchSync('update', $pesanan_penjualan->id);

        // Insert Log Aktifitas
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Merubah Pesanan Penjualan dengan nomer : ' . $pesanan_penjualan->nomer_pesanan_penjualan
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>UPDATE SALES ORDER</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $pesanan_penjualan->nomer_pesanan_penjualan . "</strong> \n"
            . "Jenis penjualan : <strong>" . $pesanan_penjualan->jenis_penjualan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $pesanan_penjualan->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($pesanan_penjualan->tanggal)) . "</strong> \n"
            . "Diubah oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n"

            . "Silahkan <strong>DICEK</strong> ulang data Pesanan di atas. \n"
            . "\n"

            . "Khusus Marketplace Online \n"
            . "<strong>SALES INVOICE sudah digenerate otomatis oleh sistem.</strong> v2\n";

        SendTelegramJob::dispatchSync($text);

        // redirect ke halaman pesanan yang diedit
        return redirect(route('pesanan-penjualan.show', $pesanan_penjualan->id))->with('sukses', 'DATA PESANAN PENJUALAN BERHASIL DIPERBARUI');
    }

    public function destroy(Pesanan $pesanan_penjualan)
    {
        if (Auth::user()->cannot('delete', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        if ($pesanan_penjualan->status_proses == 2) { // jika pesanan sudah selsai / 2, maka transaksi sudah tidak dapat diubah/dihapus
            return redirect(route('pesanan-penjualan.show', $pesanan_penjualan->id))
                ->with('sukses', 'STATUS PESANAN SUDAH SELESAI TIDAK BISA DIHAPUS!');
        }
        // Di sini adalah proses logic untuk menghapus transaksi
        // proses hapus transaksinya berada pada method prosesDestroy()
        if ($pesanan_penjualan->status_proses == 1) {
            $idOtorisasi = [16, 13, 9]; // tampung di sini, id yang boleh hapus transaksi SO
            if (in_array(Auth::user()->id, $idOtorisasi)) {
                $this->prosesDestroy($pesanan_penjualan);
            } else {
                return redirect(route('pesanan-penjualan.show', $pesanan_penjualan->id))
                    ->with('sukses', 'DATA PESANAN SUDAH DIPROSES TIDAK BISA DIHAPUS, ANDA BUKAN SPV!');
            }
        } elseif ($pesanan_penjualan->status_proses == 0) {
            $this->prosesDestroy($pesanan_penjualan);
        }

        return redirect(route('pesanan-penjualan.index'))->with('sukses', 'DATA PESANAN NOMER : ' . $pesanan_penjualan->nomer_pesanan_penjualan . ' BERHASIL DIHAPUS');
    }

    public function prosesDestroy($pesanan_penjualan)
    {
        if (Auth::user()->cannot('delete', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        // Update stok gudang dengan menambahkan stok
        foreach ($pesanan_penjualan->rincian as $rincian) {
            UpdateStokJob::dispatchSync('tambah', $pesanan_penjualan->gudang_id, $rincian->produk_id, $rincian->kuantitas);
        }

        // Hapus tabel transaksi stok
        TransaksiStokJob::dispatchSync('hapus', [
            'nomer_ref' => $pesanan_penjualan->nomer_pesanan_penjualan,
        ]);

        // Hapus data invoice
        GenerateInvoicePenjualanJob::dispatchSync('hapus', $pesanan_penjualan->id);

        // Hapus data pesanan
        Pesanan::destroy($pesanan_penjualan->id);

        // Catat log dan kirim notif ke telegram
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menghapus Pesanan Penjualan dengan nomer : ' . $pesanan_penjualan->nomer_pesanan_penjualan
        ]);

        // Tampung nomer yang dihapus untuk digunakan kembali nanti
        NomerDihapus::create([
            'nama_modul' => 'pesanan_penjualan',
            'nomer' => $pesanan_penjualan->nomer_pesanan_penjualan
        ]);

        // Kirim notifikasi ke telegram
        $text = "<strong>SALES ORDER DIHAPUS</strong> \n"
            . "\n"

            . "Nomer : <strong>" . $pesanan_penjualan->nomer_pesanan_penjualan . "</strong> \n"
            . "Jenis penjualan : <strong>" . $pesanan_penjualan->jenis_penjualan . "</strong> \n"
            . "Nama pelanggan : <strong>" . $pesanan_penjualan->pelanggan->nama_pelanggan . "</strong> \n"
            . "Tanggal : <strong>" . date('d-m-Y', strtotime($pesanan_penjualan->tanggal)) . "</strong> \n"
            . "Dihapus oleh : <strong>" . Auth::user()->name . "</strong> \n"
            . "Pada tanggal : <strong>" . date('d-m-Y H:i:s') . "</strong> \n"
            . "\n";
        SendTelegramJob::dispatchSync($text);
    }

    public function getHargaProduk($id)
    {
        if (Auth::user()->cannot('create', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        $hargaProduk = Barang::find($id)->harga_barang1;

        return response()->json($hargaProduk, 200);
    }

    public function storePelangganBaru(Request $request)
    {
        if (Auth::user()->cannot('create', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        // validasi tabel lama
        $request->validate([
            'kode_pelanggan' => 'required',
            'nama_pelanggan' => 'required',
            'detail_alamat' => 'required'
        ]);

        // insert ke data tabel lama
        //Pelanggan::create($request->all());

        // insert ke data tabel baru
        MasterPelanggan::create([
            'kode_pelanggan' => $request->kode_pelanggan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_handphone' => $request->handphone_pelanggan,
            'provinsi' => Province::find($request->provinsi)->name,
            'kota' => $request->kota,
            'detil_alamat' => $request->detail_alamat,
        ]);

        return redirect(route('pesanan-penjualan.create'))->with('sukses', 'Pelanggan ' . $request->nama_pelanggan . ' berhasil ditambahkan');
    }

    public function downloadBerkas($berkas)
    {
        if (Auth::user()->cannot('view', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        return Storage::download('berkas_penjualan/' . $berkas);
    }

    public function downloadExcel(Request $request)
    {
        if (Auth::user()->cannot('view', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        $pesanan_penjualan = Pesanan::with('rincian', 'pelanggan', 'sales')
            ->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal])
            ->get();

        return Excel::download(new PesananPenjualanExport($pesanan_penjualan), 'daftar-pesanan-penjualan.xlsx');
    }

    public function print(Pesanan $pesanan_penjualan)
    {
        if (Auth::user()->cannot('view', Pesanan::class)) abort(403, 'akses tidak diizinkan');

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('v2.penjualan.pesanan.print', [
                'pesanan_penjualan' => $pesanan_penjualan,
                'pesanan_penjualan_rinci' => PesananRinci::with('produk')->where('penjualan_pesanan_id', $pesanan_penjualan->id)->get()
            ]);

        return $pdf->stream();
    }

    public function storeTabelLama($request, $nomerPesananPenjualan)
    {
        $berkas1 = '';
        $berkas2 = '';
        $berkas3 = '';
        $berkas4 = '';
        $berkas5 = '';

        // INSERT DATA KE TABEL LAMA //
        // insert data ke tabel lama penjualan_so
        $so = Penjualan_SO::create([
            'so_nomer' => $nomerPesananPenjualan,
            'so_tanggal' => $request->tanggal,
            'id_pelanggan' => $request->pelanggan_id,
            'jenis_penjualan' => JenisPenjualan::where('jenis_penjualan', $request->jenis_penjualan)->first()->id,
            'keterangan' => $request->keterangan,
            'id_user' => Auth::user()->id,
            'id_sales' => $request->sales_id,
            'no_pesanan' => $request->nomer_pesanan,
            'ekspedisi' => $request->ekspedisi,
            'resi' => $request->resi,
            'penerima' => $request->penerima,
            'alamat_pengiriman' => $request->alamat_penerima
        ]);
        // insert data ke tabel penjualan_so_rinci
        foreach ($request->produk_id as $key => $produk) {

            Penjualan_SO_rinci::create([
                'id_so' => $so->id,
                'id_barang' => $produk,
                'qty_barang' => $request->kuantitas[$key],
                'harga_barang' => $request->harga_produk[$key],
                'diskon_barang' => $request->diskon_persen[$key],
                'diskon_nominal' => $request->diskon_nominal[$key],
                'potongan_admin' => $request->potongan[$key],
                'cashback_ongkir' => $request->cashback[$key],
                'note' => $request->catatan[$key],
            ]);
        }
        //insert berkas pendukung
        if ($request->has('berkas1')) {
            $file = $request->file('berkas1');
            $getFileName = $request->file('berkas1')->getClientOriginalName();
            $berkas1 = str_replace(["-", "/"], "_", $request->nomer_pesanan_penjualan) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas1')->storeAs('berkas_penjualan', $berkas1);
            $request->file('berkas1')
                ->storeAs('uploads/so_penjualan', $berkas1);
            $file->move('uploads/so_penjualan', $berkas1);
        }

        if ($request->has('berkas2')) {
            $file = $request->file('berkas2');
            $getFileName = $request->file('berkas2')->getClientOriginalName();
            $berkas2 = str_replace(["-", "/"], "_", $request->nomer_pesanan_penjualan) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas2')->storeAs('berkas_penjualan', $berkas2);
            $request->file('berkas2')
                ->storeAs('uploads/so_penjualan', $berkas2);
            $file->move('uploads/so_penjualan', $berkas2);
        }

        if ($request->has('berkas3')) {
            $file = $request->file('berkas3');
            $getFileName = $request->file('berkas3')->getClientOriginalName();
            $berkas3 = str_replace(["-", "/"], "_", $request->nomer_pesanan_penjualan) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas3')->storeAs('berkas_penjualan', $berkas3);
            $request->file('berkas3')
                ->storeAs('uploads/so_penjualan', $berkas3);
            $file->move('uploads/so_penjualan', $berkas3);
        }

        if ($request->has('berkas4')) {
            $file = $request->file('berkas4');
            $getFileName = $request->file('berkas4')->getClientOriginalName();
            $berkas4 = str_replace(["-", "/"], "_", $request->nomer_pesanan_penjualan) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas4')->storeAs('berkas_penjualan', $berkas4);
            $request->file('berkas4')
                ->storeAs('uploads/so_penjualan', $berkas4);
            $file->move('uploads/so_penjualan', $berkas4);
        }

        if ($request->has('berkas5')) {
            $file = $request->file('berkas5');
            $getFileName = $request->file('berkas5')->getClientOriginalName();
            $berkas5 = str_replace(["-", "/"], "_", $request->nomer_pesanan_penjualan) . "_" . str_replace(" ", "", $getFileName);
            $request->file('berkas5')->storeAs('berkas_penjualan', $berkas5);
            $request->file('berkas5')
                ->storeAs('uploads/so_penjualan', $berkas5);
            $file->move('uploads/so_penjualan', $berkas5);
        }

        BerkasSalesorder::create([
            'penjualan_so_id' => $so->id,
            'berkas_1' => $berkas1,
            'berkas_2' => $berkas2,
            'berkas_3' => $berkas3,
            'berkas_4' => $berkas4,
            'berkas_5' => $berkas5,
        ]);
        // END INPUT DATA KE TABEL LAMA //
    }
}
