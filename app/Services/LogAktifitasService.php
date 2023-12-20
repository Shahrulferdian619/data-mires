<?php

namespace App\Services;

use App\Models\v2\LogAktifitas;
use Illuminate\Support\Facades\Auth;

class LogAktifitasService
{
    public function createLog($aktifitas)
    {
        LogAktifitas::create([
            'nama_user' => Auth::user()->name,
            'nama_aktifitas' => $aktifitas
        ]);
    }
}
