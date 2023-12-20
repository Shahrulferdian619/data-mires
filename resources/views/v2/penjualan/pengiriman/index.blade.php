@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .ukuran-font-tabel td {
        padding: 0.1rem;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
    <ul>
        <li>
            Untuk melakukan proses pengiriman barang konsinyasi, sudah tidak dapat melalui menu ini,
            silahkan masuk ke menu Permintaan Konsinyasi
        </li>
        <li>Fitur download excel sudah bisa digunakan</li>
    </ul>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Pengiriman Penjualan
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
                <td style="background-color: #ffa1b7;">Belum diproses</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #fcd88b;">Sudah diproses/dikirim</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #98fc8b;">Sudah selesai</td>
            </tr>
        </table>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt" width="100%">
            <thead>
                <tr>
                    <th>no so.</th>
                    <th>tgl so.</th>
                    <th>no. do.</th>
                    <th>tgl do.</th>
                    <th>market</th>
                    <th>pesanan</th>
                    <th>pelanggan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan as $row)
                <tr style="background-color: @if($row->status_proses == 0) #ffa1b7 @elseif($row->status_proses == 1) #fcd88b @else #98fc8b @endif;">
                    <td>
                        <a href="{{ route('pengiriman-penjualan.show',$row->id) }}">
                            {{ $row->nomer_pesanan_penjualan }}
                        </a>
                    </td>
                    <td>{{ date('d/m/Y',strtotime($row->tanggal)) }}</td>
                    <td>
                        @if($row->pengiriman != null)
                        {{ $row->pengiriman->nomer_pengiriman_penjualan }}
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if($row->pengiriman != null)
                        {{ date('d/m/Y',strtotime($row->pengiriman->tanggal)) }}
                        @else
                        -
                        @endif
                    </td>
                    <td>{{ $row->jenis_penjualan }}</td>
                    <td>{{ $row->nomer_pesanan }}</td>
                    <td>{{ $row->pelanggan->nama_pelanggan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Download Excel -->
<div class="modal fade" id="modalDownloadExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pengiriman-penjualan.download-excel') }}" method="post">
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

<!-- Modal Download PDF -->
<div class="modal fade" id="modalDownloadPDF" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pesanan-penjualan.index') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Download PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

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
            'pageLength': 100,
            columnDefs: [{
                'width': '100px',
                'targets': 0,
            }],
        });
        // button modal download Excel
        $('.btn-download-excel').click(function(e) {
            $('#modalDownloadExcel').modal('show');
        });

        // button modal download PDF
        $('.btn-download-pdf').click(function(e) {
            $('#modalDownloadPDF').modal('show');
        });

        // button modal download PDF
    });
</script>
@endsection