@extends('layouts.vuexy')

@section('header')
Customer Receipt (Pembayaran Pelanggan)
@endsection

@section('content')
<div class="card">
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ url('admin/cr/create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
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
                        <a class="dropdown-item" href="{{ route('admin.cr.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.cr.printPDF') }}">Print</a>
                    </div>
                </div>
                
            </div>
        </div>
        

        <table class="table table-hover table-bordered" id="cr-table">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nomer CR</th>
                    <th>Tanggal CR</th>
                    <th>Pelanggan</th>
                    <th>Jumlah Yang Dibayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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
        $('#cr-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.cr.index') }}",
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
                    data: 'nomer_cr',
                    name: 'nomer_cr'
                },
                {
                    data: 'tanggal_cr',
                    name: 'tanggal_cr'
                },
                {
                    data: 'nama_pelanggan',
                    name: 'nama_pelanggan'
                },
                {
                    data: 'total_payment',
                    name: 'total_payment'
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
                    "targets": 5,
                    orderable: false,
                    searchable: false
                }
            ],
            order: [0, 'desc'],
            drawCallback: function(settings) {
                feather.replace()
            }
        });
        $('#cr-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection