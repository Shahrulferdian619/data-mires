@extends('layouts.vuexy')

@section('header')
Rincian Penjualan Order
@endsection

@section('content')

<a href="/admin/so">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

@if (session()->has('fail'))
@include('layouts.fail')
@endif

<!-- Header untuk Sales Order -->
<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <table class="table table-borderless">
                    <tr>
                        <td style="width: 30%">Nomer Penjualan</td>
                        <td>: {{ $so->so_nomer }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Penjualan</td>
                        <td>: {{ $so->jenis_penjualan }}</td>
                    </tr>
                    @if(!empty($so->no_pesanan))
                    <tr>
                        <td>No Pesanan</td>
                        <td>: {{ $so->no_pesanan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Tanggal Penjualan</td>
                        <td>: {{ $so->so_tanggal }}</td>
                    </tr>
                    <tr>
                        <td>Nama Customer/Pemesan</td>
                        <td>: {{ $so->pelanggan->nama_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td>Kode Customer</td>
                        <td>: {{ $so->pelanggan->kode_area }}-{{ $so->pelanggan->nama_pelanggan }}-{{ $so->pelanggan->kode_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td>Nama Penerima</td>
                        <td>: {{ $so->penerima }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Pengiriman</td>
                        <td>: {{ $so->alamat_pengiriman }}</td>
                    </tr>

                    @if(!empty($so->ekspedisi))
                    <tr>
                        <td>Ekspedisi</td>
                        <td>: {{ $so->ekspedisi }}</td>
                    </tr>
                    @endif

                    @if(!empty($so->resi))
                    <tr>
                        <td>No Resi</td>
                        <td>: {{ $so->resi }}</td>
                    </tr>
                    @endif
                    @if(!empty($so->sales->nama_sales))
                    <tr>
                        <td>Nama Sales</td>
                        <td>: {{ $so->sales->kode }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Keterangan Penjualan</td>
                        <td>: {{ $so->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status Barang</td>
                        <td>:
                            @if($so->status_do == 0)
                            <div style="width:150px" class="badge badge-light-warning">Belum dikirim</div>
                            @elseif($so->status_do == 1)
                            <div style="width:150px" class="badge badge-light-info">Dikirim Sebagian</div>
                            @elseif($so->status_do == 2)
                            <div style="width:150px" class="badge badge-light-success">Sudah Dikirim</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>:
                            @if($so->status_invoice == 0)
                            <div style="width:150px" class="badge badge-light-warning">Belum invoice</div>
                            @elseif($so->status_invoice == 1)
                            <div style="width:150px" class="badge badge-light-info">Dibuatkan Invoice</div>
                            @elseif($so->status_invoice == 2)
                            <div style="width:150px" class="badge badge-light-success">Sudah lunas</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End of - Header untuk Sales Order -->

<!-- Rincian Sales Order -->
<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th class="text-align">Nama Barang</th>
                                <th class="text-align">Harga</th>
                                <th class="text-align">Jumlah</th>
                                <th class="text-align">Diskon</th>
                                <th class="text-align">Diskon Nominal</th>
                                <th class="text-align"><small>Biaya Admin, Layanan, PPN</small></th>
                                <th class="text-align">Cashback Ongkir</th>
                                <th class="text-align">Total</th>
                                <th class="text-align">Note</th>
                            </tr>
                        </thead>
                        @php
                        $grandTotal = 0;
                        @endphp
                        <tbody>
                            @foreach ($so->rinci as $key => $value)
                            <tr>
                                <td>{{ $value->barang->nama_barang }}</td>
                                <td class="text-right">{{ number_format($value->harga_barang, 0, ',', '.') }}</td>
                                <td class="text-right">{{ $value->qty_barang }}</td>
                                <?php $total = $value->harga_barang * $value->qty_barang; ?>
                                <td class="text-right">{{ $value->diskon_barang }}%</td>
                                <td class="text-right">{{ number_format($value->diskon_nominal, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($value->potongan_admin, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($value->cashback_ongkir, 0, ',', '.') }}</td>
                                <?php $subtotal = $total - ($total * $value->diskon_barang / 100) - $value->diskon_nominal - $value->potongan_admin + $value->cashback_ongkir; ?>
                                <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td>{{ $value->note }}</td>
                                <?php $grandTotal += $subtotal; ?>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Grand Total</th>
                                <th class="text-right" colspan="2">Rp. {{ number_format($grandTotal, 0, ',', '.')}}</th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="7">PPN</th>
                                <th class="text-right" colspan="2"> {{ $so->is_tax == 1 ? 'Rp. '.number_format($grandTotal * 10/100, 0, ',', '.') : '0%' }} </th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="7">Grand Total</th>
                                <th class="text-right" colspan="2">Rp.
                                    @if ($so->is_tax == 1)
                                    {{ number_format($grandTotal + ($grandTotal * (10 / 100)), 0, ',', '.') }}
                                    @else
                                    {{ number_format($grandTotal, 0, ',', '.')}}
                                    @endif
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of - Rincian Sales Order -->

@if (!empty($so->berkas))
<!-- Berkas Sales Order -->
<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 30%">Berkas 1</td>
                        <td>:
                            @if ($so->berkas->berkas_1 != '')
                            <a href="{{ asset('uploads/so_penjualan/' . $so->berkas->berkas_1) }}"> {{ $so->berkas->berkas_1 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 2</td>
                        <td>:
                            @if ($so->berkas->berkas_2 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_2) }} "> {{ $so->berkas->berkas_2 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 3</td>
                        <td>:
                            @if ($so->berkas->berkas_3 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_3) }} "> {{ $so->berkas->berkas_3 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 4</td>
                        <td>:
                            @if ($so->berkas->berkas_4 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_4) }} "> {{ $so->berkas->berkas_4 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 5</td>
                        <td>:
                            @if ($so->berkas->berkas_5 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_5) }} "> {{ $so->berkas->berkas_5 }} </a>
                            @endif
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
<!-- End of - Berkas berkas Sales Order -->
@endif

<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('so.edit_v2', $so->id) }}" class="btn btn-outline-warning">
                    <i class="fa fa-edit"></i> Ubah Data
                </a>

                <a href="#" class="btn btn-outline-success">
                    <i class="fa fa-edit"></i> Print DO
                </a>

                <a href="{{ route('so.print_so', $so->id) }}" class="btn btn-outline-success" target="_blank">
                    <i class="fa fa-edit"></i> Print SO
                </a>

                <div class="pull-right">
                    <form action="{{ url('/admin/so/delete/' . $so->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Anda Yakin Untuk Menghapus Sales Order?')" class="btn btn-outline-danger">
                            <i class="fa fa-trash"></i> Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection