<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmtpembelian_rinci extends Model
{
    use HasFactory;

    protected $table = 'pmtpembelian_rinci';
    protected $fillable = [
        'pmtpembelian_id',
        'barang_id',
        'qty',
        'harga',
        'note',
        'is_received',
        'user_id'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
