<?php

namespace App\Models\v2\Persediaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PindahStokBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'pindah_stok_berkas';
    protected $guarded = [];
}
