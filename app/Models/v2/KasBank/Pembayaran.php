<?php

namespace App\Models\v2\KasBank;

use App\Models\User;
use App\Models\v2\Master\Coa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'kasbank_pembayaran';
    protected $guarded = [];

    public function rincianAkun()
    {
        return $this->hasMany(PembayaranRinci::class, 'kasbank_pembayaran_id');
    }

    public function rincianBerkas()
    {
        return $this->hasMany(PembayaranBerkas::class, 'kasbank_pembayaran_id');
    }

    public function bank()
    {
        return $this->belongsTo(Coa::class, 'bank_id');
    }

    public function dibuatOleh()
    {
        return $this->setConnection('mysql')->belongsTo(User::class,'created_by');
    }

    public function diupdateOleh()
    {
        return $this->setConnection('mysql')->belongsTo(User::class,'updated_by');
    }
}
