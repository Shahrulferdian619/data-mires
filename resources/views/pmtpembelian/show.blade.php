@extends('layouts.vuexy')

@section('header')
Rincian permintaan pembelian
@endsection

@section('content')

<a href="/admin/pmtpembelian">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

@if($pmtpembelian->approve_direktur == 1)
<div class="alert alert-success" role="alert">
    <div class="alert-body">
        Pengajuan sudah disetujui...
    </div>
</div>
@elseif($pmtpembelian->approve_direktur == 2)
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

@if (session()->has('fail'))
    @include('layouts.fail')
@endif

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                @csrf
                <label>Nomer permintaan</label>
                <input type="text" class="form-control" readonly value="{{ $pmtpembelian->nomer_pmtpembelian }}">
                
                <label>Tanggal permintaan</label>
                <input type="date" class="form-control" readonly value="{{ $pmtpembelian->tanggal }}">
                
                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" readonly>{{ $pmtpembelian->keterangan }}</textarea>
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
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody id="show_produk">
                    <?php $grandtotal=0 ?>
                    @foreach($pmtpembelian->rinci as $rinci)
                    <tr>
                        <td>{{ $rinci->barang->nama_barang }} @if($pmtpembelian->type == 4)<small>({{ $rinci->description }})</small>@endif</td>
                        <td>{{ $rinci->qty }}</td>
                        <td>{{ $rinci->note }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <br>
    </div>
</div>


@if (!empty($pmtpembelian->berkaspendukung))
<div class="card">
    <div class="card-body">
        <h3 class="card-title">
            Berkas pendukung
        </h3>

        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <td>Berkas 1</td>
                    <td>:</td>
                    <td>
                        <a download href="/uploads/pmtpembelian/{{ $pmtpembelian->berkaspendukung->berkas_1 }}">{{ $pmtpembelian->berkaspendukung->berkas_1 }}</a>
                    </td>
                </tr>
                <tr>
                    <td>Berkas 2</td>
                    <td>:</td>
                    <td>
                        <a download href="/uploads/pmtpembelian/{{ $pmtpembelian->berkaspendukung->berkas_2 }}">{{ $pmtpembelian->berkaspendukung->berkas_2 }}</a>
                    </td>
                </tr>
                <tr>
                    <td>Berkas 3</td>
                    <td>:</td>
                    <td>
                        <a download href="/uploads/pmtpembelian/{{ $pmtpembelian->berkaspendukung->berkas_3 }}">{{ $pmtpembelian->berkaspendukung->berkas_3 }}</a>
                    </td>
                </tr>
                <tr>
                    <td>Berkas 4</td>
                    <td>:</td>
                    <td>
                        <a download href="/uploads/pmtpembelian/{{ $pmtpembelian->berkaspendukung->berkas_4 }}">{{ $pmtpembelian->berkaspendukung->berkas_4 }}</a>
                    </td>
                </tr>
                <tr>
                    <td>Berkas 5</td>
                    <td>:</td>
                    <td>
                        <a download href="/uploads/pmtpembelian/{{ $pmtpembelian->berkaspendukung->berkas_5 }}">{{ $pmtpembelian->berkaspendukung->berkas_5 }}</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endif


@if(Auth::user()->level_id != 2)
<div class="card">
    <div class="card-body">
        <h3 class="card-title">
            Note direktur
        </h3>
        <textarea name="" id="" cols="30" rows="10" class="form-control" readonly>{{ $pmtpembelian->note_direktur }}</textarea>
    </div>
</div>
@endif


<?php $type = "pmtpembelian" ?>
@if(Auth::user()->level_id == 2)
    @if($pmtpembelian->approve_direktur == 0)
        @include('button.approve')
    @else
        @include('button.show')
    @endif
@elseif(Auth::user()->level_id == 3)
@else 
    @include('button.show')
@endif



@endsection