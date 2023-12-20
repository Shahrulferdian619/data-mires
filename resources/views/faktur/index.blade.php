@extends('layouts.vuexy')

@section('header')
Purchase Invoice (Tagihan Pembelian)
@endsection
@section('content')
@if($errors->all())
@include('layouts.validation')
@elseif(session('success'))
@include('layouts.success')
@endif
<div class="card">
    <!-- <div class="card-header with-border">
        <a href="/admin/fakturpembelian/create" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a>
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ url('admin/fakturpembelian/create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
                    <!-- <i class="fa fa-plus mr-1"></i> -->
                    <i data-feather="plus"></i>
                    Baru
                </a>
            </div>
            <div class="col-md-9 form-group d-flex justify-content-end">
                <div class="btn-group dropleft">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-toggle="dropdown" aria-expanded="false">
                        <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.fakturpembelian.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.fakturpembelian.printPDF') }}">Print</a>
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
            <table class="table-faktur table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nomer Faktur</th>
                        <th>Tanggal Buat</th>
                        <th>Approval</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faktur as $fakturpembelian)
                        <tr>
                            <td>{{ $fakturpembelian->nomer_fakturpembelian }}</td>
                            <td>{{ date('d-m-Y', strtotime($fakturpembelian->tanggal_faktur)) }}</td>
                            <td>
                                @if($fakturpembelian->approve_direktur == 0)
                                    <div style="width:150px" class="badge badge-light-warning">Menunggu Direktur</div>
                                @elseif($fakturpembelian->approve_direktur == 1)
                                    <div style="width:150px" class="badge badge-light-success">Approve Direktur</div>
                                @elseif($fakturpembelian->approve_direktur == 2)
                                    <div style="width:150px" class="badge badge-light-danger">Reject Direktur</div>
                                @endif
                                @if($fakturpembelian->relation->sum('total_perfaktur') > 5000000)
                                    @if($fakturpembelian->approve_komisaris == 0)
                                        <div style="width:150px" class="badge badge-light-warning">Menunggu Komisaris</div>
                                    @elseif($fakturpembelian->approve_komisaris == 1)
                                        <div style="width:150px" class="badge badge-light-success">Approve Komisaris</div>
                                    @elseif($fakturpembelian->approve_komisaris == 2)
                                        <div style="width:150px" class="badge badge-light-danger">Reject Komisaris</div>
                                    @endif
                                @endif
                            </td>
                            <td>Rp.{{ number_format($fakturpembelian->relation->sum('total_perfaktur')) }}</td>
                            <td>
                                @if($fakturpembelian->is_payment == 0)
                                <div class="badge badge-light-danger">Belum dibayar</div>
                                @elseif($fakturpembelian->is_payment == 1)
                                <div class="badge badge-light-success">Sudah dibayar</div>
                                @endif
                            </td>
                            <td>@if(!empty($fakturpembelian->termin)) {{ $fakturpembelian->termin }} Hari @else - @endif</td>
                            <td>
                                <a href="/admin/fakturpembelian/{{ $fakturpembelian->id }}" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                     Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> -->

        <table class="table table-hover table-bordered" id="faktur-table">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nomer Faktur</th>
                    <th>Tanggal Buat</th>
                    <th>Approval Owner</th>
                    <th>Total Tagihan</th>
                    <th>Status</th>
                    <th>Jatuh Tempo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-faktur').DataTable()
    })
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#faktur-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.fakturpembelian.index') }}",
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
                    data: 'nomer_fakturpembelian',
                    name: 'nomer_fakturpembelian'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'approval',
                    name: 'approval'
                },
                {
                    data: 'total_perfaktur',
                    name: 'total_perfaktur'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'termin',
                    name: 'termin'
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
        $('#faktur-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection