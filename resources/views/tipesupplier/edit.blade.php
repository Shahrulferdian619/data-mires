@extends('layouts.vuexy')

@section('header')
Edit Category Supplier ( Ubah Data Kategori Supplier )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/tipesupplier/{{ $tipesupplier->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>Tipe supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="tipesupplier" placeholder="Masukkan Tipe supplier" value="{{ $tipesupplier->tipesupplier}}">

            <label>Deskripsi Tipe supplier</label>
            <textarea class="form-control" rows="4" name="deskripsi_tipesupplier" placeholder="Masukkan Deskripsi Tipe supplier">{{ $tipesupplier->deskripsi_tipesupplier }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/tipesupplier/{{ $tipesupplier->id }}" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection