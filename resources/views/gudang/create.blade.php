@extends('layouts.vuexy')

@section('header')
Create Warehouse ( Tambah Data Gudang )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/gudang" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Kode Gudang<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_gudang" placeholder="Masukkan Kode Gudang">
            <label>Nama Gudang<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_gudang" placeholder="Masukkan Nama Gudang">
            <label>Deskripsi Gudang</label>
            <textarea class="form-control" rows="4" name="deskripsi_gudang" placeholder="Masukkan Deskripsi Gudang"></textarea>
            <label>Nama Penanggung Jawab Gudang</label>
            <input type="text" class="form-control" name="nama_penanggungjawab" placeholder="Masukkan Nama Pennggung Jawab Gudang">
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/gudang" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection