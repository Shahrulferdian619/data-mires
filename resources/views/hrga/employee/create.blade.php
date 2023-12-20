@extends('layouts.vuexy')

@section('header')
Create Employee ( Tambah Data Karyawan )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif
<style>
    .card-body .profile_img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin: 2px auto;
    border: 2px solid #ccc;
    border-radius: 50%;
}
</style>

<a href="/admin/employee">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/employee" method="POST" enctype="multipart/form-data">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>N.I.K <span class="text-danger"><i>*</i></span></label>
            <input type="number" class="form-control" name="nik" value="{{ old('nik') }}" >
            
            <label>Nama Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_karyawan" value="{{ old('nama_karyawan') }}" >
            
            <label>Tanggal Lahir Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="tanggal_lahir_karyawan" value="{{ old('tanggal_lahir_karyawan') }}">
            
            <label>Nomer HP Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="number" class="form-control" name="nomer_hp_karyawan" value="{{ old('nomer_hp_karyawan') }}" >
            <!-- onkeypress="return NoHP(event)"  -->
            <label>Email Karyawan</label>
            <input type="text" class="form-control" name="email_karyawan" value="{{ old('email_karyawan') }}" id="email" >
            <!-- oninput="this.value=this.value.toLowerCase()" -->
            <label>Alamat Karyawan <span class="text-danger"><i>*</i></span></label>
            <textarea class="form-control" rows="4" name="alamat_karyawan" value="{{ old('alamat_karyawan') }}" ></textarea>
            
            <label>Jabatan Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="jabatan_karyawan" value="{{ old('jabatan_karyawan') }}" >
            
            <label>Divisi Karyawan <span class="text-danger"><i>*</i></span></label></label>
            <input type="text" class="form-control" name="divisi_karyawan" value="{{ old('divisi_karyawan') }}" >
            
            <label>Tanggal Masuk Kerja <span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="tanggal_masuk_kerja" value="{{ date('Y-m-d') }}">
            
            <label>Tanggal Keluar Kerja</label>
            <input type="date" class="form-control" name="tanggal_keluar_kerja" value="{{ old('tanggal_keluar_kerja') }}">
            
            <label>Masa Kontrak<span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="masa_kontrak" value="{{ date('Y-m-d') }}">

            <label>Gaji Karyawan </label>
            <input type="text" class="form-control" name="gaji_karyawan" id="gaji_karyawan" value="{{ old('gaji_karyawan') }}">
            
            <label>Keterangan Tambahan</label>
            <textarea class="form-control" rows="4" name="keterangan_tambahan"  >{{ old('keterangan_tambahan') }}</textarea>

            <img class="m-2 shadow profile_img" id="previewpicture"><br>
            <label for="">Foto Karyawan</label>
            <input required onchange="previewFile(this);" type="file" class="form-control" name="picture">
            <small>File Harus Gambar, Rasio 1:1, Rekomendasi Ukuran 500 x 500 px</small>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/employee" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">

    //format rupiah
    var gaji_karyawan = document.getElementById('gaji_karyawan');
    gaji_karyawan.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatgaji_karyawan() untuk mengubah angka yang di ketik menjadi format angka
        gaji_karyawan.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi NoHP */
    function NoHP(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
    
        return false;
        return true;
    }

    $(document).ready(function(){
        $("#email").on('input', function(){
            $(this).val( $(this).val().toLowerCase() );
        })
    });


    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    

    function previewFile(input){
        var file = $("input[name=picture]").get(0).files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                $("#previewpicture").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }

</script>
@endsection
