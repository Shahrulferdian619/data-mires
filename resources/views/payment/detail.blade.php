@extends('layouts.vuexy')
@section('header')
Pembayaran
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="card-inner">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="d-flex flex-row" >
                                    <div class="logo-wrapper">
                                        <img src="{{ asset('vuexy') }}/images/logo/logo_ptmires.png" width="40" alt="">
                                        {{-- <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                            <defs>
                                                <linearGradient id="invoice-linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                                    <stop stop-color="#000000" offset="0%"></stop>
                                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                </linearGradient>
                                                <linearGradient id="invoice-linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                                </linearGradient>
                                            </defs>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-400.000000, -178.000000)">
                                                    <g transform="translate(400.000000, 178.000000)">
                                                        <path class="text-primary" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill: currentColor"></path>
                                                        <path d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#invoice-linearGradient-1)" opacity="0.2"></path>
                                                        <polygon fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                                        <polygon fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                                        <polygon fill="url(#invoice-linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg> --}}
                                    </div>
                                    <h3 class="text-primary invoice-logo ml-2">Mires Mahisa</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 d-flex justify-content-start justify-content-sm-end">
                                <div class="mt-md-0 mt-2">
                                    <h4 class="invoice-title">
                                        <span class="invoice-number">#{{ $payment->nomer_payment }}</span>
                                    </h4>
                                    <div class="invoice-date-wrapper">
                                        @php
                                            $newDate = date_create($payment->tanggal);
                                            $newDate = date_format($newDate, 'l, d M Y');
                                        @endphp
                                        <p class="invoice-date-title">Tanggal: {{ $newDate }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="invoice-spacing" />
                        <div class="row">
                            <div class="col-12 col-sm-8 mb-2">
                                <h6 class="mb-2">Supplier : </h6>
                                <p class="card-text">
                                    <strong class="d-block">{{ $payment->supplier->kode_supplier }}</strong>
                                    <span class="d-block">{{ $payment->supplier->nama_supplier }}</span>
                                    <small class="d-block"><em>{{ $payment->supplier->detail_alamat }}</em></small>
                                    <small class="d-block"><em>{{ $payment->supplier->handphone_supplier }}</em></small>
                                    <small class="d-block"><em>{{ $payment->supplier->email_supplier }}</em></small>
                                </p>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <h6 class="mb-2">Detail Pembayaran : </h6>
                                <p class="card-text">
                                    <div>
                                        <strong>Total Tagihan: </strong> 
                                        <small><em>Rp. {{ number_format($payment->jumlah_tagihan, 0, ',', '.') }}</em></small>
                                    </div>
                                    <div>
                                        <strong>Total Yang Dibayar: </strong> 
                                        <small><em>Rp. {{ number_format($total_payment, 0, ',', '.') }}</em></small>
                                    </div>
                                    <div>
                                        <strong>Nama Bank : </strong> 
                                        <small><em>{{ $payment->bank != '' ? $payment->bank : '-' }}</em></small>
                                    </div>
                                    <div>
                                        <strong>Keterangan : </strong> 
                                        <small><em>{{ $payment->keterangan }}</em></small>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Invoice Description starts -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-center" >Nomor Tagihan</th>
                                <th colspan="4" class="text-center" >Rincian</th>
                            </tr>
                            <tr>
                                <th>Barang</th>
                                <th>Kuantitas</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payment->paymentfaktur as $item)
                                @foreach ($item->faktur->rinci as $key => $value)
                                    <tr>
                                        @if ($key == 0)
                                            <td rowspan="{{ count($item->faktur->rinci) }}" >
                                                <p class="card-text fw-bold mb-25">{{ $item->faktur->nomer_fakturpembelian }}</p>
                                            </td>
                                        @endif
                                        <td>{{ $value->barang->nama_barang }}</td>
                                        <td>{{ $value->qty }}</td>
                                        <td>Rp. {{ number_format($value->harga, 0, ',', '.') }}</td>
                                        <td>{{ $value->dsc }}%</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection