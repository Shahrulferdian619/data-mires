@extends('layouts.vuexy')

@section('header')
Detail Customer ( Detail Pelanggan )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/tipepelanggan">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 270px;">Tipe Pelanggan</td>
                    <td>{{ $tipepelanggan->tipepelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 200px;">Deskirpsi Tipe Pelanggan</td>
                    <td>{{ $tipepelanggan->deskripsi_tipepelanggan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/tipepelanggan/{{ $tipepelanggan->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/tipepelanggan/{{$tipepelanggan->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div> 

@endsection