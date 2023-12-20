@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Invoice Pembelian
            <a href="{{ route('pembelian.invoice-pembelian.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>

            <button class="btn btn-sm btn-outline-success btn-download-excel">
                <i class="fa fa-download"></i>
                Download Excel
            </button>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 150px;">no.</th>
                    <th style="width: 150px;">no. po</th>
                    <th style="width: 100px;">tgl.</th>
                    <th style="width: 150px;">status</th>
                    <th>item</th>
                    <th>keterangan</th>
                    <th>nominal</th>
                    <th>sudah terbayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['invoicePembelian'] as $invoicePembelian)
                <tr>
                    <td>
                        <a href="{{ route('pembelian.invoice-pembelian.edit',$invoicePembelian->id) }}">{{ $invoicePembelian->nomer_invoice_pembelian }}</a>
                    </td>
                    <td>
                        {{ $invoicePembelian->pesananPembelian->nomer_pesanan_pembelian }}
                    </td>
                    <td>
                        {{ date('d/m/Y',strtotime($invoicePembelian->tanggal)) }}
                    </td>
                    <td>
                        @if($invoicePembelian->grandtotal != $invoicePembelian->sudah_terbayar)
                        <span class="badge bg-label-warning">Belum lunas</span>
                        @else
                        <span class="badge bg-label-success">Sudah lunas</span>
                        @endif
                    </td>
                    <td>
                        @foreach($invoicePembelian->rincianItem as $item)
                        <small class="badge bg-label-primary">{{$item->item->nama_barang}} {{$item->deskripsi_item}}</small>,
                        @endforeach
                    </td>
                    <td>
                        {{ $invoicePembelian->keterangan }}
                    </td>
                    <td>
                        {{ number_format($invoicePembelian->grandtotal,2) }}
                    </td>
                    <td>
                        {{ number_format($invoicePembelian->sudah_terbayar,2) }}
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