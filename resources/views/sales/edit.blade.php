@extends('layouts.vuexy')

@section('header')
Edit Sales ( Ubah Data Sales )
@endsection


@section('content')

@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/sales">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/sales/{{ $sales->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')
            <label>Nama Sales <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_sales" value="{{ $sales->nama_sales }}" placeholder="Masukkan Nama Sales">
            
            <label>Kode Area </label>
            <input type="text" class="form-control" name="kode_area" value="{{ $sales->kode_area }}" placeholder="Masukkan Kode Area: (JTM01)">
            
            <label>Target Total Invoice</label>
            <input type="text" class="form-control" id="target-total"    name="target_total_invoice" value="{{ $sales->target_total_invoice }}" placeholder="Masukkan Target Total Invoice">
            
            <label>Bonus Presentase</label>
            <input type="text" class="form-control" name="bonus_presentase" value="{{ $sales->bonus_presentase }}" placeholder="Masukkan Bonus Presentase">
            
            <label>Keterangan Tambahan</label>
            <textarea class="form-control" rows="4" name="keterangan"  placeholder="Masukkan Deskripsi">{{ $sales->keterangan }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/sales" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
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