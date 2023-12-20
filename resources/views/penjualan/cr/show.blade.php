@extends('layouts.vuexy')

@section('header')
Rincian Customer Receipt
@endsection

@section('content')

<a href="/admin/cr">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

@if (session()->has('fail'))
    @include('layouts.fail')
@endif

<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless" >
                    <tr>
                        <td style="width: 30%" >Nomer CR</td>
                        <td>: {{ $cr->nomer_cr }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ $cr->tanggal_cr }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pelanggan</td>
                        <td>: {{ $cr->pelanggan->nama_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $cr->note ?? '-' }}</td>
                    </tr>   
                    <tr>
                        <td>Total Pembayaran</td>
                        <td>: Rp. {{ number_format($cr->total_payment, 0, ',', '.') }}</td>
                    </tr>   
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Rincian Customer Receipt</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Total Pembayaran</th>
                        <th>Keterangan</th>
                    </tr>
                    @foreach($cr->rinci as $rinci)
                    <tr>
                        <td>{{ $rinci->invoice->nomer_invoice }}</td>
                        <td>Rp. {{ number_format($rinci->total_payment) }}</td>
                        <td>{{ $rinci->keterangan }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="text-align" >#</th>
                            <th class="text-align" >Nama Barang</th>
                            <th class="text-align" >Jumlah Barang</th>
                            <th class="text-align" >Diskon Barang</th>
                            <th class="text-align" >Harga Barang</th>
                        </tr>
                    </thead>
                    @php
                        $grandTotal = 0;
                    @endphp
                    <tbody>
                        @foreach ($si->rinci as $key => $value)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td>{{ $value->barang->nama_barang }}</td>
                            <td class="text-right" >{{ $value->qty }}</td>
                            <td class="text-right" >{{ $value->dsc }}%</td>
                            <td class="text-right" >Rp. {{ number_format($value->harga, 0, ',', '.') }}</td>
                            @php
                                $calculateDC = $value->harga - ($value->harga * ($value->dsc / 100));
                                $grandTotal += $calculateDC * $value->qty;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="4" >Total Harga</th>
                            <th class="text-right" >Rp. {{ number_format($grandTotal, 0, ',', '.')}}</th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="4" >PPN</th>
                            <th class="text-right" > {{ $si->so->is_tax == 1 ? '10%' : '0%' }} </th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="4" >Total Harga</th>
                            <th class="text-right" >Rp. 
                                @if ($si->so->is_tax == 1)
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
</div> --}}
@if ($cr->berkas)
<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless" >
                    <tr>
                        <td style="width: 30%" >Berkas 1</td>
                        <td>: 
                            @if ($cr->berkas->berkas_1 != '')
                                <a href=" {{ asset('uploads/cr_penjualan/' . $cr->berkas_1) }} "> {{ $cr->berkas->berkas_1 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%" >Berkas 2</td>
                        <td>: 
                            @if ($cr->berkas->berkas_2 != '')
                                <a href=" {{ asset('uploads/cr_penjualan/' . $cr->berkas_2) }} "> {{ $cr->berkas->berkas_2 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%" >Berkas 3</td>
                        <td>: 
                            @if ($cr->berkas->berkas_3 != '')
                                <a href=" {{ asset('uploads/cr_penjualan/' . $cr->berkas_3) }} "> {{ $cr->berkas->berkas_3 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%" >Berkas 4</td>
                        <td>: 
                            @if ($cr->berkas->berkas_4 != '')
                                <a href=" {{ asset('uploads/cr_penjualan/' . $cr->berkas_4) }} "> {{ $cr->berkas->berkas_4 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%" >Berkas 5</td>
                        <td>: 
                            @if ($cr->berkas->berkas_5 != '')
                                <a href=" {{ asset('uploads/cr_penjualan/' . $cr->berkas_5) }} "> {{ $cr->berkas->berkas_5 }} </a>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<?php $type = "pmtpembelian" ?>
{{-- @if(Auth::user()->level_id == 2)
    @if($pmtpembelian->approve_direktur == 0)
        @include('button.approve')
    @else
        @include('button.show')
    @endif
@elseif(Auth::user()->level_id == 3)
@else 
    @include('button.show')
@endif --}}



@endsection