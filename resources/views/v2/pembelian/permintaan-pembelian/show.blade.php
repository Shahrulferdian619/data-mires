@extends('v2.layout.vuexy')

@section('custom_style')

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

<a href="{{ route('pembelian.permintaan-pembelian.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<!-- Data Permintaan -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr class="table-info">
                        <td colspan="2">DATA PERMINTAAN</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 300px;">Status pengajuan</td>
                        <td>:
                            @if($permintaan->approve_direktur == 0)
                            <span class="badge bg-label-warning">Belum diapprove</span>
                            @elseif($permintaan->approve_direktur == 1)
                            <span class="badge bg-label-success">Sudah diapprove</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Nomer permintaan pembelian</td>
                        <td>: {{$permintaan->nomer_permintaan_pembelian}}</td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Tanggal</td>
                        <td>: {{date('d/m/Y',strtotime($permintaan->tanggal))}}</td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Keterangan</td>
                        <td>: {{$permintaan->keterangan}}</td>
                    </tr>
                </tbody>
            </table>
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
            <table class="table table-borderless">
                <thead>
                    <tr class="table-info">
                        <th>item</th>
                        <th>deskripsi</th>
                        <th>kuantitas</th>
                        <th>harga</th>
                        <th>catatan</th>
                        <th>tgl minta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permintaan->rincianPermintaan as $r)
                    <tr>
                        <td>
                            {{$r->item->nama_barang}}
                        </td>
                        <td>
                            {{$r->deskripsi_item}}
                        </td>
                        <td>
                            {{$r->kuantitas}}
                        </td>
                        <td>
                            {{$r->harga}}
                        </td>
                        <td>
                            {{$r->catatan}}
                        </td>
                        <td>
                            {{date('d/m/Y',strtotime($r->tanggal_minta))}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>

<!-- Data Berkas -->
@if($permintaan->berkasPermintaan()->exists())
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Berkas
        </h5>
    </div>

    <div class="card-body">
        <div class="col-md-6">
            <table class="table table-borderless tabel-berkas">
                @foreach($permintaan->berkasPermintaan as $berkas)
                <tr>
                    <td>Berkas</td>
                    <td>
                        <a href="{{ route('pembelian.permintaan-pembelian.download-berkas',$berkas->nama_berkas) }}">
                            {{ $berkas->nama_berkas }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<br>
@endif

<!-- Button Submit -->
<div class="card">
    <div class="card-footer">
        <a href="{{ route('pembelian.permintaan-pembelian.print-pdf',$permintaan->id) }}" class="btn btn-outline-info" target="_blank">Print</a>
        @if(Auth::user()->position != 'direktur')
        <a href="{{ route('pembelian.permintaan-pembelian.edit',$permintaan->id) }}" class="btn btn-outline-warning">Ubah</a>
        <button class="btn btn-outline-danger btn-hapus">Hapus</button>
        @endif
        @if(Auth::user()->position == 'direktur')
        <button type="button" class="btn btn-outline-success btn-approve">Approve</button>
        <button type="button" class="btn btn-outline-danger btn-reject">Reject</button>
        @endif
        @if($permintaan->approve_direktur == 1)
        <a href="{{ route('pembelian.permintaan-pembelian.revisi',$permintaan->id) }}" class="btn btn-outline-warning">Revisi data</a>
        @endif
    </div>
</div>

<!-- Modal hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.permintaan-pembelian.destroy',$permintaan->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus Data!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Apakah Anda yakin menghapus data ini?</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(Auth::user()->position == 'direktur')
<!-- Modal approve direktur -->
<div class="modal fade" id="modalApprove" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.permintaan-pembelian.approve-direktur',$permintaan->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Approve pengajuan!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Catatan</label>
                    <textarea name="catatan_direktur" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal reject direktur -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.permintaan-pembelian.reject-direktur',$permintaan->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Reject pengajuan!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Catatan</label>
                    <textarea name="catatan_direktur" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-approve').click(function(e) {
            $('#modalApprove').modal('show');
        });

        $('.btn-reject').click(function(e) {
            $('#modalReject').modal('show');
        });

        $('.btn-hapus').click(function(e) {
            $('#modalHapus').modal('show');
        });
    });
</script>
@endsection