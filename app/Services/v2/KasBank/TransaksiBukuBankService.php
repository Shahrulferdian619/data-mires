<?php

namespace App\Services\v2\KasBank;

use App\Models\v2\KasBank\BukuBank;
use Illuminate\Support\Facades\Log;

class TransaksiBukuBankService
{

    public function createData($request, $tipeMutasi, $tipeTransaksi)
    {
        try {
            $transaksiBukuBank = new BukuBank();
            $transaksiBukuBank->bank_id = $request->input('bank_id');
            $transaksiBukuBank->tanggal = $request->input('tanggal');
            $transaksiBukuBank->nomer_sumber = $request->input('nomer');
            $transaksiBukuBank->tipe_transaksi = $tipeTransaksi;
            $transaksiBukuBank->keterangan = $request->input('keterangan');
            $transaksiBukuBank->nominal_mutasi = $request->input('nominal');
            $transaksiBukuBank->tipe_mutasi = $tipeMutasi;
            $transaksiBukuBank->save();

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function createRincian($data)
    {
        try {
            $transaksiBukuBank = new BukuBank();
            $transaksiBukuBank->bank_id = $data['coa_id'];
            $transaksiBukuBank->tanggal = $data['tanggal'];
            $transaksiBukuBank->nomer_sumber = $data['nomer'];
            $transaksiBukuBank->tipe_transaksi = $data['tipe_transaksi'];
            $transaksiBukuBank->keterangan = $data['catatan'];
            $transaksiBukuBank->nominal_mutasi = $data['nominal'];
            $transaksiBukuBank->tipe_mutasi = $data['tipe_mutasi'];
            $transaksiBukuBank->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function deleteData($nomerSumber, $tipeTransaksi)
    {
        try {
            BukuBank::where('nomer_sumber', $nomerSumber)->where('tipe_transaksi', $tipeTransaksi)->delete();

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
