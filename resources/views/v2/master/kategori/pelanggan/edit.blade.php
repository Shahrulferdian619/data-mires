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

<a href="{{ route('master-kategori.pelanggan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{ route('master-kategori.pelanggan.update', $data['kategori_pelanggan']->id) }}" method="post">
    @method('patch')
    @csrf
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Kategori Pelanggan
            </h5>
        </div>

        <div class="card-body">
            <p class="mb-0">Status</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status_aktif" id="status" value="1" {{ ($data['kategori_pelanggan']->status_aktif == '1') ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Aktif</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status_aktif" id="status" value="0" {{ ($data['kategori_pelanggan']->status_aktif == '0') ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Tidak Aktif</label>
            </div>
            <br>
            <label for="">Kategori Pelanggan*</label>
            <input type="text" name="kategori_pelanggan" class="form-control" value="{{ $data['kategori_pelanggan']->kategori_pelanggan }}" required>
        </div>
    </div>

    <br>                                                                        

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('master-kategori.pelanggan.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>
@endsection