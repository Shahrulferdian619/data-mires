@extends('layouts.vuexy')

@section('header')
Edit Employees ( Ubah Data Karyawan )
@endsection


@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/employee">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/employee/{{ $employee->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>N.I.K <span class="text-danger"><i>*</i></span></label>
            <input type="number" class="form-control" name="nik" value="{{ $employee->nik }}">
            
            <label>Nama Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_karyawan" value="{{ $employee->nama_karyawan }}">
            
            <label>Tanggal Lahir Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="tanggal_lahir_karyawan" value="{{ $employee->tanggal_lahir_karyawan }}">
            
            <label>Nomer HP Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="number" class="form-control" name="nomer_hp_karyawan" value="{{ $employee->nomer_hp_karyawan }}">
            
            <label>Email Karyawan</label>
            <input type="text" id="email" class="form-control" name="email_karyawan" value="{{ $employee->email_karyawan }}">
            
            <label>Alamat Karyawan <span class="text-danger"><i>*</i></span></label>
            <textarea class="form-control" rows="4" name="alamat_karyawan" >{{ $employee->alamat_karyawan }}</textarea>
            
            <label>Jabatan Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="jabatan_karyawan" value="{{ $employee->jabatan_karyawan }}">
            
            <label>Divisi Karyawan <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="divisi_karyawan" value="{{ $employee->divisi_karyawan }}">
            
            <label>Tanggal Masuk Kerja <span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="tanggal_masuk_kerja" value="{{ $employee->tanggal_masuk_kerja }}">
            
            <label>Tanggal Keluar Kerja</label>
            <input type="date" class="form-control" name="tanggal_keluar_kerja" value="{{ $employee->tanggal_keluar_kerja }}">
            
            <label>Masa Kontrak<span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="masa_kontrak" value="{{ $employee->masa_kontrak }}">

            <label>Gaji Karyawan </label>
            <input type="text" class="form-control" name="gaji_karyawan" id="gaji_karyawan" value="{{ $employee->gaji_karyawan }}">
            
            <label>Keterangan Tambahan</label>
            <textarea class="form-control" rows="4" name="keterangan_tambahan" >{{ $employee->keterangan }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/employee" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function(){
        $("#email").on('input', function(){
            $(this).val( $(this).val().toLowerCase() );
        })
    });

    //format rupiah
    var gaji_karyawan = document.getElementById('gaji_karyawan');
    gaji_karyawan.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatgaji_karyawan() untuk mengubah angka yang di ketik menjadi format angka
        gaji_karyawan.value = formatRupiah(this.value, 'Rp. ');
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

</script>
@endsection
