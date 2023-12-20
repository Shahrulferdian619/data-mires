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

<form action="{{route('master-data.sales.update', $data->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')
    <!-- Data Sales -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Sales
            </h5>
        </div>

        <div class="card-body">
            <label>Nama Sales / Distributor<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_sales" value="{{ $data->nama_sales }}" placeholder="Masukkan Nama Sales">

            <label>Kode Area </label>
            <input type="text" class="form-control" name="kode_area" value="{{ $data->kode_area }}" placeholder="Masukkan Kode Area: (JTM01)">
            
            <label>Target Total Invoice</label>
            <input type="text" id="target-total" class="form-control" name="target_total_invoice" value="{{ $data->target_total_invoice }}" placeholder="Masukkan Target Total Invoice">
            
            <label>Bonus Presentase</label>
            <input type="text" class="form-control" name="bonus_presentase" value="{{ $data->bonus_presentase }}" placeholder="Masukkan Bonus Presentase">
            
            <label>Keterangan Tambahan</label>
            <!-- <textarea class="form-control" rows="4" name="keterangan_tambahan"  placeholder="Masukkan Deskripsi Tambahan">{{ old('keterangan_tambahan') }}</textarea> -->
            <textarea class="form-control" rows="4" name="keterangan"  placeholder="Masukkan Deskripsi Tambahan">{{ $data->keterangan }}</textarea>
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

    document.querySelector('#target-total').value = 'Rp. ' + formatRupiah(document.querySelector('#target-total').value.toString())

    document.querySelector('#target-total').addEventListener('keyup', function(e){
        let expTarget = removeRp(this.value);
        this.value = 'Rp. ' + formatRupiah(expTarget);
    })

    function removeRp(input){
        var removeRp = input.replace('Rp. ', '');
        var val = removeRp.replace('.', '');

        return val;
    }

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