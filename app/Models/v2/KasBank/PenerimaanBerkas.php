<?php

namespace App\Models\v2\KasBank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBerkas extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'kasbank_penerimaan_berkas';
    protected $guarded = [];
}
