@extends('layouts.vuexy')

@section('header')
Edit Category Asset ( Ubah Kategori Asset )
@endsection


@section('content')

@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/tipeasset">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/tipeasset/{{ $assettype->id}}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>Nama Kategori Asset <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="tipe_asset" value="{{ $assettype->tipe_asset}}">

            <label>Deskripsi Kategori</label>
            <textarea class="form-control" rows="4" name="Keterangan" placeholder="Masukkan Keterangan">{{ $assettype->keterangan}}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/tipeasset" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection