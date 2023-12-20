<?php

namespace App\Services\v2\Pembelian;

use App\Models\v2\Pembelian\PermintaanPembelian;
use App\Models\v2\Pembelian\PermintaanPembelianRinci;

class PermintaanPembelianService
{
    public function tambahKuantitasDiproses($po)
    {
        foreach ($po->rincianItem as $rincian) {
            $prRinci = PermintaanPembelianRinci::where('permintaan_pembelian_id', $po->permintaan_pembelian_id)
                ->where('item_id', $rincian->item_id)->first();

            try {
                $newKuantitas = $prRinci->kuantitas_diproses + $rincian->kuantitas;
                $prRinci->kuantitasDiproses($newKuantitas);

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    /**
     * Melakukan cek kuantitas dan kuantitas diproses
     */
    public function cekRincianKuantitasDanKuantitasDiproses($permintaan_pembelian_id)
    {
        $pr = PermintaanPembelian::find($permintaan_pembelian_id);

        $count = $pr->rincianPermintaan()
            ->whereColumn('kuantitas', '=', 'kuantitas_diproses')
            ->count();

        if ($count === $pr->rincianPermintaan()->count()) {
            $pr->sudahDiproses(1);
        } else {
            $pr->sudahDiproses(0);
        }
    }
}
