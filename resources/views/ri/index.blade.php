@extends('layouts.vuexy')

@section('header')
Receive Item (Penerimaan Barang)
@endsection
@section('content')
@if($errors->all())
@include('layouts.validation')
@elseif(session('success'))
@include('layouts.success')
@endif
<div class="card">
    <!-- <div class="card-header with-border">
        <a href="/admin/ri/create" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a>
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ url('admin/ri/create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
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
                        <a class="dropdown-item" href="{{ route('admin.ri.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.ri.printPDF') }}">Print</a>
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
            <table class="table-ri table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nomer Penerimaan Pesanan (RI)</th>
                        <th>Tanggal Penerimaan</th>
                        <th>Keterangan</th>
                        <th>Supplier</th>
                        <th>Nomer Pesanan (PO)</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ri as $ri)
                        <tr>
                            <td>{{ $ri->nomer_ri }}</td>
                            <td>{{ date('d-m-Y', strtotime($ri->tanggal_ri)) }}</td>
                            <td>{{ $ri->keterangan}}</td>
                            <td>{{ $ri->supplier->nama_supplier }}</td>
                            <td>{{ $ri->po->nomer_po }}</td>
                            <td>
                                <a href="/admin/ri/{{ $ri->id }}" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                     Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> -->

        <table class="table table-hover table-bordered" id="ri-table">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nomer Surat Jalan</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Keterangan</th>
                    <th>Supplier</th>
                    <th>Nomer Pesanan (PO)</th>
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
        $('.table-ri').DataTable()
    })
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#ri-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.ri.index') }}",
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
                    data: 'nomer_ri',
                    name: 'nomer_ri'
                },
                {
                    data: 'tanggal_ri',
                    name: 'tanggal_ri'
                    //   render : function (data,full ) { return '{{ date("d-M-Y", strtotime('data')) }}'; } 
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'nama_supplier',
                    name: 'nama_supplier'
                },
                {
                    data: 'nomer_po',
                    name: 'nomer_po'
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
                    "targets": 6,
                    orderable: false,
                    searchable: false
                }
            ],
            order: [0, 'desc'],
            drawCallback: function(settings) {
                feather.replace()
            }
        });
        $('#ri-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection