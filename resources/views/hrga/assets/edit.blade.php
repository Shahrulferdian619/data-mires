@extends('layouts.vuexy')

@section('header')
Edit Asset ( Ubah Data Asset )
@endsection


@section('content')

@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/asset">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="/admin/asset/{{ $asset->id }}" method="POST">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            @method('PUT')

            <label>Pilih Kategori Asset <span class="text-danger"><i>*</i></span></label>
            <select class="form-control" name="id_tipeasset" required>
                <option value="{{ $asset->id_tipeasset }}">{{ $asset->tipe->tipe_asset }}</option>
                @foreach($assettype as $assettype)
                    <option value="{{ $assettype->id }}">{{ $assettype->tipe_asset }}</option>
                @endforeach
            </select>

            <label>Masukkan Nama Asset <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_asset" placeholder="Masukkan Nama Asset" value="{{ $asset->nama_asset }}">

            <label>Tanggal Perolehan Asset <span class="text-danger"><i>*</i></span></label>
            <input type="date" class="form-control" name="tanggal_perolehan"  value="{{ $asset->tanggal_perolehan }}">

            <label>Masukkan Harga Perolehan Asset <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="harga_perolehan" id="harga_perolehan" value="{{ $asset->harga_perolehan }}">

            <label>Masukkan Kuantitas <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kuantitas" value="{{ $asset->kuantitas }}">

            <label>Keterangan</label>
            <textarea class="form-control" rows="4" name="Keterangan" >{{ $asset->keterangan }}</textarea>
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="/admin/asset" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">

    //format rupiah
    var harga_perolehan = document.getElementById('harga_perolehan');
    harga_perolehan.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatharga_perolehan() untuk mengubah angka yang di ketik menjadi format angka
        harga_perolehan.value = formatRupiah(this.value, 'Rp. ');
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