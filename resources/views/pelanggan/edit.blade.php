@extends('layouts.vuexy')

@section('header')
Edit Customer ( Ubah Data Pelanggan )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<form action="/admin/pelanggan/{{ $pelanggan->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <select class="form-control" name="tipepelanggan_id" required>
                <option value="">-- PILIH TIPE pelanggan --</option>
                @foreach($tipepelanggan as $tipepelanggan)
                    <option value="{{ $tipepelanggan->id }}" {{ $pelanggan->tipepelanggan->id === $tipepelanggan->id ? 'selected' : '' }}>{{ $tipepelanggan->tipepelanggan }}</option>
                @endforeach
            </select>
            <label>Kode Pelanggan<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_pelanggan" placeholder="Masukkan Kode pelanggan : (CUST01)" value="{{ $pelanggan->kode_pelanggan }}">
            
            <label>Kode Area<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_area" placeholder="Masukkan Kode area : (JTM01)" value="{{ $pelanggan->kode_area }}">
            <label>Nama Pelanggan<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_pelanggan" placeholder="Masukkan pelanggan" value="{{ $pelanggan->nama_pelanggan }}">
            <label>Handphone Pelanggan</label>
            <input type="text" class="form-control" name="handphone_pelanggan" placeholder="Masukkan No. Handphone pelanggan" value="{{ $pelanggan->handphone_pelanggan }}">
            <label>Email Pelanggan</label>
            <input type="text" class="form-control" name="email_pelanggan" placeholder="Masukkan Email pelanggan" value="{{ $pelanggan->email_pelanggan }}">
            <label>Negara</label>
            <input type="text" class="form-control" name="negara" placeholder="Masukkan Negara" value="{{ $pelanggan->negara }}">
            <label>Provinsi</label>
            <select required name="provinsi" id="select_provinsi" class="form-control">
                @foreach($provinces as $province)
                <option @if($pelanggan->provinsi == $province->name) selected @endif value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
            <label>Kota</label>
            <select required name="kota" class="form-control" id="select_city">
                <option value="{{ $pelanggan->kota }}">{{ $pelanggan->kota }}</option>
            </select><label>Kecamatan</label>
            <input type="text" class="form-control" name="kecamatan" placeholder="Masukkan Kecamatan pelanggan" value="{{ $pelanggan->kecamatan }}">
            <label>Detail Alamat<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="detail_alamat" placeholder="Masukkan Detail Alamat pelanggan" value="{{ $pelanggan->detail_alamat }}">
            <label>Deskripsi Pelanggan</label>
            <textarea class="form-control" rows="4" name="deskripsi_pelanggan" placeholder="Masukkan Deskripsi pelanggan">{{ $pelanggan->deskripsi_pelanggan }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/pelanggan/{{ $pelanggan->id }}" class="btn btn-outline-secondary">Batal</a>
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