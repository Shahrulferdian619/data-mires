<?php

namespace App\Models\v2\Bukubesar;

use App\Models\v2\Bukubesar\JurnalUmumBerkas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'jurnal_umum';
    protected $guarded = [];

    public function rincian()
    {
        return $this->hasMany(JurnalUmumRinci::class, 'jurnal_umum_id');
    }

    public function berkas()
    {
        return $this->hasMany(JurnalUmumBerkas::class, 'jurnal_umum_id');
    }
}
