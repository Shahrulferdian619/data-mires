<?php

namespace App\Services;

use App\Models\v2\NomerDihapus;

class CekNomerDihapusService
{

    public static function cekNomer($namaModul)
    {
        return NomerDihapus::where('nama_modul', $namaModul)
            ->where('sudah_dipakai', 0)
            ->whereMonth('created_at', date('m'))
            ->first();
    }
}
