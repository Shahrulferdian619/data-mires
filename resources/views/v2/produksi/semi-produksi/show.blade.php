@extends('v2.layout.vuexy')

@section('custom_style')

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

<a href="{{ route('produksi.semi-index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<!-- Data semi produksi -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr class="table-info">
                        <td colspan="2">DATA SEMI PRODUKSI</td> 
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 300px;">Nomer Produksi</td>
                        <td>: {{$data->nomer_produksi}}</td>
                    </tr>
                        <td style="width: 300px;">Tanggal</td>
                        <td>: {{date('d/m/Y',strtotime($data->tanggal_produksi))}}</td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Keterangan</td>
                        <td>: {{$data->keterangan}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>

<!-- Data Rincian -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Rincian Item
        </h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr class="table-info">
                        <th>item</th>
                        <th>kuantitas</th>
                        <th>catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->semiProduksiRinci as $item)
                        {{-- @dump($item->barang) --}}
                        <tr>
                            <td>{{ $item->barang->nama_barang }}</td>
                            <td>{{ $item->kuantitas }}</td>
                            <td>{{ $item->catatan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>

<!-- Produk Jasa -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Produk
        </h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr class="table-info">
                        <th>produk jasa</th>
                        <th>kuantitas</th>
                        <th>catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data->barang->nama_barang }}</td>
                        <td>{{ $data->kuantitas }}</td>
                        <td>{{ $data->catatan }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>

<!-- Button Submit -->
<div class="card">
    <div class="card-footer">
        <a href="{{ route('produksi.semi-edit', $data->id) }}" class="btn btn-outline-warning">Ubah</a>
    </div>
</div>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
@endsection