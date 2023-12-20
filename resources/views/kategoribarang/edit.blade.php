@extends('layouts.vuexy')

@section('header')
Edit Item Category ( Ubah Kategori Barang )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/kategoribarang/{{ $kategoribarang->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>Nama Kategori Barang<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_kategori" value="{{ $kategoribarang->nama_kategori }}">

            <label>Deskripsi Kategori</label>
            <textarea class="form-control" rows="4" name="deskripsi_kategori" value="{{ $kategoribarang->deskripsi_kategori }}">{{$kategoribarang->deskripsi_kategori}}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/kategoribarang/{{ $kategoribarang->id }}" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection