@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')

@if($errors->any())
@include('v2.component.error')
@endif

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<a href="{{ route('pembelian.penerimaan-barang.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pembelian.penerimaan-barang.update',$data['penerimaanBarang']->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Penerimaan Barang -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Penerimaan Barang
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Pilih pesanan pembelian (PO)</label>
                    <select name="pesanan_pembelian_id" id="pilih-po" class="form-control select2" onchange="pilihPO()">
                        <option value="{{ $data['penerimaanBarang']->pesanan_pembelian_id }}" selected>{{ $data['penerimaanBarang']->pesananPembelian->nomer_pesanan_pembelian }}</option>
                        @foreach($data['pesananPembelian'] as $po)
                        <option value="{{ $po->id }}">{{ $po->nomer_pesanan_pembelian }}</option>
                        @endforeach
                    </select>

                    <label for="">Nomer penerimaan barang*</label>
                    <input type="text" name="nomer_penerimaan_barang" class="form-control" value="{{ $data['penerimaanBarang']->nomer_penerimaan_barang }}" required>

                    <label for="">Tanggal*</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $data['penerimaanBarang']->tanggal }}" required>
                </div>

                <div class="col-md-6">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" cols="30" rows="6" class="form-control" placeholder="kosongkan bila tidak perlu">{{ $data['penerimaanBarang']->keterangan }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Rincian -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Rincian Permintaan
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>item*</th>
                            <th>deskripsi item</th>
                            <th style="width: 100px;">qty*</th>
                            <th style="width: 200px;">catatan</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-item">
                        @php $i=0 @endphp
                        @foreach($data['penerimaanBarang']->rincianBarang as $rincian)
                        <tr>
                            <td>
                                <select name="rincian[{{ $i }}][item_id]" id="" class="form-control bg-light">
                                    <option value="{{ $rincian->item_id }}">{{ $rincian->barang->nama_barang }}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $i }}][deskripsi_item]" class="form-control bg-light" value="{{ $rincian->deskripsi_item }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $i }}][kuantitas]" class="form-control" value="{{ $rincian->kuantitas }}">
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $i }}][catatan]" class="form-control" value="{{ $rincian->catatan }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-item" onclick="hapusItem(this)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @php $i++ @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Berkas -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Berkas
            </h5>
        </div>

        <div class="card-body">
            <div class="col-md-6">
                @if($data['penerimaanBarang']->rincianBerkas()->exists())
                <table class="table table-borderless">
                    @foreach($data['penerimaanBarang']->rincianBerkas as $rincian)
                    <tr>
                        <td>Berkas</td>
                        <td>
                            <a href="{{ route('pembelian.penerimaan-barang.download-berkas',$rincian->nama_berkas) }}">{{ $rincian->nama_berkas }}</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
                <table class="table table-borderless tabel-berkas">
                    <tr class="berkas-0">
                        <td>Berkas</td>
                        <td>
                            <input type="file" name="berkas[0][nama_berkas]" class="form-control">
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-success btn-tambah-berkas">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('pembelian.penerimaan-barang.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
            <button type="button" class="btn btn-outline-danger" onclick="modalHapus()">Hapus</button>
        </div>
    </div>
</form>

<!-- Modal PO -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pembelian.penerimaan-barang.destroy', $data['penerimaanBarang']->id) }}" method="post">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">INFORMASI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Apakah anda yakin akan menghapus data ini?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('custom_js')
<script type="text/javascript">
    function modalHapus() {
        $('#modalHapus').modal('show');
    }

    function hapusItem(button) { // menghapus baris rincian item
        button.closest('tr').remove();
    }

    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection