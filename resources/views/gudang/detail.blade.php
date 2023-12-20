@extends('layouts.vuexy')

@section('header')
Detail Warehouse ( Detail Data Gudang )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/gudang">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
            <tr>
                    <td style="width: 200px;">Kode Gudang</td>
                    <td>{{ $gudang->kode_gudang }}</td>
                </tr>
                <tr>
                    <td style="width: 200px;">Nama Gudang</td>
                    <td>{{ $gudang->nama_gudang }}</td>
                </tr>
                <tr>
                    <td style="width: 200px;">Deskirpsi Gudang</td>
                    <td>{{ $gudang->deskripsi_gudang }}</td>
                </tr>
                <tr>
                    <td style="width: 200px;">Nama Penanggung Jawab Gudang</td>
                    <td>{{ $gudang->nama_penanggungjawab }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/gudang/{{ $gudang->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/gudang/{{$gudang->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>

@endsection