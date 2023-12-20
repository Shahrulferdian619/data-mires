<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $table = 'gudangs';

    protected $fillable = ['kode_gudang','nama_gudang','deskripsi_gudang', 'nama_penanggungjawab','aktif'];
}
