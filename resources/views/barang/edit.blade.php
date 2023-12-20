@extends('layouts.vuexy')

@section('header')
Edit Item ( Ubah Data Barang )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/barang">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/barang/{{ $barang->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <select class="form-control" name="kategoribarang_id" required>
                <option value="">-- PILIH KATEGORI BARANG --</option>
                @foreach($kategoribarang as $kategoribarang)
                    <option value="{{ $kategoribarang->id }}" {{ $barang->kategoribarang->id === $kategoribarang->id ? 'selected' : '' }}>{{ $kategoribarang->nama_kategori }}</option>
                @endforeach
            </select>
            <label>Tipe Barang <span class="text-danger"><i>*</i></span></label>
            <select class="form-control" name="type" required>
                <option value="1" {{ $barang->type === 1 ? 'selected' : '' }}>Produk</option>
                <option value="2" {{ $barang->type === 2 ? 'selected' : '' }}>Asset</option>
                <option value="3" {{ $barang->type === 3 ? 'selected' : '' }}>Jasa</option>
                <option value="4" {{ $barang->type === 4 ? 'selected' : '' }}>Lainnya</option>
            </select>
            <label>Kode Barang<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="kode_barang" placeholder="Masukkan Kode Barang" value="{{ $barang->kode_barang }}">
            <label>Nama Barang<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_barang" placeholder="Masukkan Nama Barang" value="{{ $barang->nama_barang }}">
            <label>Deskripsi Barang</label>
            <textarea class="form-control" rows="4" name="deskripsi_barang" placeholder="Masukkan Deskripsi Barang">{{$barang->deskripsi_barang}}</textarea>
            <label>Satuan Barang</label>
            <input type="text" class="form-control" name="satuan_barang" placeholder="Masukkan Satuan Barang" value="{{ $barang->satuan_barang}}">
            <label>Harga Barang 1</label>
            <input type="text" class="form-control" name="harga_barang1" id="harga_barang1" placeholder="Masukkan Harga Barang 1" value="{{$barang->harga_barang1}}">
            <label>Harga Barang 2</label>
            <input type="text" class="form-control" name="harga_barang2" id="harga_barang2" placeholder="Masukkan Harga Barang 2" value="{{$barang->harga_barang2}}">
            <label>Harga Barang 3</label>
            <input type="text" class="form-control" name="harga_barang3" id="harga_barang3" placeholder="Masukkan Harga Barang 3" value="{{$barang->harga_barang3}}">
            <label>Harga Barang 4</label>
            <input type="text" class="form-control" name="harga_barang4" id="harga_barang4" placeholder="Masukkan Harga Barang 4" value="{{$barang->harga_barang4}}">
            <label>Harga Barang 5</label>
            <input type="text" class="form-control" name="harga_barang5" id="harga_barang5" placeholder="Masukkan Harga Barang 5" value="{{$barang->harga_barang5}}">
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/barang/{{ $barang->id }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection
