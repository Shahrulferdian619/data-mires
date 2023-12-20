<?php

namespace App\Http\Controllers\v2\Penjualan;

use App\Http\Controllers\Controller;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Coa;
use App\Models\v2\Penjualan\InvoicePenjualan;
use App\Models\v2\Penjualan\PenerimaanPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenerimaanPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['penerimaanPenjualan'] = PenerimaanPenjualan::with('bank')->get();

        return view('v2.penjualan.penerimaan-penjualan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['invoice'] = InvoicePenjualan::where('status_proses', 0)->get();
        $data['akun_potongan'] = Coa::whereIn('coa_tipe_id', [11, 13, 14, 15])->get();
        $data['akun_cashback'] = Coa::whereIn('coa_tipe_id', [11, 15])->get();
        $data['akun_bank'] = Coa::bank()->get();

        return view('v2.penjualan.penerimaan-penjualan.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // insert header penerimaan penjualan
        $penerimaanPenjualan = PenerimaanPenjualan::create([
            'created_by' => Auth::user()->id,
            'akun_bank_id' => $request->input('akun_bank_id'),
            'nomer_bukti' => $request->input('nomer_bukti'),
            'tanggal' => $request->input('tanggal'),
            'jumlah_pembayaran' => $request->input('jumlah_pembayaran'),
            'keterangan' => $request->input('keterangan'),
        ]);

        // masukkan rincian penerimaan penjualan & update sudah terbayar tabel penjualan invoice
        foreach ($request->input('rincian') as $rincian) {
            //insert rincian penerimaan penjualan
            $penerimaanPenjualanRinci = $penerimaanPenjualan->rincianPenerimaan()->create([
                'penjualan_invoice_id' => $rincian['penjualan_invoice_id'],
                'nominal_pembayaran' => $rincian['nominal_pembayaran'],
                'bayar' => $rincian['bayar'],
            ]);

            //update sudah terbayar invoice penjualan
            $invoice = InvoicePenjualan::findOrFail($rincian['penjualan_invoice_id']);
            $invoice->update([
                'sudah_terbayar' => $invoice->sudah_terbayar + $rincian['bayar']
            ]);

            //update status invoice & pesanan penjualan apabila nilai grandtotal setelah diskon == sudah terbayar
            if ($invoice->grandtotal_setelah_diskon == $invoice->sudah_terbayar) {
                $invoice->update([ // update status menjadi lunas (1 == lunas)
                    'status_proses' => 1
                ]);

                $invoice->pesanan()->update([ // update status menjadi 2 (2 == lunas)
                    'status_proses' => 2
                ]);
            }

            //jika ada potongan, insert data ke tabel potongan
            if ($rincian['potongan'] != 0) {
                $penerimaanPenjualanRinci->rincianPotongan()->create([
                    'akun_potongan_id' => $rincian['akun_potongan_id'],
                    'potongan' => $rincian['potongan'],
                ]);
            }
        }

        // catat log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Membuat penerimaan penjualan nomer : ' . $penerimaanPenjualan->nomer_bukti,
        ]);

        // jika ada berkas, upload berkas tsb
        if ($request->hasFile('berkas')) {
            $getFile = $request->file('berkas')->getClientOriginalName();
            $request->file('berkas')->storeAs('berkas_penerimaan_penjualan', $getFile);

            // insert ke tabel penerimaan berkas
            $penerimaanPenjualan->berkas()->create([
                'nama_berkas' => $getFile
            ]);
        }

        // redirect kembali ke halaman daftar penerimaan penjualan
        return redirect(route('penerimaan-penjualan.index'))->with('sukses', 'DATA BERHASIL DIBUAT');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['penerimaanPenjualan'] = PenerimaanPenjualan::findOrFail($id);

        return view('v2.penjualan.penerimaan-penjualan.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['penerimaanPenjualan'] = PenerimaanPenjualan::findOrFail($id);
        $data['akun_potongan'] = Coa::whereIn('coa_tipe_id', [11, 13, 14, 15])->get();
        $data['akun_cashback'] = Coa::whereIn('coa_tipe_id', [11, 15])->get();
        $data['akun_bank'] = Coa::bank()->get();

        return view('v2.penjualan.penerimaan-penjualan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penerimaanPenjualan = PenerimaanPenjualan::findOrfail($id);

        // update header penerimaan penjualan
        $penerimaanPenjualan->update([
            'created_by' => Auth::user()->id,
            'akun_bank_id' => $request->input('akun_bank_id'),
            'nomer_bukti' => $request->input('nomer_bukti'),
            'tanggal' => $request->input('tanggal'),
            'jumlah_pembayaran' => $request->input('jumlah_pembayaran'),
            'keterangan' => $request->input('keterangan'),
        ]);

        // sebelum hapus rincian penerimaan, update data sudah bayar pada invoice penjualan dulu
        foreach ($penerimaanPenjualan->rincianPenerimaan as $rincian) {
            $invoice = InvoicePenjualan::findOrFail($rincian->penjualan_invoice_id);

            // jika terdapat potongan pada rincian, tambahkan juga nominal potongan
            // if ($rincian->rincianPotongan()->exists()) $potongan = $rincian->rincianPotongan[0]->potongan;
            // else $potongan = 0;
            $invoice->update([
                'sudah_terbayar' => $invoice->sudah_terbayar - $rincian->bayar
            ]);

            //update status invoice & pesanan penjualan apabila nilai grandtotal setelah diskon != sudah terbayar
            if ($invoice->grandtotal_setelah_diskon != $invoice->sudah_terbayar) {
                $invoice->update([ // ubah status menjad 0 (belum lunas)
                    'status_proses' => 0
                ]);

                $invoice->pesanan()->update([ // ubah status menjadi 1 (sudah diproses/dikirim)
                    'status_proses' => 1
                ]);
            }
        }

        // hapus rincian penerimaan penjualan & potongan penjualan bila ada
        $penerimaanPenjualan->rincianPenerimaan()->delete();

        // insert data ulang rincian penerimaan penjualan & potongan penjualan
        foreach ($request->input('rincian') as $rincian) {
            //insert rincian penerimaan penjualan
            $penerimaanPenjualanRinci = $penerimaanPenjualan->rincianPenerimaan()->create([
                'penjualan_invoice_id' => $rincian['penjualan_invoice_id'],
                'nominal_pembayaran' => $rincian['nominal_pembayaran'],
                'bayar' => $rincian['bayar'],
            ]);

            //update sudah terbayar invoice penjualan
            $invoice = InvoicePenjualan::findOrFail($rincian['penjualan_invoice_id']);
            $invoice->update([
                'sudah_terbayar' => $invoice->sudah_terbayar + $rincian['bayar']
            ]);

            //update status invoice & pesanan penjualan apabila nilai grandtotal setelah diskon == sudah terbayar
            if ($invoice->grandtotal_setelah_diskon == $invoice->sudah_terbayar) {
                $invoice->update([ // update status menjadi lunas (1 == lunas)
                    'status_proses' => 1
                ]);

                $invoice->pesanan()->update([ // update status menjadi 2 (2 == lunas)
                    'status_proses' => 2
                ]);
            }

            //jika ada potongan, insert data ke tabel potongan
            if ($rincian['potongan'] != 0) {
                $penerimaanPenjualanRinci->rincianPotongan()->create([
                    'akun_potongan_id' => $rincian['akun_potongan_id'],
                    'potongan' => $rincian['potongan'],
                ]);
            }
        }

        // catat log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Merubah penerimaan penjualan nomer : ' . $penerimaanPenjualan->nomer_bukti,
        ]);

        // jika ada berkas, hapus berkas lama & upload ulang
        if ($request->hasFile('berkas')) {
            //delete berkas lama & hapus data di tabel
            Storage::delete('berkas_penerimaan_penjualan/' . $penerimaanPenjualan->berkas[0]->nama_berkas);
            $penerimaanPenjualan->berkas()->delete();

            //upload ulang berkas baru
            $getFile = $request->file('berkas')->getClientOriginalName();
            $request->file('berkas')->storeAs('berkas_penerimaan_penjualan', $getFile);

            // insert ke tabel penerimaan berkas
            $penerimaanPenjualan->berkas()->create([
                'nama_berkas' => $getFile
            ]);
        }

        // redirect kembali ke halaman daftar penerimaan penjualan
        return redirect(route('penerimaan-penjualan.index'))->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penerimaanPenjualan = PenerimaanPenjualan::findOrFail($id);

        if ($penerimaanPenjualan->berkas()->exists()) { //jika ada berkas, hapus file berkas tsb
            Storage::delete('berkas_penerimaan_penjualan/' . $penerimaanPenjualan->berkas[0]->nama_berkas); //delete berkas
        }

        // sebelum hapus rincian penerimaan, update data sudah bayar pada invoice penjualan dulu
        foreach ($penerimaanPenjualan->rincianPenerimaan as $rincian) {
            $invoice = InvoicePenjualan::findOrFail($rincian->penjualan_invoice_id);

            // jika terdapat potongan pada rincian, tambahkan juga nominal potongan
            $invoice->update([
                'sudah_terbayar' => $invoice->sudah_terbayar - $rincian->bayar,
            ]);

            //update status invoice & pesanan penjualan apabila nilai grandtotal setelah diskon != sudah terbayar
            if ($invoice->grandtotal_setelah_diskon != $invoice->sudah_terbayar) {
                $invoice->update([ // ubah status menjad 0 (belum lunas)
                    'status_proses' => 0
                ]);

                $invoice->pesanan()->update([ // ubah status menjadi 1 (sudah diproses/dikirim)
                    'status_proses' => 1
                ]);
            }
        }

        // hapus rincian penerimaan penjualan & potongan penjualan bila ada
        $penerimaanPenjualan->delete();

        // redirect kembali ke halaman daftar penerimaan penjualan
        return redirect(route('penerimaan-penjualan.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
    }

    public function getDetilInvoice(Request $request)
    {
        $dataInvoice = InvoicePenjualan::with('pelanggan')->findOrFail($request->input('id'));

        return response()->json($dataInvoice, 200);
    }
}
