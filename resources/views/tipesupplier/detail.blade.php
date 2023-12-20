@extends('layouts.vuexy')

@section('header')
Detail Category Supplier ( Detail Kategori Supplier )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/tipesupplier">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 270px;">Tipe Supplier</td>
                    <td>{{ $tipesupplier->tipesupplier }}</td>
                </tr>
                <tr>
                    <td style="width: 200px;">Deskirpsi Tipe Supplier</td>
                    <td>{{ $tipesupplier->deskripsi_tipesupplier }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/tipesupplier/{{ $tipesupplier->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/tipesupplier/{{$tipesupplier->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>

@endsection