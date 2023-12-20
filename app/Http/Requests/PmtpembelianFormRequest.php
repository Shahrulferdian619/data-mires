<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PmtpembelianFormRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nomer_pmtpembelian' => 'bail|required|unique:pmtpembelian',
            'tanggal' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nomer_pmtpembelian.required' => 'Nomer permintaan harus diisi.',
            'nomer_pmtpembelian.unique' => 'Nomer permintaan sudah ada.',
            'tanggal.required' => 'Tanggal permintaan harus disi.',
        ];
    }
}
