@extends('layouts.vuexy')

@section('header')
Create Category Supplier ( Tambah Data Supplier )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/tipesupplier" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Tipe Supplier<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="tipesupplier" >

            <label>Deskripsi Tipe Supplier</label>
            <textarea class="form-control" rows="4" name="deskripsi_tipesupplier" ></textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/tipesupplier" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection