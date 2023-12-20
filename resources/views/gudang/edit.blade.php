@extends('layouts.vuexy')

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/gudang/{{ $gudang->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>Kode Gudang<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_gudang" placeholder="Masukkan Kode Gudang" value="{{ $gudang->kode_gudang }}">
            <label>Nama Gudang<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_gudang" placeholder="Masukkan Nama Gudang" value="{{ $gudang->nama_gudang }}">
            <label>Deskripsi Gudang</label>
            <textarea class="form-control" rows="4" name="deskripsi_gudang" placeholder="Masukkan Deskripsi Gudang" >{{ $gudang->deskripsi_gudang }}</textarea>
            <label>Nama Penanggung Jawab Gudang</label>
            <input type="text" class="form-control" name="nama_penanggungjawab" placeholder="Masukkan Nama Pennggung Jawab Gudang" value="{{ $gudang->nama_penanggungjawab}}">
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/gudang/{{ $gudang->id }}" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection