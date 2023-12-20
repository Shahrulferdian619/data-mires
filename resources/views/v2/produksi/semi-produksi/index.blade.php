@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
    <ul>
        <li>Untuk melihat data lama silahkan kembali ke versi 1</li>
        <li>Fitur download excel masih belum bisa</li>
    </ul>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Permintaan Pembelian
            <a href="{{ route('produksi.semi-create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>

            <button class="btn btn-sm btn-outline-success btn-download-excel">
                <i class="fa fa-download"></i>
                Download Excel
            </button>
        </h5>
    </div>

    <div class="col-md-4">
        <table class="table table-bordered"> 
            <tr>
                <td colspan="2" style="background-color: #8bd6fc;">Keterangan warna tabel</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #ffa1b7;">Dibatalkan/tidak disetujui/ditutup</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #fcd88b;">Belum diproses</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #98fc8b;">Sudah diproses</td>
            </tr>
        </table>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 150px;">no.</th>
                    <th style="width: 150px;">tgl produksi.</th>
                    <th>produk</th>
                    <th>item</th>
                    <th>keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $produk)
                    <tr>
                        <td>
                            <a href="{{ route('produksi.semi-show', $produk->id) }}">{{ $produk->nomer_produksi }}</a>
                        </td>
                        <td>{{date('d/m/Y',strtotime($produk->tanggal_produksi))}}</td>
                        <td>{{ $produk->barang->nama_barang }}</td>
                        <td>
                            @foreach ($produk->semiProduksiRinci as $item)
                                {{ $item->barang->nama_barang }},
                            @endforeach
                        </td>
                        <td>
                            {{ $produk->keterangan }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tabel_dt').DataTable({
            'ordering': false,
            'pageLength': 100
        });
    });
</script>
@endsection