@extends('layouts.vuexy')

@section('header')
Create Supplier ( Tambah Data Pemasok )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/supplier" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Tipe Supplier<span class="text-danger"><i>*</i></span></label>
            <select class="form-control" name="tipesupplier_id" required>
                <option value="">-- PILIH TIPE SUPPLIER --</option>
                @foreach($tipesupplier as $tipesupplier)
                    <option value="{{ $tipesupplier->id }}">{{ $tipesupplier->tipesupplier }}</option>
                @endforeach
            </select>
            <label>Kode Supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" value="{{ old('kode_supplier') }}" name="kode_supplier" placeholder="Masukkan Kode Supplier">
            <label>Nama Supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" value="{{ old('nama_supplier') }}" name="nama_supplier" placeholder="Masukkan Supplier">
            <label>Handphone Supplier</label>
            <input type="text" class="form-control" value="{{ old('handphone_supplier') }}" name="handphone_supplier" placeholder="Masukkan No. Handphone Supplier">
            <label>Email Supplier</label>
            <input type="text" class="form-control" value="{{ old('email_supplier') }}" name="email_supplier" placeholder="Masukkan Email Supplier">
            <label>PIC Supplier<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" value="{{ old('pic_supplier') }}" name="pic_supplier" placeholder="Masukkan Nama PIC Supplier">
            <label for="">Nomer rekening</label>
            <input type="text" class="form-control" value="{{ old('nomer_rekening') }}" name="rekening_supplier" placeholder="Masukkan Nomer Rekening">
            <label>Negara</label>
            <input type="text" class="form-control" value="{{ old('negara') }}" name="negara" placeholder="Masukkan Negara">
            <label>Provinsi</label>
            <input type="text" class="form-control" value="{{ old('provinsi') }}" name="provinsi" placeholder="Masukkan Provinsi Supplier">
            <label>Kota</label>
            <input type="text" class="form-control" value="{{ old('kota') }}" name="kota" placeholder="Masukkan Kota Supplier">
            <label>Kecamatan</label>
            <input type="text" class="form-control" value="{{ old('kecamatan') }}" name="kecamatan" placeholder="Masukkan Kecamatan Supplier">
            <label>Detail Alamat<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" value="{{ old('detail_alamat') }}" name="detail_alamat" placeholder="Masukkan Detail Alamat Supplier">
            <label>Deskripsi Supplier</label>
            <textarea class="form-control" rows="4" name="deskripsi_supplier" placeholder="Masukkan Deskripsi Supplier">{{ old('deskripsi_supplier') }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/supplier" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection