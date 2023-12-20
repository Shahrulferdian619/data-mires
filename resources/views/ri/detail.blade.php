@extends('layouts.vuexy')

@section('header')
Rincian Penerimaan Pesanan (RI)
@endsection

@section('content')

@if (session()->has('fail'))
    @include('layouts.fail')
@endif

<a href="/admin/ri">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <label>Nomer Surat Jalan</label>
                <input type="text" class="form-control" readonly value="{{ $ri->nomer_ri }}">
                
                <label>Nomer Pesanan (PO)</label>
                <input type="text" class="form-control" readonly value="{{ $ri->po->nomer_po }}">
                
                <label>Tanggal Penerimaan</label>
                <input type="date" class="form-control" readonly value="{{ $ri->tanggal_ri }}">
                
                <label>Supplier</label>
                <input type="text" class="form-control" readonly value="{{ $ri->supplier->nama_supplier }}">
                
                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" readonly>{{ $ri->keterangan }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ri->rinci as $rinci)
                <tr>
                    <td>{{ $rinci->barang->nama_barang }}</td>
                    <td>{{ number_format($rinci->qty) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@php
    $type = "recieve_item"
@endphp
@include('button.show')

@endsection