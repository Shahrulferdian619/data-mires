@extends('v2.layout.vuexy')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header pb-3">
                <h5 class="m-0 me-2 card-title">
                    Jumlah Penjualan
                </h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td>Shopee</td>
                        <td>{{ $data['Shopee'] }}</td>
                    </tr>
                    <tr>
                        <td>Tokopedia</td>
                        <td>{{ $data['Tokopedia'] }}</td>
                    </tr>
                    <tr>
                        <td>TikTok Shop</td>
                        <td>{{ $data['Tiktok'] }}</td>
                    </tr>
                    <tr>
                        <td>Lazada</td>
                        <td>{{ $data['Lazada'] }}</td>
                    </tr>
                    <tr>
                        <td>WhatsApp</td>
                        <td>{{ $data['Whatsapp'] }}</td>
                    </tr>
                    <tr>
                        <td>Blibli</td>
                        <td>{{ $data['Blibli'] }}</td>
                    </tr>
                    <tr>
                        <td>Offline</td>
                        <td>{{ $data['Offline'] }}</td>
                    </tr>
                    <tr>
                        <td>Event</td>
                        <td>{{ $data['Event'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header pb-3">
                <h5 class="m-0 me-2 card-title">
                    Statistik
                </h5>
            </div>

            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                <i class="ti ti-chart-pie-2 ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $data['jumlah_pesanan_penjualan'] }}</h5>
                                <small>Order/yr {{ date('Y') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{$data['jumlah_katalog_produk']}}</h5>
                                <small>Katalog</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-user ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{$data['pelanggan']}}</h5>
                                <small>Pelanggan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{number_format($data['omset'],2)}}</h5>
                                <small>Omset/yr {{ date('Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="col-md-12">
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Ringkasan pengajuan pending
            </h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Deskripsi pengajuan</th>
                        <th>Jumlah</th>
                        <th style="width: 10%;">#</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Permintaan Pembelian (PR)</td>
                        <td>{{ $data['permintaan_pembelian_pending'] }}</td>
                        <td>
                            <a href="{{ route('pembelian.permintaan-pembelian.index') }}">
                                <button class="btn btn-sm btn-outline-info">Lihat</button>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Pesanan Pembelian (PO)</td>
                        <td>{{ $data['pesanan_pembelian_pending'] }}</td>
                        <td>
                            <a href="{{ route('pembelian.pesanan-pembelian.index') }}">
                                <button class="btn btn-sm btn-outline-info">Lihat</button>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection