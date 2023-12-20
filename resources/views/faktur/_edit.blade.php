@extends('layouts.vuexy')

@section('header')
    Edit faktur pembelian
@endsection

@section('content')
    @if($errors->all())
        @include('layouts.validation')
    @endif

    @if ($fakturpembelian->approve_direktur == 1 || $fakturpembelian->approve_komisaris == 1)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        Tidak bisa merubah data, karna pengajuan telah di setujui
                        <a href="{{ url('admin/fakturpembelian/' .$popembelian->id ) }}">kembali</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <a href="{{ url('admin/fakturpembelian/' . $fakturpembelian->id) }}">
            <i class="fa fa-arrow-left"></i> Kembali ke daftar
        </a>
        <hr>
        <form action="{{ url('admin/fakturpembelian/' . $fakturpembelian->id) }}" method="post" enctype="multipart/form-data" >
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nomer_fakturpembelian">Nomer Faktur Pembelian</label>
                                <input type="text" name="nomer_fakturpembelian" id="nomer_fakturpembelian" class="form-control" value="{{ $fakturpembelian->nomer_fakturpembelian }}" readonly >
                                @csrf
                                @method('patch')
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal Faktur</label>
                                <input type="text" name="tanggal" id="tanggal" class="form-control" value="{{ $fakturpembelian->tanggal }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <input type="text" name="supplier_id" id="supplier_id" class="form-control" value="{{ $fakturpembelian->supplier->nama_supplier }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control" readonly >{{ $fakturpembelian->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="termin">Jatuh Tempo</label>
                                <select name="termin" id="termin" class="form-control">
                                    <option value="14" {{ $fakturpembelian->termin == 14 ? 'selected' : '' }} >14 Hari</option>
                                    <option value="30" {{ $fakturpembelian->termin == 30 ? 'selected' : '' }} >30 Hari</option>
                                    <option value="60" {{ $fakturpembelian->termin == 60 ? 'selected' : '' }} >60 Hari</option>
                                    <option value="90" {{ $fakturpembelian->termin == 90 ? 'selected' : '' }} >90 Hari</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Berkas 1</label>
                                    <input type="file" name="berkas_1" id="berkas_1" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Berkas 2</label>
                                    <input type="file" name="berkas_2" id="berkas_2" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Berkas 3</label>
                                    <input type="file" name="berkas_3" id="berkas_3" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Berkas 4</label>
                                    <input type="file" name="berkas_4" id="berkas_4" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>Berkas 5</label>
                                    <input type="file" name="berkas_5" id="berkas_5" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection