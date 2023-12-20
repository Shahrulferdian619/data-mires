@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
    <ul>
        <li>Untuk melihat data lama silahkan kembali ke versi 1</li>
        <li>Fitur download excel masih belum bisa</li>
    </ul>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Permintaan Pembelian
            <a href="{{ route('pembelian.permintaan-pembelian.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>

            <button class="btn btn-sm btn-outline-success btn-download-excel">
                <i class="fa fa-download"></i>
                Download Excel
            </button>
        </h5>
    </div>

    <div class="col-md-4">
        <table class="table table-bordered"> 
            <tr>
                <td colspan="2" style="background-color: #8bd6fc;">Keterangan warna tabel</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #ffa1b7;">Dibatalkan/tidak disetujui/ditutup</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #fcd88b;">Belum diproses</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #98fc8b;">Sudah diproses</td>
            </tr>
        </table>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 150px;">no.</th>
                    <th style="width: 100px;">tgl.</th>
                    <th style="width: 150px;">status</th>
                    <th>item</th>
                    <th>keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permintaan as $p)
                <tr style="background-color: @if($p->status_proses == 0) #fcd88b @elseif($p->status_proses == 1) #98fc8b @elseif($p->status_proses == 10) #ffa1b7 @endif;">
                    <td>
                        <a href="{{ route('pembelian.permintaan-pembelian.show',$p->id) }}">
                            {{$p->nomer_permintaan_pembelian}}
                        </a>
                    </td>
                    <td>
                        {{date('d/m/Y',strtotime($p->tanggal))}}
                    </td>
                    <td>
                        @if($p->approve_direktur == 0)
                        <span class="badge bg-label-warning">Belum diapprove</span>
                        @elseif($p->approve_direktur == 1)
                        <span class="badge bg-label-success">Sudah diapprove</span>
                        @elseif($p->approve_direktur == 2)
                        <span class="badge bg-label-danger">Tidak diapprove</span>
                        @endif
                    </td>
                    <td>
                        @foreach($p->rincianPermintaan as $rinci)
                        <small class="bg-label-primary">{{$rinci->item->nama_barang}} {{$rinci->deskripsi_item}}</small>,
                        @endforeach
                    </td>
                    <td>
                        {{$p->keterangan}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Download Excel -->
<div class="modal fade" id="modalDownloadExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pesanan-penjualan.download-excel') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Download Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Dari tanggal</label>
                    <input name="dari_tanggal" type="date" class="form-control" required>

                    <label for="">Sampai tanggal</label>
                    <input name="sampai_tanggal" type="date" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-info btn-tambah-paket">Download</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tabel_dt').DataTable({
            'ordering': false,
            'pageLength': 100
        });
        // button modal download Excel
        $('.btn-download-excel').click(function(e) {
            $('#modalDownloadExcel').modal('show');
        });
    });
</script>
@endsection