@extends('layouts.vuexy')

@section('header')
Create Sales ( Tambah Data Sales )
@endsection


@section('content')

@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/sales">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/sales" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body"> 
            @csrf
            <label>Nama Sales / Distributor<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_sales" value="{{ old('nama_sales') }}" placeholder="Masukkan Nama Sales">

            <label>Kode Area </label>
            <input type="text" class="form-control" name="kode_area" value="{{ old('kode_area') }}" placeholder="Masukkan Kode Area: (JTM01)">
            
            <label>Target Total Invoice</label>
            <input type="text" id="target-total" class="form-control" name="target_total_invoice" value="{{ old('target_total_invoice') }}" placeholder="Masukkan Target Total Invoice">
            
            <label>Bonus Presentase</label>
            <input type="text" class="form-control" name="bonus_presentase" value="{{ old('bonus_presentase') }}" placeholder="Masukkan Bonus Presentase">
            
            <label>Keterangan Tambahan</label>
            <!-- <textarea class="form-control" rows="4" name="keterangan_tambahan"  placeholder="Masukkan Deskripsi Tambahan">{{ old('keterangan_tambahan') }}</textarea> -->
            <textarea class="form-control" rows="4" name="keterangan"  placeholder="Masukkan Deskripsi Tambahan">{{ old('keterangan') }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" name="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/sales" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
    <script>

        document.querySelector('#target-total').addEventListener('keyup', function(e){
            this.value = 'Rp. ' + formatRupiah(this.value.toString());
        })

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