<?php

namespace App\Models\v2\Bukubesar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmumBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'jurnal_umum_berkas';
    protected $guarded = [];
}
