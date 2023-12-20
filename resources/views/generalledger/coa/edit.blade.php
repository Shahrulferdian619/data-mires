@extends('layouts.vuexy')

@section('header')
List Account (COA) (Daftar Akun) Ubah
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="{{ route('admin.coa.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{ route('admin.coa.update', ['coa' => $coa->id]) }}" method="POST">
<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <span><b><span class="text-danger">*</span> Wajib Di isi</b></span>
                <br>
                @csrf
                @method('PUT')
                <label>Tipe Akun  <span class="text-danger">*</span></label>
                <select class="form-control" name="id_coatype" required>
                    <option value="">-- PILIH TIPE AKUN --</option>
                    @foreach($tipeCoa as $row)
                        <option value="{{ $row->id }}" {{ $row->id == $coa->id_coatype ? "selected" : "" }}>{{ $row->tipecoa }}</option>
                    @endforeach
                </select>
               
                <label>Nomer Akun <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nomer_coa" value="{{ $coa->nomer_coa }}" required>
               
                <label>Nama Akun <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama_coa" value="{{ $coa->nama_coa }}" required>

                <label>Saldo Awal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="saldo_awal" id="saldo_awal" value="{{ $coa->saldo_awal }}" required>

            
                <label>Keterangan</label>
                <textarea class="form-control" rows="4" name="keterangan">{{ $coa->keterangan }}</textarea>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-outline-primary" type="submit">Simpan</button>
                <a href="{{ route('admin.coa.index') }}" class="btn btn-outline-danger">Batal</a>
            </div>
        </div>
    </div>
</div>
   
</form>
@endsection

@section('myjs')
<script type="text/javascript">

    //format rupiah
    var saldo_awal = document.getElementById('saldo_awal');
    saldo_awal.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatsaldo_awal() untuk mengubah angka yang di ketik menjadi format angka
        saldo_awal.value = formatRupiah(this.value, 'Rp. ');
    });

    var saldo_akhir = document.getElementById('saldo_akhir');
    saldo_akhir.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatsaldo_akhir() untuk mengubah angka yang di ketik menjadi format angka
        saldo_akhir.value = formatRupiah(this.value, 'Rp. ');
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