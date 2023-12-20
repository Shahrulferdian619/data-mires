@extends('layouts.vuexy')

@section('header')
Rincian Pesanan PO
@endsection

@section('content')

<a href="/admin/po">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

@if($popembelian->approve_direktur == 1 && $popembelian->approve_komisaris == 1)
<div class="alert alert-success" role="alert">
    <div class="alert-body">
        Pengajuan sudah disetujui...
    </div>
</div>
@elseif($popembelian->approve_direktur == 1 && $popembelian->approve_komisaris == 2)
<div class="alert alert-danger" role="alert">
    <div class="alert-body">
        Pengajuan tidak disetujui...
    </div>
</div>
@else 
<div class="alert alert-warning" role="alert">
    <div class="alert-body">
        Pengajuan belum disetujui...
    </div>
</div>
@endif

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <label>Nomer Pesanan PO</label>
                <input type="text" class="form-control" readonly value="{{ $popembelian->nomer_po }}">
                
                <label>Tanggal Pesanan PO</label>
                @php
                    $newDate = date_create($popembelian->tanggal_po);
                    $newDate = date_format($newDate, 'Y-m-d');
                @endphp
                <input type="date" class="form-control" readonly value="{{ $newDate }}">
                
                <label>Supplier</label>
                <input type="text" class="form-control" readonly value="{{ $popembelian->supplier->nama_supplier }}">
                
                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" readonly>{{ $popembelian->keterangan }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Discount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $subtotal = 0; ?>
                    @foreach($popembelian->rinci as $rinci)
                    <tr>
                        <td>{{ $rinci->barang->nama_barang }}@if($rinci->description != null) <small>({{ $rinci->description }})</small> @endif</td>
                        <td>{{ number_format($rinci->jumlah) }}</td>
                        <td>Rp.{{ number_format($rinci->harga) }}</td>
                        <td>{{ $rinci->dsc }} %</td>
                    </tr>
                    <?php $total = $rinci->jumlah * ($rinci->harga - ($rinci->harga * $rinci->dsc / 100)); ?>
                    <?php $subtotal += $total; ?>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"> <strong>Total</strong> </td>
                        <td>Rp.{{ number_format($subtotal) }}</td>
                    </tr>
                    @if($popembelian->pph != 0)
                    <tr>
                        <td colspan="3" class="text-right"> <strong>PPh (-)</strong> </td>
                        <td>Rp.{{ number_format($subtotal * $popembelian->pph / 100) }}</td>
                        @php $subtotal = $subtotal - ($subtotal * $popembelian->pph / 100); @endphp
                    </tr>
                    @endif
                    @if($popembelian->pajak_lain != 0)
                    <tr>
                        <td colspan="3" class="text-right"> <strong>Pajak Lain (+)</strong> </td>
                        <td>Rp.{{ number_format($subtotal * $popembelian->pajak_lain / 100) }}</td>
                        @php $subtotal = $subtotal + ($subtotal * $popembelian->pajak_lain / 100); @endphp
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="text-right"> <strong>PPN 11%</strong> </td>
                        @if($popembelian->is_tax == 1)
                        <td>Rp.{{ number_format($subtotal*11/100) }}</td>
                        @php $subtotal = $subtotal+($subtotal*11/100)@endphp
                        @else
                        <td>Rp.0</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right"> <strong>Grand Total</strong> </td>
                        <td>Rp.{{ number_format($subtotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@if (!empty($popembelian->berkas))
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">
                Berkas Pendukung
            </h3>

            <table class="table table-striped">
                <tr>
                    <td>Berkas 1</td>
                    <td>:</td>
                    <td>
                        @if ($popembelian->berkas->berkas_1 != '')
                            <a download href="{{ asset('uploads/popembelian/' . $popembelian->berkas->berkas_1) }}" target="_blank" rel="noopener noreferrer">{{ $popembelian->berkas->berkas_1 }}</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Berkas 2</td>
                    <td>:</td>
                    <td>
                        @if ($popembelian->berkas->berkas_2 != '')
                            <a download href="{{ asset('uploads/popembelian/' . $popembelian->berkas->berkas_2) }}" target="_blank" rel="noopener noreferrer">{{ $popembelian->berkas->berkas_2 }}</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Berkas 3</td>
                    <td>:</td>
                    <td>
                        @if ($popembelian->berkas->berkas_3 != '')
                            <a download href="{{ asset('uploads/popembelian/' . $popembelian->berkas->berkas_3) }}" target="_blank" rel="noopener noreferrer">{{ $popembelian->berkas->berkas_3 }}</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Berkas 4</td>
                    <td>:</td>
                    <td>
                        @if ($popembelian->berkas->berkas_4 != '')
                            <a download href="{{ asset('uploads/popembelian/' . $popembelian->berkas->berkas_4) }}" target="_blank" rel="noopener noreferrer">{{ $popembelian->berkas->berkas_4 }}</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Berkas 5</td>
                    <td>:</td>
                    <td>
                        @if ($popembelian->berkas->berkas_5 != '')
                            <a download href="{{ asset('uploads/popembelian/' . $popembelian->berkas->berkas_5) }}" target="_blank" rel="noopener noreferrer">{{ $popembelian->berkas->berkas_5 }}</a>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endif

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">
                    Note direktur
                </h3>
                <textarea name="" id="" cols="30" rows="10" class="form-control" readonly>{{ $popembelian->note_direktur }}</textarea>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">
                    Note Komisaris
                </h3>
                <textarea name="" id="" cols="30" rows="10" class="form-control" readonly>{{ $popembelian->note_komisaris }}</textarea>
            </div>
        </div>
    </div>
    </div>
</div>



<?php $type = "popembelian" ?>
@if(Auth::user()->level_id == 2)
    @if($popembelian->approve_direktur == 0)
        @include('button.approve')
    @else
        @include('button.show')
    @endif
@elseif(Auth::user()->level_id == 3)
    @if($subtotal > 5000000)
        @if($popembelian->approve_komisaris == 0)
            @include('button.approve')
        @else
            @include('button.show')
        @endif
    @endif
@else
    @include('button.show')
@endif

@endsection 