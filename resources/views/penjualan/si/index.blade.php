@extends('layouts.vuexy')

@section('header')
Sales Invoice (Tagihan Penjualan)
@endsection

@section('content')
<div class="card">

    <div class="card-body">
        @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Success !</h4>
            <div class="alert-body">
                <ul>
                    <li>{{ session()->get('success') }}</li>
                </ul>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ url('admin/si/create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
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
                        <a class="dropdown-item" href="{{ route('admin.si.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.si.printPDF') }}">Print</a>
                    </div>
                </div></div>
        </div>

        <table class="table table-hover table-bordered" id="si-table">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>No. SO</th>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tgl.</th>
                    <th>Jns. Penjualan</th>
                    <th>Status</th>
                    <th>#</th>
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
        $('#tabel').DataTable()
    })
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#si-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.si.index') }}",
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
                    data: 'so_nomer',
                    name: 'so_nomer'
                },
                {
                    data: 'no_pesanan',
                    name: 'no_pesanan'
                },
                {
                    data: 'pelanggan',
                    name: 'pelanggan'
                },
                {
                    data: 'so_tanggal',
                    name: 'so_tanggal'
                },
                {
                    data: 'jenis_penjualan',
                    name: 'jenis_penjualan'
                },
                {
                    data: 'status_invoice',
                    name: 'status_invoice'
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
                //   {"targets": 2, "render": $.fn.dataTable.render.number( '.', '.', 0, 'Rp. ' )},
                {
                    "width": "8%",
                    "targets": 4,
                    orderable: false,
                    searchable: false
                }
            ],
            order: [0, 'desc'],
            drawCallback: function(settings) {
                feather.replace()
            }
        });
        $('#si-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection