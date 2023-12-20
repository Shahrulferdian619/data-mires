<?php

namespace App\Http\Requests\v2\Pembelian;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananPembelianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'created_by' => $this->user()->id,
            'total' => convertToDouble($this->input('total')),
            'total_setelah_diskon' => convertToDouble($this->input('total_setelah_diskon')),
            'diskon_nominal_global' => convertToDouble($this->input('diskon_nominal_global')),
            'nilai_ppn' => convertToDouble($this->input('nilai_ppn')),
            'grandtotal' => convertToDouble($this->input('grandtotal')),
            'rincian' => array_map(function ($item) {
                $item['diskon_nominal'] = convertToDouble($item['diskon_nominal']);
                $item['subtotal'] = convertToDouble($item['subtotal']);

                return $item;
            }, $this->input('rincian')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permintaan_pembelian_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'nomer_pesanan_pembelian' => 'required|unique:second_mysql.pesanan_pembelian',
            'tanggal' => 'required|date',
            'ppn' => 'required|numeric',
            'total' => 'required|numeric',
            'biaya_kirim' => 'nullable|numeric',
            'diskon_persen_global' => 'required|numeric',
            'diskon_nominal_global' => 'required|numeric',
            'total_setelah_diskon' => 'required|numeric',
            'nilai_ppn' => 'required|numeric',
            'grandtotal' => 'required|numeric',
            'rincian.*.item_id' => 'required|numeric',
            'rincian.*.deskripsi_item' => 'nullable',
            'rincian.*.kuantitas' => 'required|numeric|min:1',
            'rincian.*.harga' => 'required|numeric',
            'rincian.*.diskon_persen' => 'required|numeric',
            'rincian.*.diskon_nominal' => 'required|numeric',
            'rincian.*.subtotal' => 'required|numeric',
            'rincian.*.catatan' => 'nullable',
            'berkas.*.nama_berkas' => 'nullable|mimes:jpg,jpeg,png,pdf',
        ];
    }
}
