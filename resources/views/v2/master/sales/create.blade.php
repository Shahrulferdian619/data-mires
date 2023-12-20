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

<a href="{{ route('master-data.sales.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('master-data.sales.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Data Sales -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Sales
            </h5>
        </div>

        <div class="card-body">
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
    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('master-data.sales.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@section('custom_js')
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