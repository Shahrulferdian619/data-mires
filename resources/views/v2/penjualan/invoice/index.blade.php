@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
    <ul>

    </ul>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Invoice Penjualan
            <a href="{{ route('invoice-penjualan.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i>
                Buat invoice manual
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
                <td style="background-color: #ffa1b7;">Belum lunas</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #98fc8b;">Sudah lunas</td>
            </tr>
        </table>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 150px;">no. inv.</th>
                    <th style="width: 150px;">no. ref</th>
                    <th style="width: 100px;">tgl.</th>
                    <th>market</th>
                    <th>pelanggan</th>
                    <th>nilai</th>
                    <th>terhutang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice as $i)
                <tr style="background-color: @if($i->status_proses == 0) #ffa1b7 @elseif($i->status_proses == 1) #98fc8b @endif;">
                    <td>
                        <a href="{{ route('invoice-penjualan.show',$i->id) }}">{{ $i->nomer_invoice_penjualan }}</a>
                    </td>
                    <td>
                        @if($i->pesanan == '')
                        {{ $i->nomer_ref }}
                        @else
                        {{ $i->pesanan->nomer_pesanan_penjualan }}
                        @endif
                    </td>
                    <td>{{ date('d/m/Y',strtotime($i->tanggal)) }}</td>
                    <td>{{ $i->jenis_penjualan }}</td>
                    <td>{{ $i->pelanggan->nama_pelanggan }}</td>
                    <td>{{ number_format($i->grandtotal_setelah_diskon,2) }}</td>
                    <td>{{ number_format($i->grandtotal_setelah_diskon - $i->sudah_terbayar,2) }}</td>
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

<!-- Modal Generate Invoice -->
<div class="modal fade" id="modalGenerateInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('invoice-penjualan.generate-invoice-manual') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Generate invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>no. so.</th>
                                <th>market</th>
                                <th>tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan_penjualan as $r)
                            <tr>
                                <td style="width: 50px;">
                                    <input name="pesanan_penjualan[]" class="form-check-input" type="checkbox" value="{{ $r->id }}" />
                                </td>
                                <td>{{ $r->nomer_pesanan_penjualan }}</td>
                                <td>{{ $r->jenis_penjualan }}</td>
                                <td>{{ date('d-m-Y', strtotime($r->tanggal)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-info">Generate</button>
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

        // button modal download PDF
        $('.btn-download-pdf').click(function(e) {
            $('#modalDownloadPDF').modal('show');
        });

        // button modal generate invoice
        $('.btn-generate-invoice').click(function(e) {
            $('#modalGenerateInvoice').modal('show');
        })
    });
</script>
@endsection