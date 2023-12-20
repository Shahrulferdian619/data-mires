<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fakturpembelian_rinci extends Model
{
    use HasFactory;

    protected $table = 'fakturpembelian_rinci';

    protected $fillable = ['fakturpembelian_id', 'barang_id', 'qty', 'harga', 'note'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function faktur()
    {
        return $this->belongsTo(fakturpembelian::class, 'fakturpembelian_id');
    }
}
