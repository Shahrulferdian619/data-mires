<?php

namespace App\Http\Requests\v2\Pembelian;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanPembelianRequest extends FormRequest
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
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $idPermintaan = $this->route('permintaan_pembelian');

        return [
            'created_by' => 'required',
            'tipe_permintaan' => ['required', 'numeric', 'max:10'],
            'nomer_permintaan_pembelian' => [
                'required',
                'unique:second_mysql.permintaan_pembelian,nomer_permintaan_pembelian,' . $idPermintaan,
            ],
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|max:255',
            'catatan_direktur' => 'nullable|max:255',
            'catatan_komisaris' => 'nullable|max:255',
            'rincian.*.item_id' => 'required|numeric',
            'rincian.*.deskripsi_item' => 'nullable',
            'rincian.*.kuantitas' => 'required|numeric',
            'rincian.*.harga' => 'nullable|numeric',
            'berkas.*.nama_berkas' => ['nullable', 'mimes:jpeg,png,jpg,pdf,word', 'max:2048'],
        ];
    }
}
