@extends('layouts.vuexy')

@section('header')
Rincian List Account (COA) (Daftar Akun)
@endsection


@section('content')

<a href="{{ route('admin.coa.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 270px;">Nomer Akun</td>
                    <td>{{ $coa->nomer_coa }}</td>
                </tr>
                <tr>
                    <td style="width: 270px;">Nama Akun</td>
                    <td>{{ $coa->nama_coa }}</td>
                </tr>
                <tr>
                    <td style="width: 270px;">Tipe Akun</td>
                    <td>{{ $coa->tipeCoa->tipecoa }}</td>
                </tr>
             
                <tr>
                    <td style="width: 270px;">Saldo Awal</td>
                    <td>{{ rupiah($coa->saldo_awal) }}</td>
                </tr>
                <tr>
                    <td style="width: 270px;">Keterangan</td>
                    <td>{{ empty($coa->keterangan) ? "-" : $coa->keterangan }}</td>
                </tr>
                
                
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.coa.destroy', ['coa' => $coa->id]) }}" method="POST">
        <div class="card-body">
            <a href="{{ route('admin.coa.edit', ['coa' => $coa->id]) }}" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div> 

@endsection