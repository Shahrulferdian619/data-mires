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

<a href="{{ route('bukubesar.coa.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('bukubesar.coa.store')}}" method="post">
    @csrf
    <!-- Data Akun -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Akun (COA)
            </h5>
        </div>

        <div class="card-body">
            <label for="">Tipe COA</label>
            <select name="coa_tipe_id" class="form-control" required>
                <option value="">TIPE COA</option>
                @foreach($data['tipeCoa'] as $tipeCoa)
                <option value="{{ $tipeCoa->id }}">{{ $tipeCoa->coa_tipe }}</option>
                @endforeach
            </select>

            <label for="">Nomer</label>
            <input type="text" name="nomer_coa" class="form-control" required>

            <label for="">Nama</label>
            <input type="text" name="nama_coa" class="form-control" required>

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('bukubesar.coa.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection