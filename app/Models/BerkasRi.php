<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasRi extends Model
{
    use HasFactory;
    
    protected $table = 'berkas_ri';

    public function ri(){
        return $this->belongsTo(Ri::class);
    }
}
