@extends('layouts.vuexy')

@section('header')
Edit Supplier ( Ubah Data Supplier )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/supplier/{{ $supplier->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <select class="form-control" name="tipesupplier_id" required>
                <option value="">-- PILIH TIPE SUPPLIER --</option>
                @foreach($tipesupplier as $tipesupplier)
                    <option value="{{ $tipesupplier->id }}" {{ $supplier->tipesupplier->id === $tipesupplier->id ? 'selected' : '' }}>{{ $tipesupplier->tipesupplier }}</option>
                @endforeach
            </select>
            <label>Kode Supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_supplier" placeholder="Masukkan Kode Supplier" value="{{ $supplier->kode_supplier }}">
            <label>Nama Supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_supplier" placeholder="Masukkan Supplier" value="{{ $supplier->nama_supplier }}">
            <label>Handphone Supplier</label>
            <input type="text" class="form-control" name="handphone_supplier" placeholder="Masukkan No. Handphone Supplier" value="{{ $supplier->handphone_supplier }}">
            <label>PIC Supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="pic_supplier" placeholder="Masukkan PIC Supplier" value="{{ $supplier->pic }}">
            <label>Rekening Supplier</label>
            <input type="text" class="form-control" name="rekening_supplier" placeholder="Masukkan Rekening Supplier" value="{{ $supplier->nomer_rekening }}">
            <label>Email Supplier</label>
            <input type="text" class="form-control" name="email_supplier" placeholder="Masukkan Email Supplier" value="{{ $supplier->email_supplier }}">
            <label>Negara</label>
            <input type="text" class="form-control" name="negara" placeholder="Masukkan Negara" value="{{ $supplier->negara }}">
            <label>Provinsi</label>
            <input type="text" class="form-control" name="provinsi" placeholder="Masukkan Provinsi Supplier" value="{{ $supplier->provinsi }}">
            <label>Kota</label>
            <input type="text" class="form-control" name="kota" placeholder="Masukkan Kota Supplier" value="{{ $supplier->kota }}">
            <label>Kecamatan</label>
            <input type="text" class="form-control" name="kecamatan" placeholder="Masukkan Kecamatan Supplier" value="{{ $supplier->kecamatan }}">
            <label>Detail Alamat<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="detail_alamat" placeholder="Masukkan Detail Alamat Supplier" value="{{ $supplier->detail_alamat }}">
            <label>Deskripsi Supplier</label>
            <textarea class="form-control" rows="4" name="deskripsi_supplier" placeholder="Masukkan Deskripsi Supplier">{{ $supplier->deskripsi_supplier }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/supplier/{{ $supplier->id }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection