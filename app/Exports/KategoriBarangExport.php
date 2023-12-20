<?php

namespace App\Exports;

use App\Models\Kategoribarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriBarangExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Kategoribarang::all();
        return Kategoribarang::select('nama_kategori', 'deskripsi_kategori')->get();
    }

    public function headings(): array
    {
        return array_keys($this->collection()->first()->toArray());
    }

    // public function headings(): array
    // {
    //     return [
    //         'Nama Kategori',
    //         'Deskripsi Kategori'
    //     ];
    // }
}
