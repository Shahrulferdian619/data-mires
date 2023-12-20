<?php

namespace App\Http\Requests\v2\Penjualan;

use Illuminate\Foundation\Http\FormRequest;

class InvoicePenjualanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'akun_bank_id' => 'required',
            'akun_ppn_id' => 'required',
            'akun_diskon_id' => 'required',
            'akun_biayakirim_id' => 'required',
            'pelanggan_id' => 'required',
            'sales_id' => 'required',
            'created_by' => 'required',
            'gudang_id' => 'required',
            'nomer_invoice_penjualan' => 'required|unique:second_mysql.penjualan_invoice,nomer_invoice_penjualan',
            'nomer_ref' => 'nullable',
            'tanggal' => 'required',
            'keterangan' => 'nullable',
            'jenis_penjualan' => 'required',
            'ppn' => 'nullable',
            'nilai_ppn' => 'before:parseDouble|nullable',
            'nomer_pesanan' => 'nullable',
            'resi' => 'nullable',
            'ekspedisi' => 'nullable',
            'penerima' => 'nullable',
            'alamat_penerima' => 'nullable',
            'diskon_persen_global' => 'nullable',
            'diskon_nominal_global' => 'nullable|numeric',
            'biaya_kirim' => 'nullable',
            'grandtotal' => 'nullable|numeric',
            'grandtotal_setelah_diskon' => 'nullable|numeric',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'created_by' => $this->user()->id,
            'jenis_penjualan' => 'KONSINYASI',
            'akun_biayakirim_id' => 139
        ]);
    }

    public function parseDouble($value)
    {
        $cleanValue = str_replace(['.', ','], ['', '.'], $value);
        return (float) str_replace(',', '.', $cleanValue);
    }
}
