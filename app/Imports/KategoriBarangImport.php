<?php

namespace App\Imports;

use App\Models\Kategoribarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class KategoriBarangImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new KategoriBarang([
            'nama_kategori'     => $row['nama_kategori'],
            'deskripsi_kategori'    => $row['deskripsi_kategori'],
        ]);
    }
}