<?php

namespace App\Http\Controllers\v2\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\v2\LogAktifitas;
use App\Models\v2\Master\Supplier;
use App\Models\v2\Pembelian\InvoicePembelian;
use App\Models\v2\Pembelian\PesananPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoicePembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['invoicePembelian'] = InvoicePembelian::with('rincianItem.item')->orderBy('tanggal', 'desc')->get();

        return view('v2.pembelian.invoice-pembelian.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['pesananPembelian'] = PesananPembelian::where('status_invoice', 0)->get();
        $data['supplier'] = Supplier::active()->get();

        return view('v2.pembelian.invoice-pembelian.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // prepare validasi
        $request->merge([
            'created_by' => Auth::user()->id,
            'total' => convertToDouble($request->input('total')),
            'biaya_kirim' => convertToDouble($request->input('biaya_kirim')),
            'diskon_nominal_global' => convertToDouble($request->input('diskon_nominal_global')),
            'total_setelah_diskon' => convertToDouble($request->input('total_setelah_diskon')),
            'nilai_ppn' => convertToDouble($request->input('nilai_ppn')),
            'pajaklain1_nominal' => convertToDouble($request->input('pajaklain1_nominal')),
            'grandtotal' => convertToDouble($request->input('grandtotal')),
            'rincian' => array_map(function ($item) {
                $item['harga'] = convertToDouble($item['harga']);
                $item['diskon_nominal'] = convertToDouble($item['diskon_nominal']);
                $item['subtotal'] = convertToDouble($item['subtotal']);

                return $item;
            }, $request->input('rincian')),
        ]);

        // validasi
        $request->validate([
            'pesanan_pembelian_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'nomer_invoice_pembelian' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'ppn' => 'required|numeric',
            'total' => 'required',
            'biaya_kirim' => 'required',
            'diskon_persen_global' => 'required|numeric',
            'diskon_nominal_global' => 'required',
            'total_setelah_diskon' => 'required',
            'nilai_ppn' => 'required',
            'pajaklain1_keterangan' => 'nullable',
            'pajaklain1_persen' => 'nullable|numeric',
            'pajaklain1_nominal' => 'nullable|numeric',
            'grandtotal' => 'required',
            'rincian.*.item_id' => 'required|numeric',
            'rincian.*.deskripsi_item' => 'nullable',
            'rincian.*.kuantitas' => 'required|numeric',
            'rincian.*.diskon_persen' => 'required',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:pdf,word,jpeg,jpg,png|max:2048',
        ]);

        // insert data 
        try {
            $invoicePembelian = InvoicePembelian::create($request->except('rincian', 'berkas'));
            $invoicePembelian->rincianItem()->createMany($request->input('rincian'));

            // update PO status_invoice menjadi 1
            PesananPembelian::findOrFail($invoicePembelian->pesanan_pembelian_id)->update([
                'status_invoice' => 1
            ]);

            // catat log
            LogAktifitas::create([
                'nama_user' => Auth::user()->name,
                'nama_aktifitas' => 'Membuat invoice pembelian nomer : ' . $invoicePembelian->nomer_invoice_pembelian
            ]);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        // jika ada berkas
        if ($request->has('berkas')) {
            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerInvoicePembelian = str_replace(['/', '-', ' '], '_', $invoicePembelian->nomer_invoice_pembelian);
                    $berkas['nama_berkas']->storeAs('berkas_invoice_pembelian', $nomerInvoicePembelian . '_' . $filename);
                    $invoicePembelian->rincianBerkas()->create([
                        'nama_berkas' => $nomerInvoicePembelian . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    exit($e->getMessage());
                }
            }
        }

        return redirect(route('pembelian.invoice-pembelian.edit', $invoicePembelian->id))->with('sukses', 'DATA BERHASIL DITAMBAHKAN');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['invoicePembelian'] = InvoicePembelian::findOrFail($id);
        $data['pesananPembelian'] = PesananPembelian::where('status_invoice', 0)->get();
        $data['supplier'] = Supplier::active()->get();

        return view('v2.pembelian.invoice-pembelian.edit', compact('data'));
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
        $invoicePembelian = InvoicePembelian::findOrFail($id);

        // prepare validasi
        $request->merge([
            'updated_by' => Auth::user()->id,
            'total' => convertToDouble($request->input('total')),
            'biaya_kirim' => convertToDouble($request->input('biaya_kirim')),
            'diskon_nominal_global' => convertToDouble($request->input('diskon_nominal_global')),
            'total_setelah_diskon' => convertToDouble($request->input('total_setelah_diskon')),
            'nilai_ppn' => convertToDouble($request->input('nilai_ppn')),
            'pajaklain1_nominal' => convertToDouble($request->input('pajaklain1_nominal')),
            'grandtotal' => convertToDouble($request->input('grandtotal')),
            'rincian' => array_map(function ($item) {
                $item['harga'] = convertToDouble($item['harga']);
                $item['diskon_nominal'] = convertToDouble($item['diskon_nominal']);
                $item['subtotal'] = convertToDouble($item['subtotal']);

                return $item;
            }, $request->input('rincian')),
        ]);

        // validasi
        $request->validate([
            'pesanan_pembelian_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'nomer_invoice_pembelian' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable',
            'ppn' => 'required|numeric',
            'total' => 'required',
            'biaya_kirim' => 'required',
            'diskon_persen_global' => 'required|numeric',
            'diskon_nominal_global' => 'required',
            'total_setelah_diskon' => 'required',
            'nilai_ppn' => 'required',
            'pajaklain1_keterangan' => 'nullable',
            'pajaklain1_persen' => 'nullable|numeric',
            'pajaklain1_nominal' => 'nullable|numeric',
            'grandtotal' => 'required',
            'rincian.*.item_id' => 'required|numeric',
            'rincian.*.deskripsi_item' => 'nullable',
            'rincian.*.kuantitas' => 'required|numeric',
            'rincian.*.diskon_persen' => 'required',
            'rincian.*.catatan' => 'nullable',
        ]);

        try {
            // update invoice
            $invoicePembelian->update($request->except('rincian', 'berkas'));

            // delete rincian item
            $invoicePembelian->rincianItem()->delete();

            // insert ulang rincian item
            $invoicePembelian->rincianItem()->createMany($request->input('rincian'));

            // catat log
            LogAktifitas::create([
                'nama_user' => Auth::user()->name,
                'nama_aktifitas' => 'Merubah invoice pembelian nomer : ' . $invoicePembelian->nomer_invoice_pembelian
            ]);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        // jika ada berkas baru yang diupload
        if ($request->has('berkas')) {
            // hapus berkas lama dulu
            foreach ($invoicePembelian->rincianBerkas as $berkas) {
                Storage::delete('berkas_invoice_pembelian/' . $berkas->nama_berkas);
            }
            $invoicePembelian->rincianBerkas()->delete(); // delete data berkas

            // upload ulang
            foreach ($request->file('berkas') as $berkas) {
                try {
                    $filename = str_replace(['/', '-', ' '], '_', $berkas['nama_berkas']->getClientOriginalName());
                    $nomerInvoicePembelian = str_replace(['/', '-', ' '], '_', $invoicePembelian->nomer_invoice_pembelian);
                    $berkas['nama_berkas']->storeAs('berkas_invoice_pembelian', $nomerInvoicePembelian . '_' . $filename);
                    $invoicePembelian->rincianBerkas()->create([
                        'nama_berkas' => $nomerInvoicePembelian . '_' . $filename,
                    ]);
                } catch (\Exception $e) {
                    exit($e->getMessage());
                }
            }
        }

        return back()->with('sukses', 'DATA BERHASIL DIPERBARUI');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoicePembelian = InvoicePembelian::findOrFail($id);

        // hapus berkas
        foreach ($invoicePembelian->rincianBerkas as $berkas) {
            Storage::delete('berkas_invoice_pembelian/' . $berkas->nama_berkas);
        }

        // delete data invoice
        $invoicePembelian->delete();

        // ubah status_invoice PO menjadi 0
        PesananPembelian::find($invoicePembelian->pesanan_pembelian_id)->update([
            'status_invoice' => 0
        ]);

        // catat log
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => 'Menghapus invoice pembelian nomer : ' . $invoicePembelian->nomer_invoice_pembelian
        ]);

        return redirect(route('pembelian.invoice-pembelian.index'))->with('sukses', 'DATA BERHASIL DIHAPUS');
    }

    public function downloadBerkas($nama_berkas)
    {
        return Storage::download('berkas_invoice_pembelian/' . $nama_berkas);
    }
}
