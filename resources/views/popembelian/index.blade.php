@extends('layouts.vuexy')

@section('header')
Purchase Order (Pesanan Pembelian)
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@elseif(session('success'))
@include('layouts.success')
@endif
<div class="card">
    <!-- <div class="card-header with-border">
        <a href="/admin/po/create" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a> -->
    <div class="card-body">

        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ url('admin/po/create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
                    <!-- <i class="fa fa-plus mr-1"></i> -->
                    <i data-feather="plus"></i>
                    Baru
                </a>
            </div>
            <div class="col-md-9 form-group d-flex justify-content-end">
                <!-- <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">
                                                            <i data-feather="edit-2" class="me-50"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i data-feather="trash" class="me-50"></i>
                                                            <span>Delete</span>
                                                        </a>
                                                    </div>
                                                </div> -->
                <div class="btn-group dropleft">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-toggle="dropdown" aria-expanded="false">
                        <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.popembelian.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.popembelian.printPDF') }}">Print</a>
                    </div>
                </div>
                <!-- <div class="btn-group mr-1">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-file-excel-o mr-1"></i>    
                    Excel
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('admin.kategoribarang.exportExcel', 'csv') }}">Export .csv</a>
                        <a class="dropdown-item" href="{{ route('admin.kategoribarang.exportExcel', 'xls') }}">Export .xls</a>
                        <a class="dropdown-item" href="{{ route('admin.kategoribarang.exportExcel', 'xlsx') }}">Export .xlsx</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" data-toggle="modal" data-target="#importExcel"  data-toggle="tooltip" data-placement="right" title="Tipe file xlsx,xls,csv" href="javascript:void(0);">Import Excel</a>
                    </div>
                </div>
            <button type="button" class="btn btn-outline-danger waves-effect waves-float waves-light"><i class="fa fa-trash mr-1"></i>Sampah</button> -->
            </div>
        </div>
        <!-- <div class="table-responsive">
            <table class="table-popembelian table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nomer(PO) - PMT</th>
                        <th>Approval</th>
                        <th>Barang</th>
                        <th>Faktur</th>
                        <th>Nilai</th>
                        <th>Pembayaran</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($popembelian as $po)
                            <tr>
                                <td>{{ $po->nomer_po.' - '.$po->pmtpembelian->nomer_pmtpembelian }}</td>
                                <td>
                                    @if($po->approve_direktur == 0)
                                        <div style="width:150px" class="badge badge-light-warning">Menunggu Direktur</div>
                                    @elseif($po->approve_direktur == 1)
                                        <div style="width:150px" class="badge badge-light-success">Approve Direktur</div>
                                    @elseif($po->approve_direktur == 2)
                                        <div style="width:150px" class="badge badge-light-danger">Reject Direktur</div>
                                    @endif
                                    @if($po->nilai_po > 5000000)
                                        @if($po->approve_komisaris == 0)
                                            <div style="width:150px" class="badge badge-light-warning">Menunggu Komisaris</div>
                                        @elseif($po->approve_komisaris == 1)
                                            <div style="width:150px" class="badge badge-light-success">Approve Komisaris</div>
                                        @elseif($po->approve_komisaris == 2)
                                            <div style="width:150px" class="badge badge-light-danger">Reject Komisaris</div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($po->approve_direktur == 1 && $po->approve_komisaris == 1)
                                        @if($po->status == 1)
                                            <div style="width:150px" class="badge badge-light-warning">Diterima Sebagian</div>
                                        @elseif($po->status == 2)
                                            <div style="width:150px" class="badge badge-light-success">Diterima</div>
                                        @else
                                            <div style="width:150px" class="badge badge-light-danger">Belum Diterima</div>
                                        @endif
                                    @else
                                        <div style="width:150px" class="badge badge-light-danger">Menunggu</div>
                                    @endif
                                </td>
                                <td>
                                        @if($po->status_faktur == 1)
                                            <div style="width:150px" class="badge badge-light-warning">Sebagian</div>
                                        @elseif($po->status_faktur == 2)
                                            <div style="width:150px" class="badge badge-light-success">Lengkap</div>
                                        @else
                                            <div style="width:150px" class="badge badge-light-danger">Belum Dibuat</div>
                                        @endif
                                </td>
                                <td>
                                    @if($po->is_tax == 1)
                                        Rp.{{ number_format($po->nilai_po + $po->nilai_po * 10 / 100) }}
                                    @else
                                        Rp.{{ number_format($po->nilai_po) }}
                                    @endif    
                                 </td>
                                <td>Rp.{{ number_format($po->payment) }}</td>
                                <td>
                                    <a href="/admin/po/{{ $po->id }}" class="badge badge-light-secondary">
                                        <i data-feather="eye"></i>
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </div> -->

        <table class="table table-hover table-bordered" id="popembelian-table">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nomer(PO) - PMT</th>
                    <th>Item</th>
                    <th>Approval</th>
                    <th>Penerimaan</th>
                    <th>Faktur</th>
                    <th>Nilai</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</div>

</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-popembelian').DataTable()
    })
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#popembelian-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.po.index') }}",
                type: 'GET'
            },
            "pageLength": 50,
            "aLengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                // {data:'id', name:'id'},
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'nomer_popembelian',
                    name: 'nomer_popembelian'
                },
                {
                    data: 'item',
                    name: 'item'
                },
                {
                    data: 'approval',
                    name: 'approval'
                    //   render : function (data,full ) { return '{{ date("d-M-Y", strtotime('data')) }}'; } 
                },
                {
                    data: 'barang',
                    name: 'barang'
                },
                {
                    data: 'faktur',
                    name: 'faktur'
                },
                {
                    data: 'nilai_po',
                    name: 'nilai_po'
                },
                {
                    data: 'payment',
                    name: 'payment'
                },
                {
                    data: 'actions',
                    name: 'actions'
                },
            ],
            columnDefs: [{
                    "width": "5%",
                    "targets": 0,
                    "className": "text-center",
                    visible: false,
                    searchable: false
                },
                {
                    "width": "8%",
                    "targets": 7,
                    orderable: false,
                    searchable: false
                }
            ],
            order: [0, 'desc'],
            drawCallback: function(settings) {
                feather.replace()
            }
        });
        $('#popembelian-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection