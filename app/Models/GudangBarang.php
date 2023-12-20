<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarang extends Model
{
   protected $table = 'gudang_barang';

   public function gudang(){
      return $this->belongsTo(Gudang::class);
   }
   public function barang(){
      return $this->belongsTo(Barang::class, 'barang_id');
   }
}
