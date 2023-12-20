<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalVoucherRinci extends Model
{
    protected $table = 'jurnal_voucher_rinci';
    protected $guarded = [];
    protected $keyType = 'string';
    
    use HasFactory;

    
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}
