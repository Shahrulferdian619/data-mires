<?php

use App\Models\BukuBank;
use App\Models\BukuBankRinci;
use App\Models\BukuBesar;
use App\Models\Coa;
use App\Models\Gl;
use App\Models\JurnalVoucherRinci;
use App\Models\TipeCoa;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

function bulan_romawi($bln)
{
    switch ($bln) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}

function convertToDouble($nilai_rupiah)
{
    $nilai_rupiah = str_replace(".","", $nilai_rupiah);
    $nilai_rupiah = str_replace(",", ".", $nilai_rupiah);
    $nilai_double = (double)$nilai_rupiah;

    return $nilai_double;
}

function rupiah($jumlah)
{
    if (!is_numeric($jumlah)) {
        $jumlah = 0;
    }
    return "Rp. " . number_format($jumlah, 0, ',', '.');
}

function rupiahReport($jumlah)
{
    if (!is_numeric($jumlah)) {
        $jumlah = 0;
    }
    return number_format($jumlah, 2, ',', '.');
}

function tanggal($date)
{
    return Carbon::parse($date)->format('d F Y');
}

function explodeRupiah($string)
{
    $hilangkanRupiahNya = str_replace('Rp. ', '', $string);
    $result = str_replace('.', '', $hilangkanRupiahNya);

    return $result;
}

// Insert ke buku besar
function storeBukuBesar($coa_id, $tanggal, $nomer, $deskripsi, $sumber, $debit, $kredit)
{
    $id = Uuid::uuid4()->toString();
    BukuBesar::create([
        'id' => $id,
        'coa_id' => $coa_id,
        'tahun' =>  date('Y', strtotime($tanggal)),
        'tanggal' => $tanggal,
        'nomer' => $nomer,
        'deskripsi' => $deskripsi,
        'sumber' => $sumber,
        'debit' => $debit,
        'kredit' => $kredit,
        'is_deleted' => 1
    ]);
}

// destroy item buku besar
function destroyBukuBesar($sumber, $nomer)
{
    BukuBesar::whereSumber($sumber)->whereNomer($nomer)->delete();
}

// soft destroy buku besar
function softDestroyBukuBesar($sumber, $nomer)
{
    BukuBesar::whereSumber($sumber)->whereNomer($nomer)->update(['is_deleted' => 0]);
}

//insert ke buku bank
function storeBukuBank($buku_bank_id, $coa_id, $nomer, $tanggal, $sumber,  $deskripsi)
{

    $status = false;

    $totalCoa = count($coa_id);
    for ($i = 0; $i < $totalCoa; $i++) {
        $coa = Coa::whereId($coa_id[$i])
            ->whereIn('id_coatype', [1, 13]) // kalau ingin mengubah type coa id mana yang masuk ke kas bank bisa diubah di sini
            ->first();

        if (!empty($coa)) {
            $status = true;
        }
    }

    if ($status) {
        BukuBank::create([
            'id' => $buku_bank_id,
            'nomer' => $nomer,
            'tanggal' => $tanggal,
            'sumber' => $sumber,
            'deskripsi' => $deskripsi,
            'is_deleted' => 1
        ]);
    }
}
function storeBukuBankCR($buku_bank_id, $nomer, $tanggal, $sumber,  $deskripsi)
{
    BukuBank::create([
        'id' => $buku_bank_id,
        'nomer' => $nomer,
        'tanggal' => $tanggal,
        'sumber' => $sumber,
        'deskripsi' => $deskripsi,
        'is_deleted' => 1
    ]);
}
function storeBukuBankRinci($buku_bank_id, $coa_id, $nominal, $memo, $tipe)
{
    $id = Uuid::uuid4()->toString();

    $coa = Coa::whereId($coa_id)
        ->whereIn('id_coatype', [1, 13]) // kalau ingin mengubah type coa id mana yang masuk ke kas bank bisa diubah di sini
        ->first();

    if (!empty($coa)) {
        BukuBankRinci::create([
            'id' => $id,
            'buku_bank_id' => $buku_bank_id,
            'coa_id' => $coa_id,
            'nominal' => $nominal,
            'memo' => $memo,
            'tipe' => $tipe,
        ]);
    }
}

// destroy item buku besar
function destroyBukuBank($sumber, $nomer)
{
    BukuBank::whereSumber($sumber)->whereNomer($nomer)->delete();
}

// soft destroy buku besar
function softDestroyBukuBank($sumber, $nomer)
{
    BukuBank::whereSumber($sumber)->whereNomer($nomer)->update(['is_deleted' => 0]);
}

function storeGeneralLedger($data)
{
    GL::create($data);
}

function destroyGeneralLedger($nomer, $sumber)
{
    Gl::where(['nomer' => $nomer, 'sumber' => $sumber])->delete();
}

function tanggalSekarang()
{
    $tanggal = Carbon::now();
    return $tanggal->toDateString();
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " Seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " Seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}
