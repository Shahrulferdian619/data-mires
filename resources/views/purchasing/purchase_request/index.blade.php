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
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <div id="tambahPmt" class="btn btn-outline-primary waves-effect waves-float waves-light">
                    <i data-feather="plus"></i>
                    Baru
                </div>
            </div>
        </div>

        <table class="table table-hover table-bordered" id="pmtpembelian-table">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>no.pr</th>
                    <th>tgl</th>
                    <th>status</th>
                    <th>ket</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
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