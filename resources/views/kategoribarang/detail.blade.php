@extends('layouts.vuexy')

@section('header')
Detail Item Category ( Detail Kategori Barang )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/kategoribarang">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 200px;">Nama Kategori</td>
                    <td>{{ $kategoribarang->nama_kategori }}</td>
                </tr>
                <tr>
                    <td style="width: 200px;">Deskirpsi Kategori</td>
                    <td>{{ $kategoribarang->deskripsi_kategori }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/kategoribarang/{{ $kategoribarang->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/kategoribarang/{{$kategoribarang->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>

@endsection