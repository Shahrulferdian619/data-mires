@extends('layouts.vuexy')

@section('header')
{{ $barang->nama_barang }} ({{ $barang->kode_barang }}) <br>
<small><i>{{ $barang->kategoribarang->nama_kategori }}</i></small>
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif

<div class="card">
    <div class="card-body">
       <h3>Detail Barang</h3><br>
       <table class="table">
        <tr>
            <th>Kode Barang</th>
            <td>{{ $barang->kode_barang }}</td>
        </tr>
        <tr>
            <th>Nama Item</th>
            <td>{{ $barang->nama_barang }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $barang->kategoribarang->nama_kategori }}</td>
        </tr>
        <tr>
            <th>Satuan</th>
            <td>{{ $barang->satuan_barang }}</td>
        </tr>
        <tr>
            <th>Stok</th>
            <td>{{ $barang->balance_stok }}</td>
        </tr>
       </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <h3>Lokasi & Jumlah Item</h3>
            <table class="table-barang table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Lokasi Barang</th>
                        <th>Jumlah</th>
                        <th>Adjusment(Opname)</th>
                    </tr>
                </thead>
               <tbody>
                   @foreach($lokasi_gudang as $item_on_gudang)
                   <tr>
                       <td>{{ $item_on_gudang->gudang->nama_gudang }}</td>
                       <td>{{ $item_on_gudang->qty }}</td>
                       <td>{{ $item_on_gudang->stock_opname }}</td>
                   </tr>
                   @endforeach
               </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <h3>Histori Transaksi</h3>
            <table class="table-barang table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nomer Transaksi</th>
                        <th>Jenis Transaksi</th>
                        <th>Sumber Transaksi</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $transaksi)
                    <tr>
                        <td>{{ $transaksi->nomer_transaksi }}</td>
                        <td>{{ $transaksi->jenis_transaksi }}</td>
                        <td>{{ $transaksi->sumber_transaksi }}</td>
                        <td>{{ $transaksi->qty }}</td>
                        <td>{{ $transaksi->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <a href="{{ url('admin/list-inventory/all') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-barang').DataTable()
    })
</script>
@endsection