@extends('layouts.vuexy')

@section('header')
Detail Asset Category ( Detail Kategori Asset )
@endsection

@section('content')

<a href="/admin/tipeasset">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <label>Kategori Asset </label>
                <input type="text" class="form-control" readonly value="{{ $assettype->tipe_asset }}">
                
                <label>Keterangan Tambahan</label>
                <textarea class="form-control" rows="4" readonly> {{ $assettype->keterangan }} </textarea>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <form action="/admin/tipeasset/{{ $assettype->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/tipeasset/{{ $assettype->id }}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>
@endsection