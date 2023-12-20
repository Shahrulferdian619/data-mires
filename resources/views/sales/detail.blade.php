@extends('layouts.vuexy')

@section('header')
Detail Sales ( Detail Data Sales )
@endsection

@section('content')

@if (session()->has('fail'))
    @include('layouts.fail')
@endif

<a href="/admin/sales">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
            <label>Nama Sales <span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" readonly value="{{ $sales->nama_sales }}">

            <label>Kode Sales / Distributor / Toko</label>
            <input type="text" class="form-control" readonly name="kode_sales" value="{{ $sales->kode }}">

            <label>Kode Area </label>
            <input type="text" class="form-control" readonly name="kode_area" value="{{ $sales->kode_area }}">
            
            
            <label>Target Total Invoice</label>
            <input type="text" class="form-control" readonly value="Rp. {{ number_format($sales->target_total_invoice, 0, ',', '.') }}" >
            
            <label>Bonus Presentase</label>
            <input type="text" class="form-control" readonly value="{{ $sales->bonus_presentase }}%" >
            
            <label>Keterangan Tambahan</label>
            <textarea class="form-control" rows="4" readonly >{{ $sales->keterangan_tambahan }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <form action="/admin/sales/{{ $sales->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/sales/{{$sales->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>
@endsection