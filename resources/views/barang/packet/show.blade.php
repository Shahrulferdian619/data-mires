@extends('layouts.vuexy')

@section('header')
Detail Item ( Detail Barang )
@endsection

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif
<a href="/admin/packet">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>
<div class="card">
    <div class="card-body">
        <table class=" table table-borderless">
            <tbody>
                <tr>
                    <th>NAMA PAKET</th>
                    <td>{{ $packet->packet_name }}</td>
                </tr>
                <tr>
                    <th>TOTAL</th>
                    <td>Rp. {{ number_format($packet->total) }}</td>
                </tr>
                <tr>
                    <th>KETERANGAN</th>
                    <td>{{ $packet->note }}</td>
                </tr>
            </tbody>
        </table>
        <table class="mt-1 table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packet_rinci as $rinci)
                <tr>
                    <td>{{ $rinci->barang->nama_barang }}</td>
                    <td>{{ $rinci->qty }}</td>
                    <td>Rp.{{ number_format($rinci->harga) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    
    <form action="/admin/packet/{{ $packet->id }}" method="POST">
    <div class="card-body">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
    </div>
    </form>
</div>

@endsection