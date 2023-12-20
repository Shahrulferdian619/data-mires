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

<form action="{{route('master-data.pelanggan.update', $data['pelanggan']->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Pelanggan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pelanggan
            </h5>
        </div>

        <div class="card-body">
            <p class="mb-0">Status</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status_aktif" id="status" value="1" {{ ($data['pelanggan']->status_aktif == '1') ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Aktif</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status_aktif" id="status" value="0" {{ ($data['pelanggan']->status_aktif == '0') ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Tidak Aktif</label>
            </div>
            <br>
            <label for="">Kode*</label>
            <input type="text" name="kode_pelanggan" class="form-control bg-light" value="{{ $data['pelanggan']->kode_pelanggan }}" required readonly>

            <label for="">Nama*</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ $data['pelanggan']->nama_pelanggan }}" required>

            <label for="">No. HP</label>
            <input type="text" name="no_handphone" class="form-control" value="{{ $data['pelanggan']->no_handphone }}">

            <label for="">Provinsi</label>
            <select name="provinsi" class="form-control">
                <option value="{{ $data['pelanggan']->provinsi }}" selected>{{ $data['pelanggan']->provinsi }}</option>
            </select>

            <label for="">Kota</label>
            <input type="text" name="kota" class="form-control bg-light inputKota" value="{{ $data['pelanggan']->kota }}" required readonly>
            <select name="kota" class="form-control selectKota" disabled style="display: none">
                <option value="{{ $data['pelanggan']->kota }}" selected>{{ $data['pelanggan']->kota }}</option>
            </select>

            <label for="">Detil alamat</label>
            <textarea name="detil_alamat" cols="30" rows="5" class="form-control">{{ $data['pelanggan']->detil_alamat }}</textarea>

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control">{{ $data['pelanggan']->keterangan }}</textarea>
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
    const inputKota = document.querySelector('.inputKota');
    const selectKota = document.querySelector('.selectKota');

    // Simpan nilai provinsi dari database ke dalam variabel
    const selectedProvinsiValue = "{{ $data['pelanggan']->provinsi }}";

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
        .then(response => response.json())
        .then(provinces => {
            provinces.forEach(provinsi => {
                const option = document.createElement('option');
                option.value = `${provinsi.id}-${provinsi.name}`;
                option.textContent = provinsi.name;
                selectProvinsi.appendChild(option);
            });
            // Set opsi provinsi yang dipilih berdasarkan nilai dari database
            selectProvinsi.value = selectedProvinsiValue;

            // Sembunyikan opsi dengan value yang sama dengan nilai dari database
            hideSelectedOption(selectedProvinsiValue);
        });

    selectProvinsi.addEventListener('change', () => {
        selectKota.removeAttribute('disabled');
        selectKota.style.display = 'block';

        inputKota.removeAttribute('readonly');
        inputKota.style.display = 'none';        


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
            });
            // Sembunyikan opsi dengan value yang sama dengan nilai dari database
            hideSelectedOption(selectedProvinsiValue);
    });

    function hideSelectedOption(selectedValue) {
        const provinsiOptions = selectProvinsi.querySelectorAll('option');
        provinsiOptions.forEach(option => {
            if (option.value === selectedValue) {
                option.style.display = 'none';
            } else {
                option.style.display = 'block';
            }
        });
    }
</script>

@endsection