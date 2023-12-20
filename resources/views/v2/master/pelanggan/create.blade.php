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

<a href="{{ route('master-data.pelanggan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('master-data.pelanggan.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('post')
    <!-- Data Pelanggan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pelanggan
            </h5>
        </div>

        <div class="card-body">
            {{-- <label for="">Kode*</label>
            <input type="text" name="kode_pelanggan" class="form-control bg-light" required readonly> --}}

            <label for="">Nama*</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}">

            <label for="">No. HP</label>
            <input type="text" name="no_handphone" class="form-control" value="{{ old('no_handphone') }}">

            <label for="">Provinsi</label>
            <select name="provinsi" class="form-control">
                <option value="">Pilih provinsi</option>
            </select>
            
            <label for="" style="display: none;" id="labelKota">Kota</label>
            <select name="kota" class="form-control" style="display: none;">
                <option value="">Pilih kota</option>
            </select>

            <label for="">Detil alamat</label>
            <textarea name="detil_alamat" cols="30" rows="5" class="form-control">{{ old('detil_alamat') }}</textarea>

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control">{{ old('keterangan') }}</textarea>
        </div>
    </div>
    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('master-data.pelanggan.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

<script type="text/javascript">
    const selectProvinsi = document.querySelector('select[name="provinsi"]');
    const selectKota = document.querySelector('select[name="kota"]');
    const labelKota = document.querySelector('#labelKota');

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
        .then(response => response.json())
        .then(provinces => {
            provinces.forEach(provinsi => {
                const option = document.createElement('option');
                option.value = `${provinsi.id}-${provinsi.name}`;
                option.textContent = provinsi.name;
                selectProvinsi.appendChild(option);
            });
        });

    selectProvinsi.addEventListener('change', () => {
        const selectedProvinsi = selectProvinsi.value.split('-');
        const selectedProvinsiId = selectedProvinsi[0];
        
        // Kosongkan pilihan kota sebelum melakukan fetch data kota baru
        selectKota.innerHTML = '';
        
        // Fetch data kota sesuai dengan ID provinsi yang dipilih
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${selectedProvinsiId}.json`)
            .then(response => response.json())
            .then(regencies => {
                regencies.forEach(kota => {
                    const option = document.createElement('option');
                    option.value = `${kota.id}-${kota.name}`;
                    option.textContent = kota.name;
                    selectKota.appendChild(option);
                });
                // Tampilkan elemen select kota setelah data kota selesai dimuat
                selectKota.style.display = 'block';
                labelKota.style.display = 'block';
            });
    });
</script>

@endsection