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

<a href="{{ route('pembelian.pesanan-pembelian.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<!-- Data Pesanan Pembelian -->
@if($pesananPembelian->status_proses == 0)
<label for="" class="alert alert-warning" style="width: 100%;">Belum diproses</label>
@elseif($pesananPembelian->status_proses == 10 || $pesananPembelian->status_proses == 2)
<label for="" class="alert alert-danger" style="width: 100%;">Ditutup/ditolak</label>
@else
<label for="" class="alert alert-success" style="width: 100%;">Sudah diproses</label>
@endif
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr class="table-info">
                        <td colspan="2">DATA PESANAN PEMBELIAN</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 300px;">Status pengajuan</td>
                        <td>:
                            @if($pesananPembelian->approve_direktur == 0)
                            <span class="badge bg-label-warning">Menunggu Direktur</span>
                            @elseif($pesananPembelian->approve_direktur == 1)
                            <span class="badge bg-label-success">Approve Direktur</span>
                            @elseif($pesananPembelian->approve_direktur == 2)
                            <span class="badge bg-label-danger">Reject Direktur</span>
                            @endif

                            @if($pesananPembelian->grandtotal >= 5000000)
                            @if($pesananPembelian->approve_komisaris == 0)
                            <span class="badge bg-label-warning">Menunggu Komisaris</span>
                            @elseif($pesananPembelian->approve_komisaris == 1)
                            <span class="badge bg-label-success">Approve Komisaris</span>
                            @elseif($pesananPembelian->approve_komisaris == 2)
                            <span class="badge bg-label-danger">Reject Komisaris</span>
                            @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Nomer pesanan pembelian</td>
                        <td>: {{$pesananPembelian->nomer_pesanan_pembelian}}</td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Tanggal</td>
                        <td>: {{date('d/m/Y',strtotime($pesananPembelian->tanggal))}}</td>
                    </tr>
                    <tr>
                        <td style="width: 300px;">Keterangan</td>
                        <td>: {{$pesananPembelian->keterangan}}</td>
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
            Rincian Item
        </h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="table-info">
                        <th>item</th>
                        <th>deskripsi</th>
                        <th>kuantitas</th>
                        <th>kuantitas diterima</th>
                        <th>harga</th>
                        <th>dsc(%)</th>
                        <th>dsc(Rp)</th>
                        <th>catatan</th>
                        <th>subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesananPembelian->rincianItem as $rincian)
                    <tr>
                        <td>
                            {{$rincian->item->nama_barang}}
                        </td>
                        <td>
                            {{$rincian->deskripsi_item}}
                        </td>
                        <td>
                            {{$rincian->kuantitas}}
                        </td>
                        <td>
                            {{ $rincian->kuantitas_diterima }}
                        </td>
                        <td>
                            {{number_format($rincian->harga,2)}}
                        </td>
                        <td>
                            {{$rincian->diskon_persen}}
                        </td>
                        <td>
                            {{number_format($rincian->diskon_nominal,2)}}
                        </td>
                        <td>
                            {{$rincian->catatan}}
                        </td>
                        <td style="text-align: right;">
                            {{number_format($rincian->subtotal,2)}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8" style="text-align: right;">Biaya kirim</td>
                        <td style="text-align: right;">{{ number_format($pesananPembelian->biaya_kirim, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" style="text-align: right;">PPn</td>
                        <td style="text-align: right;">{{ number_format($pesananPembelian->nilai_ppn, 2) }}</td>
                    </tr>
                    @if($pesananPembelian->diskon_persen_global != 0)
                    <tr>
                        <td colspan="8" style="text-align: right;">Diskon {{$pesananPembelian->diskon_persen_global}}%</td>
                        <td style="text-align: right;">{{ number_format($pesananPembelian->diskon_nominal_global, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="8" style="text-align: right;">Grandtotal</td>
                        <td style="text-align: right;">{{ number_format($pesananPembelian->grandtotal, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<br>

<!-- Data Berkas -->
@if($pesananPembelian->rincianBerkas()->exists())
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Berkas
        </h5>
    </div>

    <div class="card-body">
        <div class="col-md-6">
            <table class="table table-borderless tabel-berkas">
                @foreach($pesananPembelian->rincianBerkas as $berkas)
                <tr>
                    <td>Berkas</td>
                    <td>
                        <a href="{{ route('pembelian.pesanan-pembelian.download-berkas',$berkas->nama_berkas) }}">
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
        <a href="{{ route('pembelian.pesanan-pembelian.print-nonttd',$pesananPembelian->id) }}" class="btn btn-outline-info" target="_blank">Print non-ttd</a>
        @if($pesananPembelian->approve_direktur == 1 && $pesananPembelian->approve_komisaris == 1)
        <a href="{{ route('pembelian.pesanan-pembelian.print-ttd',$pesananPembelian->id) }}" class="btn btn-outline-info" target="_blank">Print ttd</a>
        @endif

        @unless(Auth::user()->position == 'direktur' || Auth::user()->position == 'komisaris')
        <a href="{{ route('pembelian.pesanan-pembelian.edit',$pesananPembelian->id) }}" class="btn btn-outline-warning">Ubah</a>
        <button class="btn btn-outline-danger btn-hapus">Hapus</button>
        @if($pesananPembelian->approve_direktur == 1 || $pesananPembelian->approve_komisaris == 1)
        <a href="{{ route('pembelian.pesanan-pembelian.revisi-pengajuan',$pesananPembelian->id) }}" class="btn btn-outline-warning">Revisi data</a>
        @endif
        @endunless

        @if(Auth::user()->position == 'direktur')
        <button type="button" class="btn btn-outline-success btn-approve-direktur">Approve</button>
        <button type="button" class="btn btn-outline-danger btn-reject-direktur">Reject</button>
        @endif

        @if(Auth::user()->position == 'komisaris' && $pesananPembelian->grandtotal >= 5000000)
        <button type="button" class="btn btn-outline-success btn-approve-komisaris">Approve</button>
        <button type="button" class="btn btn-outline-danger btn-reject-komisaris">Reject</button>
        @endif
    </div>
</div>


<!-- Modal hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.pesanan-pembelian.destroy',$pesananPembelian->id) }}" method="post" style="width: 100%;">
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

@include('v2.pembelian.pesanan-pembelian.component.button-approve-konfirmasi')

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-approve-direktur').click(function(e) {
            $('#modalApproveDirektur').modal('show');
        });

        $('.btn-reject-direktur').click(function(e) {
            $('#modalRejectDirektur').modal('show');
        });

        $('.btn-approve-komisaris').click(function(e) {
            $('#modalApproveKomisaris').modal('show');
        });

        $('.btn-reject-komisaris').click(function(e) {
            $('#modalRejectKomisaris').modal('show');
        });

        $('.btn-hapus').click(function(e) {
            $('#modalHapus').modal('show');
        });
    });
</script>
@endsection