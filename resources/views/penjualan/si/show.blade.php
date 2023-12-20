@extends('layouts.vuexy')

@section('header')
Rincian Invoice
@endsection

@section('content')

<a href="/admin/si">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

@if (session()->has('fail'))
@include('layouts.fail')
@endif

@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Success !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session()->get('success') }}</li>
        </ul>
    </div>
</div>
@endif

<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    @if($so->status_invoice == 0)
                    <div class="alert alert-warning" role="alert">
                        <div class="alert-body">
                            Belum Dibuatkan Invoice
                        </div>
                    </div>
                    @elseif($so->status_invoice == 1)
                    <div class="alert alert-info" role="alert">
                        <div class="alert-body">
                            Sudah Dibuatkan Invoice
                        </div>
                    </div>
                    @elseif($so->status_invoice == 2)
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">
                            Invoice Sudah Lunas
                        </div>
                    </div>
                    @endif
                    <tr>
                        <td style="width: 30%">Nomer SO</td>
                        <td>: {{ $so->so_nomer }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengiriman</td>
                        <td>: {{ $do->do_tanggal }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pelanggan</td>
                        <td>: {{ $so->pelanggan->nama_pelanggan }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

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
                                <th class="text-right" colspan="7">Total</th>
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


<div class="">
    <div class="card">
        <div class="card-body">
            @if($so->status_invoice == 0)
            <button class="btn btn-outline-primary" onclick="approve()">Buat Invoice!</button>
            @else
            <a href="/admin/si/{{ $so->id }}/print" class="btn btn-outline-success">
                <i class="fa fa-print"></i>
                Cetak Invoice
            </a>
            @endif
        </div>
    </div>
</div>


<div class="modal fade" id="approveKeterangan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="text-center m-1">Anda Yakin Membuatkan Invoice Untuk Transaksi Ini?</h4>
                <table class="table table-borderless">
                        <tfoot>
                            <tr>
                                <th>Nama Pelanggan</th>
                                <th><strong>{{ $so->pelanggan->nama_pelanggan }}</strong> </th>
                            </tr>
                            <tr>
                                <th> Total Tagihan</th>
                                <th>Rp.
                                    @if ($so->is_tax == 1)
                                    {{ number_format($grandTotal + ($grandTotal * (10 / 100)), 0, ',', '.') }}
                                    @else
                                    {{ number_format($grandTotal, 0, ',', '.')}}
                                    @endif
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                <small>- Periksa Kembali Kebenaran Transaksi Ini!</small> <br>
                <small>- Pastikan Barang & Jumlah Tagihan Sudah Benar!</small>
            </div>
            <div class="modal-footer mb-2">
                    <input type="hidden" value="{{ $so->id }}" name="id_so">

                <form action="{{ url('admin/si/store') }}" method="post">
                @csrf

                    <input type="hidden" value="{{ $so->id }}" name="id_so">
                    <button type="submit" class="btn btn-primary">Ya!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function approve() {
        $('#approveKeterangan').modal('show');
    }
</script>


@endsection