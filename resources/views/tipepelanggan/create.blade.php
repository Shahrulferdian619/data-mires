@extends('layouts.vuexy')

@section('header')
Create Customer Category ( Tambah Kategori Pelanggan )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/tipepelanggan" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Tipe Pelanggan<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="tipepelanggan" >

            <label>Deskripsi Tipe Pelanggan</label>
            <textarea class="form-control" rows="4" name="deskripsi_tipepelanggan" ></textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/tipepelanggan" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection