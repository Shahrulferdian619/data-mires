@extends('layouts.vuexy')
@section('header')
Edit permintaan pembelian
@endsection

@section('content')
    @if($errors->all())
        @include('layouts.validation')
    @endif

    @if ($popembelian->approve_direktur == 1 || $popembelian->approve_komisaris == 1)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        Tidak bisa merubah data, karna pengajuan telah di setujui
                        <a href="{{ url('admin/po/' .$popembelian->id ) }}">kembali</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <a href="{{ url('admin/popembelian') }}">
            <i class="fa fa-arrow-left"></i> Kembali ke daftar
        </a>

        <hr>

        <form action="{{ url('admin/po/' . $popembelian->id) }}" method="post" enctype="multipart/form-data" >
            @method('patch')
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="nomer_po">Nomor Pesanan PO</label>
                                    <input type="text" name="nomer_po" class="form-control" id="nomer_po" value="{{ $popembelian->nomer_po }}">
                                    @csrf
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_po">Tanggal Pesanan PO</label>
                                    <input type="date" name="tanggal_po" class="form-control" id="tanggal_po" value="{{ $popembelian->tanggal_po }}">
                                </div>
                                <div class="form-group">
                                    <label for="tujuan_pengiriman">Tujuan Pengiriman PO</label>
                                    <input type="text" readonly name="tujuan_pengiriman" class="form-control" id="tujuan_pengiriman" value="{{ $popembelian->tujuan_pengiriman }}">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan PO </label>
                                    <textarea class="form-control" rows="5" name="keterangan" >{{ $popembelian->keterangan }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="is_tax">PPN 10%</label>
                                <select name="is_tax" id="is_tax" class="form-control">
                                    <option value="0" {{ $popembelian->is_tax == 0 ? 'selected' : '' }} >Tidak</option>
                                    <option value="1" {{ $popembelian->is_tax == 1 ? 'selected' : '' }} >Iya</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-outline-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection