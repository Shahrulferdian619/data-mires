@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')

@if($errors->any())
@include('v2.component.error')
@endif

@if(Session::has('sukses'))
@include('v2.component.sukses') 
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<a href="{{ route('master-data.gudang.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('master-data.gudang.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('post')
    <!-- Data Pelanggan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Gudang
            </h5>
        </div>

        <div class="card-body">
            <label for="">Nama Gudang*</label>
            <input type="text" name="nama_gudang" class="form-control"  value="{{ old('nama_gudang') }}">

            <label for="">PIC</label>
            <input type="text" name="pic_gudang" class="form-control" value="{{ old('pic_gudang') }}">
            
            <label for="">Alamat Gudang</label>
            <input type="text" name="alamat_gudang" class="form-control" value="{{ old('alamat_gudang') }}">

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control">{{ old('keterangan') }}</textarea>
        </div>
    </div>
    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('master-data.gudang.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection