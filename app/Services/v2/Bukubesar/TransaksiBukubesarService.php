<?php

namespace App\Services\v2\Bukubesar;

use App\Models\v2\Bukubesar\Bukubesar;
use Illuminate\Support\Facades\Log;

class TransaksiBukubesarService
{
    public function createData($data)
    {
        try {
            $transaksi = new Bukubesar();

            $transaksi->coa_id = $data['coa_id'];
            $transaksi->sumber_id = $data['sumber_id'];
            $transaksi->tahun = $data['tahun'];
            $transaksi->tanggal = $data['tanggal'];
            $transaksi->nomer_sumber = $data['nomer_sumber'];
            $transaksi->sumber_transaksi = $data['sumber_transaksi'];
            $transaksi->nominal = $data['nominal'];
            $transaksi->tipe_mutasi = $data['tipe_mutasi'];
            $transaksi->keterangan = $data['keterangan'];

            $transaksi->save();

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    public function deleteData($data)
    {
        Bukubesar::where('sumber_id', $data['sumber_id'])->where('sumber_transaksi', $data['sumber_transaksi'])->delete();

        return true;
    }
}
