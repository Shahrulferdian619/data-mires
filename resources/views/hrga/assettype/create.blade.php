@extends('layouts.vuexy')

@section('header')
Create Category Asset ( tambah Kategori Asset )
@endsection


@section('content')

@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/tipeasset">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/tipeasset" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Nama Kategori Asset <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="tipe_asset" >

            <label>Deskripsi Kategori</label>
            <textarea class="form-control" rows="4" name="Keterangan" ></textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/tipeasset" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection