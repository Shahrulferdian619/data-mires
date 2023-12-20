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

<a href="{{ route('master-data.supplier.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('master-data.supplier.update', $data['supplier']->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Supplier -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Supplier
            </h5>
        </div>

        <div class="card-body">
            <label for="">Kategori supplier</label>
            <select name="tipe_supplier_id" class="form-control" required>
                <option value="{{ $data['supplier']->tipe_supplier_id }}">{{ $data['supplier']->tipe_supplier->tipe }}</option>
                @foreach($data['tipeSupplier'] as $tipeSupplier)
                <option value="{{ $tipeSupplier->id }}">{{ $tipeSupplier->tipe }}</option>
                @endforeach
            </select>

            <label for="">Kode*</label>
            <input type="text" name="kode" class="form-control" value="{{ $data['supplier']->kode }}" required>

            <label for="">Nama*</label>
            <input type="text" name="nama" class="form-control" value="{{ $data['supplier']->nama }}" required>

            <label for="">Nama PIC</label>
            <input type="text" name="nama_pic" class="form-control">

            <label for="">No. rekening</label>
            <input type="text" name="nomer_rekening" class="form-control" value="{{ $data['supplier']->nomer_rekening }}">

            <label for="">No. telp</label>
            <input type="text" name="no_telp" class="form-control" value="{{ $data['supplier']->no_telp }}">

            <label for="">Provinsi</label>
            <input type="text" name="provinsi" class="form-control" value="{{ $data['supplier']->provinsi }}">

            <label for="">Kota</label>
            <input type="text" name="kota" class="form-control" value="{{ $data['supplier']->kota }}">

            <label for="">Detil alamat</label>
            <textarea name="detil_alamat" cols="30" rows="5" class="form-control">{{ $data['supplier']->detil_alamat }}</textarea>

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control">{{ $data['supplier']->keterangan }}</textarea>
        </div>
    </div>
    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('master-data.supplier.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection