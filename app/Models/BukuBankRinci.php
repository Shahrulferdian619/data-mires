<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBankRinci extends Model
{
    protected $table = 'buku_bank_rinci';
    protected $guarded = [];
    protected $keyType = 'string';

    use HasFactory;

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }

    public function bukuBank()
    {
        return $this->belongsTo(BukuBank::class);
    }
}
