<?php

namespace App\Http\Requests\v2\Persediaan;

use Illuminate\Foundation\Http\FormRequest;

class PindahStokRequest extends FormRequest
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
            'created_by' => $this->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $idPindahStok = $this->route('pindah_stok');

        return [
            'created_by' => 'required|numeric',
            'nomer_ref' => 'required|unique:second_mysql.pindah_stok,nomer_ref,' . $idPindahStok,
            'tanggal' => 'required',
            'tanggal_kirim' => 'nullable',
            'keterangan' => 'nullable',
            'gudang_asal_id' => 'required',
            'gudang_tujuan_id' => 'required',
            'rincian.*.produk_id' => 'required',
            'rincian.*.kuantitas' => 'required',
            'rincian.*.catatan' => 'nullable',
        ];
    }
}
