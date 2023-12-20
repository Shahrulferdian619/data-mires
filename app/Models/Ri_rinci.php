<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ri_rinci extends Model
{
    use HasFactory;

    protected $table = 'ri_rinci';
    protected $guarded = [];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
