@extends('layouts.vuexy')

@section('header')
Purchase Request (Permintaan Pembelian)
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@elseif(session('success'))
@include('layouts.success')
@endif
<div class="card">
    <!-- <div class="card-header with-border">
        <a href="/admin/pmtpembelian/create" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a>
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <div id="tambahPmt" class="btn btn-outline-primary waves-effect waves-float waves-light">
                    <!-- <i class="fa fa-plus mr-1"></i> -->
                    <i data-feather="plus"></i>
                    Baru
                </div>
            </div>
            <div class="col-md-9 form-group d-flex justify-content-end">
                <div class="btn-group dropleft">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-toggle="dropdown" aria-expanded="false">
                        <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.pmtpembelian.exportPDF') }}">Export PDF</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.pmtpembelian.printPDF') }}">Print</a>
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
            <table class="table-pmtpembelian table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nomer permintaan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pmtpembelians as $pmtpembelian)
                        <tr>
                        <td>{{ $pmtpembelian->nomer_pmtpembelian }}</td>
                        <td>{{ date('d-m-Y', strtotime($pmtpembelian->tanggal)) }}</td>
                        <td>
                            @if($pmtpembelian->approve_direktur == 0)
                            <div class="badge badge-light-warning">Belum disetujui</div>
                            @elseif($pmtpembelian->approve_direktur ==1)
                            <div class="badge badge-light-success">Sudah disetujui</div>
                            @elseif($pmtpembelian->approve_direktur == 2)
                            <div class="badge badge-light-danger">Tidak Disetujui</div>
                            @endif
                        </td>
                        <td>{{ $pmtpembelian->keterangan }}</td>
                        <td>
                            <a href="/admin/pmtpembelian/{{ $pmtpembelian->id }}" class="badge badge-light-secondary">
                                <i data-feather="eye"></i>
                                 Lihat
                            </a>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> -->

        <table class="table table-hover table-bordered" id="pmtpembelian-table">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nomor Permintaan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</div>


<div class="modal fade" id="tipeBarang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="text-center m-1">Pilih Tipe Barang Yang Akan Diajukan! <span id="msg"></span></h3>
                <a href="{{ url('admin/pmtpembelian/create/2') }}" style="width:100%" class="mb-1 btn btn-success">ASSET</a><br>
                <a href="{{ url('admin/pmtpembelian/create/1') }}" style="width:100%" class="mb-1 btn btn-info">PRODUK</a><br>
                <a href="{{ url('admin/pmtpembelian/create/3') }}" style="width:100%" class="mb-1 btn btn-warning">JASA</a>
                <a href="{{ url('admin/pmtpembelian/create/4') }}" style="width:100%" class="mb-1 btn btn-danger">LAINNYA</a>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-pmtpembelian').DataTable()

        $('#tambahPmt').on('click', function() {
            $('#tipeBarang').modal('show');
        });
    })
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('#pmtpembelian-table').DataTable({
            processing: false,
            info: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('admin.pmtpembelian.index') }}",
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
                    data: 'nomer_pmtpembelian',
                    name: 'nomer_pmtpembelian'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                    //   render : function (data,full ) { return '{{ date("d-M-Y", strtotime('data')) }}'; } 
                },
                {
                    data: 'approve_direktur',
                    name: 'approve_direktur'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
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
        $('#pmtpembelian-table').parent().addClass('table-responsive');
    });

    $('table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
@endsection