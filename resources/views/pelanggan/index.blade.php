@extends('layouts.vuexy')

@section('header')
Customers ( Pelanggan )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif

<div class="card">
    <!-- <div class="card-header with-border">
        <a href="/admin/pelanggan/create" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a>
    </div> -->
    <div class="card-body">
    <div class="row">
    <div class="col-md-3 form-group">
                                <a href="/admin/pelanggan/create" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
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
                                        <a class="dropdown-item" href="{{ route('admin.pelanggan.exportPDF') }}" >Export PDF</a>
                                        <a class="dropdown-item" target="_blank" href="{{ route('admin.pelanggan.printPDF') }}">Print</a>
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
            <table class="table-pelanggan table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Tipe Pelanggan</th>
                        <th>Handphone Pelanggan</th>
                        <th>Email Pelanggan</th>
                        <th>Region</th>
                        <th>Detail Alamat</th>
                        <th>Deskripsi Pelanggan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggan as $plgn)
                        <tr>
                            <td>{{ $plgn->kode_pelanggan }}</td>
                            <td>{{ $plgn->nama_pelanggan }}</td>
                            <td>{{ $plgn->tipepelanggan->tipepelanggan }}</td>
                            <td>{{ $plgn->handphone_pelanggan }}</td>
                            <td>{{ $plgn->email_pelanggan }}</td>
                            <td>{{ $plgn->negara }}, {{ $plgn->provinsi }}, {{ $plgn->kota }}, {{ $plgn->kecamatan }}</td>
                            <td>{{ $plgn->detail_alamat}}</td>
                            <td>{{ $plgn->deskripsi_pelanggan}}</td>
                            <td>
                                <a href="/admin/pelanggan/{{ $plgn->id }}" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> -->
        <table class="table table-hover table-bordered" id="pelanggan-table">
            <thead>
            <tr class="text-center">
                <th></th>
                <th>Kode Pelanggan</th>
                <th>Kode Area</th>
                <th>Nama Pelanggan</th>
                <th>Tipe Pelanggan</th>
                <th>Handphone Pelanggan</th>
                <th>Email Pelanggan</th>
                <th>Region</th>
                <th>Detail Alamat</th>
                <th>Deskripsi Pelanggan</th>
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
        $('.table-pelanggan').DataTable()
    })
</script>

<script>
      $.ajaxSetup({
        headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
      });

      $(document).ready(function() {
          $('#pelanggan-table').DataTable({
              processing: false,
              info: true,
              serverSide: true, //aktifkan server-side 
              ajax: {
                  url: "{{ route('admin.pelanggan.index') }}",
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
                      data: 'kode_pelanggan',
                      name: 'kode_pelanggan'
                  },
                  {
                      data: 'kode_area',
                      name: 'kode_area'
                  },
                  {
                      data: 'nama_pelanggan',
                      name: 'nama_pelanggan'
                  },
                  {
                      data: 'tipepelanggan',
                      name: 'tipepelanggan'
                    //   render: function ( data, type, row ) {
                    //   return data + " %";},
                  },
                  {
                      data: 'handphone_pelanggan',
                      name: 'handphone_pelanggan'
                  },
                  {
                      data: 'email_pelanggan',
                      name: 'email_pelanggan'
                  },
                  {
                      data: 'alamat',
                      name: 'alamat'
                  },
                  {
                      data: 'detail_alamat',
                      name: 'detail_alamat'
                  },
                  {
                      data: 'deskripsi_pelanggan',
                      name: 'deskripsi_pelanggan'
                  },
                  {
                      data: 'actions',
                      name: 'actions'
                  },
              ],
              columnDefs: [
              {"width": "5%", "targets": 0, "className": "text-center", visible: false, searchable: false},
            //   {"targets": 2, "render": $.fn.dataTable.render.number( '.', '.', 0, 'Rp. ' )},
              {"width": "8%", "targets": 9, orderable: false, searchable: false}
  ],
              order: [0, 'desc'],
              drawCallback: function(settings) {
                  feather.replace()
              }
          });
          $('#pelanggan-table').parent().addClass('table-responsive');
      });

      $('table').on('draw.dt', function() {
          $('[data-toggle="tooltip"]').tooltip();
      })
  </script>
@endsection