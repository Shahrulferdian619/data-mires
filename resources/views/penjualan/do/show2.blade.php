@extends('layouts.vuexy')

@section('header')
Rincian Penjualan Pengiriman Order
@endsection

@section('content')

<a href="/admin/do">
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
                <table class="table table-borderless" >
                    <tr>
                        <td style="width: 30%" >Nomer Pengiriman</td>
                        <td>: {{ $do->do_nomer }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengiriman</td>
                        <td>: {{ $do->do_tanggal }}</td>
                    </tr>
                    <tr>
                        <td>Nomer Penjualan</td>
                        <td>: {{ $do->so->so_nomer }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pelanggan</td>
                        <td>: {{ $do->pelanggan->nama_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td>Nama Penerima</td>
                        <td>: {{ $do->pic_do }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Pengiriman</td>
                        <td>: {{ $do->alamat_do }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan Pengiriman</td>
                        <td>: {{ $do->keterangan ?? '-' }}</td>
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
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="text-align" >#</th>
                            <th class="text-align" >Nama Barang</th>
                            <th class="text-align" >Jumlah Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($do->rinci as $key => $value)
                        <tr>
                            <td> {{ $key+1 }} </td>
                            <td>{{ $value->barang->nama_barang }}</td>
                            <td>{{ $value->qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@if (!empty($do->berkas))
<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless" >
                    <tr>
                        <td>Berkas 1</td>
                        <td>: 
                            @if ($do->berkas->berkas_1 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas->berkas_1) }} "> {{ $do->berkas->berkas_1 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Berkas 2</td>
                        <td>: 
                            @if ($do->berkas->berkas_2 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas->berkas_2) }} "> {{ $do->berkas->berkas_2 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Berkas 3</td>
                        <td>: 
                            @if ($do->berkas->berkas_3 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas->berkas_3) }} "> {{ $do->berkas->berkas_3 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Berkas 4</td>
                        <td>: 
                            @if ($do->berkas->berkas_4 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas->berkas_4) }} "> {{ $do->berkas->berkas_4 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Berkas 5</td>
                        <td>: 
                            @if ($do->berkas->berkas_5 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas->berkas_5) }} "> {{ $do->berkas->berkas_5 }} </a>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endif


@include('penjualan.button.do')



@endsection