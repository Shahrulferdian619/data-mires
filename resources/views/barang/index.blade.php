@extends('layouts.vuexy')

@section('header')
Items ( Barang )
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@elseif(session('success'))
@include('layouts.success')
@endif

<div class="card">
    <!-- <div class="card-header with-border">
        <a href="{{ route('admin.barang.create') }}" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a>
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ route('admin.barang.create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
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
                        <a class="dropdown-item" href="{{ route('admin.barang.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.barang.printPDF') }}">Print</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-barang table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Nama Kategori Barang</th>
                        <th>Deskripsi Barang</th>
                        <th>Satuan Barang</th>
                        <th>Harga Barang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $brg)
                        <tr>
                            <td>{{ $brg->kode_barang }}</td>
                            <td>{{ $brg->nama_barang }}</td>
                            <td>{{ $brg->kategoribarang->nama_kategori }}</td>
                            <td>{{ $brg->deskripsi_barang }}</td>
                            <td>{{ $brg->satuan_barang }}</td>
                            <td>@currency($brg->harga_barang1)</td>
                            <td>
                                <a href="/admin/barang/{{ $brg->id }}" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-barang').DataTable()
    })
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#barang-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.barang.index') }}",
                type: 'GET'
            },
            "pageLength": 10,
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
                    data: 'kode_barang',
                    name: 'kode_barang'
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang'
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori'
                },
                {
                    data: 'deskripsi_barang',
                    name: 'deskripsi_barang'
                },
                {
                    data: 'satuan_barang',
                    name: 'satuan_barang'
                },
                {
                    data: 'harga_barang1',
                    name: 'harga_barang1'
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
                //   {"targets": 6, "render": $.fn.dataTable.render.number( '.', '.', 0, 'Rp. ' )},
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
        $('#barang-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection