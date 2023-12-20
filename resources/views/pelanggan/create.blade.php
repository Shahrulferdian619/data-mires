@extends('layouts.vuexy')

@section('header')
Create Customer ( Tambah Data Pelanggan )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/pelanggan" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <select class="form-control" name="tipepelanggan_id" required>
                <option value="">-- PILIH TIPE PELANGGAN --</option>
                @foreach($tipepelanggan as $tipepelanggan)
                    <option value="{{ $tipepelanggan->id }}">{{ $tipepelanggan->tipepelanggan }}</option>
                @endforeach
            </select>
            <label>Kode pelanggan<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_pelanggan" placeholder="Masukkan Kode pelanggan : (CUST001)">
            <label>Kode area</label>
            <input type="text" class="form-control" name="kode_area" placeholder="Masukkan Kode area : (JTM01)">
            <label>Nama pelanggan<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_pelanggan" placeholder="Masukkan pelanggan">
            <label>Handphone pelanggan</label>
            <input type="text" class="form-control" name="handphone_pelanggan" placeholder="Masukkan No. Handphone pelanggan">
            <label>Email pelanggan</label>
            <input type="text" class="form-control" name="email_pelanggan" placeholder="Masukkan Email pelanggan">
            <label>Negara</label>
            <input type="text" class="form-control" name="negara" value="INDONESIA" placeholder="Masukkan Negara">
            <label>Provinsi</label>
            <select required name="provinsi" id="select_provinsi" class="form-control">
                <option value="">-- Pilih Provinsi --</option>
                @foreach($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
            <label>Kota</label>
            <select required name="kota" class="form-control" id="select_city">

            </select>
            <label>Kecamatan</label>
            <input type="text" class="form-control" name="kecamatan" placeholder="Masukkan Kecamatan pelanggan">
            <label>Detail Alamat<span class="text-danger"><i>*</i></span></label>
            <textarea class="form-control" rows="4" name="detail_alamat" placeholder="Masukkan Detail Alamat pelanggan"></textarea>
            <label>Deskripsi pelanggan</label>
            <textarea class="form-control" rows="4" name="deskripsi_pelanggan" placeholder="Masukkan Deskripsi pelanggan"></textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/pelanggan" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
<script>
    $(document).ready(function() {

        $('#select_provinsi').on('change', function(el) {
            
            el.preventDefault()
            $.ajax({
                method: 'GET',
                url: '/admin/pelanggan/get-city/' + $('#select_provinsi').val(),
                dataType: 'JSON',
                success: function(data){
                    let html = ''
                    data.map((item, index) => {
                        html += `
                        <option value="${item.name}">${item.name}</option>
                        `
                    })
                    $('#select_city').html(html)
                }
            })
        })
    })
</script>
@endsection