<?php

namespace App\Http\Controllers\v2\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\v2\Pembelian\PenerimaanBarang;
use App\Models\v2\Pembelian\PenerimaanBarangBerkas;
use App\Models\v2\Pembelian\PesananPembelian;
use App\Models\v2\Pembelian\PesananPembelianRinci;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenerimaanBarangController extends Controller
{

    public function index()

    {
        $data['penerimaanBarang'] = PenerimaanBarang::with('rincianBarang', 'rincianBerkas')->get();

        return view('v2.pembelian.penerimaan-barang.index', compact('data'));
    }

    public function create()
    {
        $data['pesananPembelian'] = PesananPembelian::sudahDisetujui()->get();

        return view('v2.pembelian.penerimaan-barang.create', compact('data'));
    }

    public function store(Request $request)
    {
        // merge before validasi
        $request->merge([
            'created_by' => Auth::user()->id,
        ]);

        // validasi request
        $request->validate([
            'pesanan_pembelian_id' => 'required|numeric',
            'nomer_penerimaan_barang' => 'required|unique:second_mysql.penerimaan_barang,nomer_penerimaan_barang',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'rincian.*.item_id' => 'required|numeric',
            'rincian.*.deskripsi_item' => 'nullable',
            'rincian.*.kuantitas' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:pdf,word,jpeg,jpg,png|max:2048',
        ]);

        try {
            // insert penerimaan barang
            $penerimaanBarang = PenerimaanBarang::create($request->except('rincian', 'berkas'));

            try {
                // insert rincian penerimaan barang
                $penerimaanBarang->rincianBarang()->createMany($request->input('rincian'));
            } catch (\Exception $e) {
                $penerimaanBarang->delete();
                exit($e->getMessage());
            }

            try {
                // proses kuantitas sudah diterima di PO
                foreach ($penerimaanBarang->rincianBarang as $rincian) {
                    $this->kuantitasDiterima('tambah', $penerimaanBarang->pesanan_pembelian_id, $rincian->item_id, $rincian->kuantitas);
                }
            } catch (\Exception $e) {
                exit($e->getMessage());
            }

            // cek kuantitas penerimaan barang PO
            $this->cekKuantitasPenerimaanPO($penerimaanBarang);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        // apakah ada berkas yang diupload
        if ($request->has('berkas')) {
            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerPenerimaan = str_replace(['/', '-', ' '], '_', $penerimaanBarang->nomer_penerimaan_barang);
                    $berkas['nama_berkas']->storeAs('berkas_penerimaan_barang', $nomerPenerimaan . '_' . $filename);
                    $penerimaanBarang->rincianBerkas()->create([
                        'nama_berkas' => $nomerPenerimaan . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    exit($e->getMessage());
                }
            }
        }

        // redirect kembali ke daftar penerimaan barang
        return redirect(route('pembelian.penerimaan-barang.index'))->with('sukses', 'DATA PENERIMAAN BARANG BERHASIL DITAMBAHKAN');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['pesananPembelian'] = PesananPembelian::sudahDisetujui()->get();
        $data['penerimaanBarang'] = PenerimaanBarang::findOrFail($id);

        return view('v2.pembelian.penerimaan-barang.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // merge before validasi
        $request->merge([
            'updated_by' => Auth::user()->id,
        ]);

        // validasi request
        $request->validate([
            'pesanan_pembelian_id' => 'required|numeric',
            'nomer_penerimaan_barang' => 'required|unique:second_mysql.penerimaan_barang,nomer_penerimaan_barang,' . $id,
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'rincian.*.item_id' => 'required|numeric',
            'rincian.*.deskripsi_item' => 'nullable',
            'rincian.*.kuantitas' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
        ]);

        $penerimaanBarang = PenerimaanBarang::findOrFail($id);

        // update kuantitas diterima pada tabel PO
        foreach ($penerimaanBarang->rincianBarang as $rincian) {
            $result = $this->kuantitasDiterima('kurang', $penerimaanBarang->pesanan_pembelian_id, $rincian->item_id, $rincian->kuantitas);

            if ($result == false) exit('Sistem error.');
        }

        // hapus rincian penerimaan barang
        $penerimaanBarang->rincianBarang()->delete();

        // update penerimaan barang
        $penerimaanBarang->update($request->except('rincian', 'berkas'));

        // insert rincian penerimaan barang 
        $penerimaanBarang->rincianBarang()->createMany($request->input('rincian'));

        // update kuantitas diterima pada tabel PO
        $penerimaanBarang = PenerimaanBarang::findOrFail($id);
        foreach ($penerimaanBarang->rincianBarang as $rincian) {
            $this->kuantitasDiterima('tambah', $penerimaanBarang->pesanan_pembelian_id, $rincian->item_id, $rincian->kuantitas);
        }

        // cek apakah kuantitas diterima sudah semuanya
        $this->cekKuantitasPenerimaanPO($penerimaanBarang);

        // apakah ada berkas baru yang diupload
        if ($request->has('berkas')) {
            // hapus data berkas dulu
            foreach ($penerimaanBarang->rincianBerkas as $berkas) {
                Storage::delete('berkas_penerimaan_barang/' . $berkas->nama_berkas);
            }
            $penerimaanBarang->rincianBerkas()->delete();

            // upload ulang berkas
            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerPenerimaan = str_replace(['/', '-', ' '], '_', $penerimaanBarang->nomer_penerimaan_barang);
                    $berkas['nama_berkas']->storeAs('berkas_penerimaan_barang', $nomerPenerimaan . '_' . $filename);
                    $penerimaanBarang->rincianBerkas()->create([
                        'nama_berkas' => $nomerPenerimaan . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    exit($e->getMessage());
                }
            }
        }

        // redirect ke form edit
        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    public function destroy($id)
    {
        $penerimaanBarang = PenerimaanBarang::findOrFail($id);

        // hapus berkas
        foreach ($penerimaanBarang->rincianBerkas as $berkas) {
            Storage::delete('berkas_penerimaan_barang/' . $berkas->nama_berkas);
        }

        // update kuantitas diterima pada tabel PO
        foreach ($penerimaanBarang->rincianBarang as $rincian) {
            $this->kuantitasDiterima('kurang', $penerimaanBarang->pesanan_pembelian_id, $rincian->item_id, $rincian->kuantitas);
        }

        // cek apakah kuantitas diterima sudah semuanya
        $this->cekKuantitasPenerimaanPO($penerimaanBarang);

        // delete data
        $penerimaanBarang->delete();

        return redirect(route('pembelian.penerimaan-barang.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
    }

    public function downloadBerkas($nama_berkas)
    {
        return Storage::download('berkas_penerimaan_barang/' . $nama_berkas);
    }

    public function kuantitasDiterima($operator, $pesanan_pembelian_id, $item_id, $kuantitas)
    {
        $poRinci = PesananPembelianRinci::where('pesanan_pembelian_id', $pesanan_pembelian_id)
            ->where('item_id', $item_id)->first();

        if ($operator == 'tambah') {
            $poRinci->kuantitas_diterima += $kuantitas;
            $poRinci->save();

            return true;
        } elseif ($operator == 'kurang') {
            $poRinci->kuantitas_diterima -= $kuantitas;
            $poRinci->save();

            return true;
        }

        return false;
    }

    public function cekKuantitasPenerimaanPO($penerimaanBarang)
    {
        // cek apakah semua item sudah diterima
        // jika sudah ubah status PO menjadi selesai (status_proses = 1)
        $po = PesananPembelian::find($penerimaanBarang->pesanan_pembelian_id);
        $countPO = $po->rincianItem()
            ->whereColumn('kuantitas', '=', 'kuantitas_diterima')
            ->count();

        if ($countPO === $po->rincianItem()->count()) {
            $po->update([
                'status_proses' => 1
            ]);
        } else {
            $po->update([
                'status_proses' => 0
            ]);
        }
    }
}
