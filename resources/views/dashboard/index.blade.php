@extends('layouts.vuexy')

@section('header')
Dashboard
@endsection

@section('content')

<div class="row match-height">
    <div class="col-lg-12 col-12">
        <div class="card card-statistics">
            <div class="card-header">
                <h4 class="card-title">Statistics</h4>
            </div>

            <div class="card-body statistic-body">
                <div class="row">
                    <!-- Sales Order Total -->
                    @if(in_array(Auth::user()->id, [1,2,5,6,8,9,11,1000,13,14,15,16,17,18]))
                    @include('dashboard.component.so')
                    @endif

                    <!-- Customer Data -->
                    @if(in_array(Auth::user()->id, [1,2,5,6,7,8,9,11,1000,13,14,15,16,17,18]))
                    @include('dashboard.component.customer')
                    @endif

                    <!-- Catalog Produk -->
                    @if(in_array(Auth::user()->id, [1,2,5,6,8,9,11,1000,13,14,15,16,18]))
                    @include('dashboard.component.catalog')
                    @endif

                    <!-- Nominal SO -->
                    @if(in_array(Auth::user()->id, [1,2,5,6,7,8,9,11,1000,13,14,15,16,17,18]))
                    @include('dashboard.component.nominal-so')
                    @endif

                    <!-- Revenue -->
                    @if(in_array(Auth::user()->id, [1,2,5,6,9,1000,13]))
                    @include('dashboard.component.revenue')
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jenis SO -->
@if(in_array(Auth::user()->id, [1,2,4,5,6,8,9,11,1000,13,14,16,17,18]))
@include('dashboard.component.jenis-penjualan')
@endif

<!-- Omset Penjualan -->
@if(in_array(Auth::user()->id, [1,2,5,6,9,1000,13]))
@include('dashboard.component.omset-penjualan')
@endif

<!-- Ringkasan pengajuan pembelian -->
@if(in_array(Auth::user()->id, [1,2,5,6,7,13,15,19]))
@include('dashboard.component.ringkasan-pengajuan-pembelian')
@endif

<!-- Ringkasan Data Karyawan -->
@if(in_array(Auth::user()->id, [1,5,6,15,1000]))
@include('dashboard.component.kontrak-pegawai')
@endif

@endsection

@section('myjs')
@include('dashboard.component.js')
@endsection