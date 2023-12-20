@extends('layouts.vuexy')

@section('header')
Payment (Pembayaran)
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif
<div class="card">
    <!-- <div class="card-header with-border">
        <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-outline-primary">
            <i data-feather="plus"></i>
             Baru
        </a>
    </div> -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ route('admin.pembayaran.create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
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
                        <a class="dropdown-item" href="{{ route('admin.pembayaran.exportPDF') }}" >Export PDF</a>
                        <a class="dropdown-item btn-modal" type="button" data-bs-toggle="modal" data-bs-target="#filter-export" href="{{ route('admin.pembayaran.exportPDF') }}" >Export Excel</a>
                        <a class="dropdown-item" target="_blank" href="{{ route('admin.pembayaran.printPDF') }}">Print</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="pembayaran-table">
                <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Nomer</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Total Nominal</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade text-start" id="filter-export" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
            </div>
            <form action="{{ url('admin/pembayaran/export-excel-all') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tanggal Awal : </label>
                                <input type="date" class="form-control" name="start" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <label>Tanggal Akhir : </label>
                            <input type="date" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.datatables-new').DataTable(
			{
				paging: true, 
				searching: true,
				order: [],
				aaSorting: [],
			}
        );
        $('.btn-modal').on('click', function(e){
            e.preventDefault()
            let attr = $(this).attr('data-bs-target')
            $(attr).modal('show')
        })
    })
</script>

<script>
      $.ajaxSetup({
        headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
      });

      $(document).ready(function() {
          $('#pembayaran-table').DataTable({
              processing: false,
              info: true,
              serverSide: true, //aktifkan server-side
              ajax: {
                  url: "{{ route('admin.pembayaran.index') }}",
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
                      data: 'nomer',
                      name: 'nomer'
                  },
                  {
                      data: 'tanggal',
                      name: 'tanggal'
                  },
                  {
                      data: 'deskripsi',
                      name: 'deskripsi'
                  },
                  {
                      data: 'total_nominal',
                      name: 'total_nominal'
                  },
                  {
                      data: 'actions',
                      name: 'actions'
                  },
              ],
              columnDefs: [
              {"width": "5%", "targets": 0, "className": "text-center", visible: false, searchable: false},
              {"width": "8%", "targets": 5, orderable: false, searchable: false}
  ],
              order: [0, 'desc'],
              drawCallback: function(settings) {
                  feather.replace()
              }
          });
          $('#pembayaran-table').parent().addClass('table-responsive');
      });

      $('table').on('draw.dt', function() {
          $('[data-toggle="tooltip"]').tooltip();
      })
  </script>
@endsection