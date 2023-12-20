@extends('layouts.vuexy')

@section('header')
Create Item ( Tambah Barang )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/barang">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/barang" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Kategori Barang <span class="text-danger"><i>*</i></span></label>
            <select class="form-control" name="kategoribarang_id" required>
                <option value="">-- PILIH KATEGORI BARANG --</option>
                @foreach($kategoribarang as $kategoribarang)
                    <option value="{{ $kategoribarang->id }}">{{ $kategoribarang->nama_kategori }}</option>
                @endforeach
            </select>
            <label>Tipe Barang <span class="text-danger"><i>*</i></span></label>
            <select class="form-control" name="type" required>
                <option value="1">Produk</option>
                <option value="2">Asset</option>
                <option value="3">Jasa</option>
                <option value="4">Lainnya</option>
            </select>
            <label>Kode Barang <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_barang" placeholder="Masukkan Kode Barang">
            <label>Nama Barang <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_barang" placeholder="Masukkan Nama Barang">
            <label>Deskripsi Barang</label>
            <textarea class="form-control" rows="4" name="deskripsi_barang" placeholder="Masukkan Deskripsi Barang"></textarea>
            <label>Satuan Barang</label>
            <input type="text" class="form-control" name="satuan_barang" placeholder="Masukkan Satuan Barang">
            <label>Harga Barang 1</label>
            <input type="text" class="form-control" name="harga_barang1" id="harga_barang1" placeholder="Masukkan Harga Barang 1">
            <label>Harga Barang 2</label>
            <input type="text" class="form-control" name="harga_barang2" id="harga_barang2" placeholder="Masukkan Harga Barang 2">
            <label>Harga Barang 3</label>
            <input type="text" class="form-control" name="harga_barang3" id="harga_barang3" placeholder="Masukkan Harga Barang 3">
            <label>Harga Barang 4</label>
            <input type="text" class="form-control" name="harga_barang4" id="harga_barang4" placeholder="Masukkan Harga Barang 4">
            <label>Harga Barang 5</label>
            <input type="text" class="form-control" name="harga_barang5" id="harga_barang5" placeholder="Masukkan Harga Barang 5">
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/barang" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>

@endsection



