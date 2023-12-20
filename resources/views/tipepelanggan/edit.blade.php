@extends('layouts.vuexy')

@section('header')
Edit Category Customer ( Edit Kategori Customer )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/tipepelanggan/{{ $tipepelanggan->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>Tipe Pelanggan<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="tipepelanggan" placeholder="Masukkan Tipe Pelanggan" value="{{ $tipepelanggan->tipepelanggan}}">

            <label>Deskripsi Tipe Pelanggan</label>
            <textarea class="form-control" rows="4" name="deskripsi_tipepelanggan" placeholder="Masukkan Deskripsi Tipe Pelanggan">{{ $tipepelanggan->deskripsi_tipepelanggan }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/tipepelanggan/{{ $tipepelanggan->id }}" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection