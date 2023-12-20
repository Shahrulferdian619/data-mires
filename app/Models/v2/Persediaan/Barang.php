<?php

namespace App\Models\v2\Persediaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    public function scopeProduk($query)
    {
        return $query->where('type',1);
    }
}
