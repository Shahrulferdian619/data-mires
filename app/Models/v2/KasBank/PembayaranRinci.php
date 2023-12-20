<?php

namespace App\Models\v2\KasBank;

use App\Models\v2\Master\Coa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranRinci extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'kasbank_pembayaran_rinci';
    protected $guarded = [];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}
