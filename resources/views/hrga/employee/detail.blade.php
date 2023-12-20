@extends('layouts.vuexy')

@section('header')
Detail Employee ( Detail Data Karyawan )
@endsection

@section('content')

@if (session()->has('fail'))
    @include('layouts.fail')
@endif

<style>
    .card-body .profile_img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin: 2px auto;
    border: 2px solid #ccc;
    border-radius: 50%;
}
</style>

<a href="/admin/employee">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <img class="m-2 shadow profile_img" src="{{ url('uploads/karyawan/'.$employee->picture) }}" id="previewpicture"><br>
                <label>N.I.K </label>
                <input type="text" class="form-control" readonly value="{{ $employee->nik }}">
                
                <label>Nama Karyawan </label>
                <input type="text" class="form-control" readonly value="{{ $employee->nama_karyawan }}" >
                
                <label>Tanggal Lahir Karyawan </label>
                <input type="date" class="form-control" readonly value="{{ $employee->tanggal_lahir_karyawan }}">
                
                <label>Nomer HP Karyawan </label>
                <input type="text" class="form-control" readonly value="{{ $employee->nomer_hp_karyawan }}" >
                
                <label>Email Karyawan</label>
                <input type="text" class="form-control" readonly value="{{ $employee->email_karyawan }}" >
                
                <label>Alamat Karyawan </label>
                <textarea class="form-control" rows="4" readonly >{{ $employee->alamat_karyawan }}</textarea>
                
                <label>Jabatan Karyawan </label>
                <input type="text" class="form-control" readonly value="{{ $employee->jabatan_karyawan }}" >
                
                <label>Divisi Karyawan</label>
                <input type="text" class="form-control" readonly value="{{ $employee->divisi_karyawan }}" >
                
                <label>Tanggal Masuk Kerja </label>
                <input type="date" class="form-control" readonly value="{{ $employee->tanggal_masuk_kerja }}" >

                <label>Masa Kontrak Kerja </label>
                <input type="date" class="form-control" readonly value="{{ $employee->masa_kontrak }}" >
                
                <label>Tanggal Keluar Kerja</label>
                <input type="date" class="form-control" readonly value="{{ $employee->tanggal_keluar_kerja }}">
                
                <label>Gaji Karyawan </label>
                <input type="text" class="form-control" readonly value="{{ rupiah($employee->gaji_karyawan) }}" >
                
                <label>Keterangan Tambahan</label>
                <textarea class="form-control" rows="4" readonly> {{ $employee->keterangan_tambahan }} </textarea>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <form action="/admin/employee/{{ $employee->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/employee/{{$employee->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>
@endsection