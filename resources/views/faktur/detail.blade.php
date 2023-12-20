@extends('layouts.vuexy')

@section('header')
Detail Faktur Pembelian
@endsection

@section('content')

<a href="/admin/fakturpembelian">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>
@if($faktur->approve_direktur == 1 && $faktur->approve_komisaris == 1)
<div class="alert alert-success" role="alert">
    <div class="alert-body">
        Pengajuan sudah disetujui...
    </div>
</div>
@endif

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <label>Nomer Faktur Pembelian</label>
                <input type="text" class="form-control" readonly value="{{ $faktur->nomer_fakturpembelian }}">

                <label>Tanggal Faktur</label>
                <input type="date" class="form-control" readonly value="{{ $faktur->tanggal }}">

                <label>Supplier</label>
                <input type="text" class="form-control" readonly value="{{ $faktur->supplier->nama_supplier }}">

                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" readonly>{{ $faktur->keterangan }}</textarea>
            </div>
        </div>
    </div>
    @if($faktur->note_komisaris != null)
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <label>Note Owner</label>
                <textarea class="form-control" rows="4" readonly>{{ $faktur->note_komisaris }}</textarea>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <strong>TAGIHAN</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tarik Berdasarkan (Nomor)</th>
                        <th>Nominal Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($faktur->relation as $relation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($relation->ri_id != null)
                            RI ({{ $relation->ri->nomer_ri }})
                            @else
                            PO ({{ $relation->po->nomer_po }})
                            @endif
                        </td>
                        <td>Rp.{{ number_format($relation->total, 2) }}</td>
                        @php $total += $relation->total @endphp
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan=2><strong>Total</strong></td>
                        <td>Rp.{{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <strong>RINCIAN BARANG</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Discount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faktur->rinci as $rinci)
                <tr>
                    <td>{{ $rinci->barang->nama_barang }}</td>
                    <td>{{ $rinci->qty }}</td>
                    <td>Rp.{{ number_format($rinci->harga) }}</td>
                    <td>{{ $rinci->dsc }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <strong>PEMBAYARAN</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Nominal Pembayaran</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @if($faktur->payment->count() <= 0) <tr>
                        <td colspan="4">Belum Ada Pembayaran</td>
                        </tr>
                        @endif
                        @foreach($faktur->payment as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->payment->tanggal }}</td>
                            <td>Rp.{{ number_format($payment->jumlah_bayar) }}</td>
                            <td>{{ $payment->payment->keterangan }}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@php $type = 'faktur'; @endphp
@if(Auth::user()->position == 'owner')
    @if($faktur->approve_direktur == 1 && $faktur->approve_komisaris == 1) 
        @include('button.show')
    @else
        @include('button.approve_faktur')
    @endif
@else
    @include('button.show')
@endif

@endsection