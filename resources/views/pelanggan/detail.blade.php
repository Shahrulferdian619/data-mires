@extends('layouts.vuexy')

@section('header')
Detail Customer ( Detail Data Pelanggan )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/pelanggan">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 300px;">Nama Pelanggan</td>
                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Tipe Pelanggan</td>
                    <td>{{ $pelanggan->tipepelanggan->tipepelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kode Pelanggan</td>
                    <td>{{ $pelanggan->kode_pelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kode Area</td>
                    <td>{{ $pelanggan->kode_area }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Handphone Pelanggan</td>
                    <td>{{ $pelanggan->handphone_pelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Email Pelanggan 1</td>
                    <td>{{ $pelanggan->email_pelanggan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Negara</td>
                    <td>{{ $pelanggan->negara }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Provinsi</td>
                    <td>{{ $pelanggan->provinsi }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kota</td>
                    <td>{{ $pelanggan->kota }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kecamatan</td>
                    <td>{{ $pelanggan->kecamatan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Detail Alamat</td>
                    <td>{{ $pelanggan->detail_alamat }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Deskirpsi Pelanggan</td>
                    <td>{{ $pelanggan->deskripsi_pelanggan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/pelanggan/{{ $pelanggan->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/pelanggan/{{$pelanggan->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>

@endsection