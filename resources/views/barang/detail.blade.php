@extends('layouts.vuexy')

@section('header')
Detail Item ( Detail Barang )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/barang">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>
<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 300px;">Nama Barang</td>
                    <td>{{ $barang->nama_barang }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Nama Kategori Barang</td>
                    <td>{{ $barang->kategoribarang->nama_kategori }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Tipe Barang</td>
                    <td>@if($barang->type == 1) Produk @elseif($barang->type == 2) Asset @elseif($barang->type == 3) Jasa @else Lainnya @endif</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kode Barang</td>
                    <td>{{ $barang->kode_barang }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Deskirpsi Barang</td>
                    <td>{{ $barang->deskripsi_barang }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Satuan Barang</td>
                    <td>{{ $barang->satuan_barang }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Harga Barang 1</td>
                    <!-- <td>{{ $barang->harga_barang1 }}</td> -->
                    <td>@currency($barang->harga_barang1)</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Harga Barang 2</td>
                    <!-- <td>{{ $barang->harga_barang2 }}</td> -->
                    <td>@currency($barang->harga_barang2)</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Harga Barang 3</td>
                    <!-- <td>{{ $barang->harga_barang3 }}</td> -->
                    <td>@currency($barang->harga_barang3)</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Harga Barang 4</td>
                    <!-- <td>{{ $barang->harga_barang4 }}</td> -->
                    <td>@currency($barang->harga_barang4)</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Harga Barang 5</td>
                    <!-- <td>{{ $barang->harga_barang5 }}</td> -->
                    <td>@currency($barang->harga_barang5)</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/barang/{{ $barang->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/barang/{{$barang->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>

@endsection