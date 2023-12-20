@extends('layouts.vuexy')

@section('header')
Mutation History (Histori Mutasi)
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif

<a href="{{ url('admin/mutation-inventory') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke mutasi
</a>
<hr>
<div class="card">
    <div class="card-body">

        <table class="table table-hover table-bordered" id="mutation-table">
            <thead>
            <tr class="text-center">
                <th>No.</th>
                <th>Nomor Mutasi</th>
                <th>Tanggal</th>
                <th>Gudang Asal</th>
                <th>Gudang Tujuan</th>
                <th>Jumlah Mutasi</th>
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
        $('.table-barang').DataTable()
    })
    $('#select').on('change', function () {
          var id = $(this).val(); // get selected value
          if (id) { 
              window.location = "{{ url('admin/list-inventory') }}/"+id; 
          }
          return false;
      });
</script>

<script>
      $.ajaxSetup({
        headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
      });

      $(document).ready(function() {
          $('#mutation-table').DataTable({
              processing: false,
              info: true,
              serverSide: true, //aktifkan server-side 
              ajax: {
                  url: "{{ url('/admin/mutation-history') }}",
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
                      data: 'nomor_mutasi',
                      name: 'nomor_mutasi'
                  },
                  {
                      data: 'tanggal',
                      name: 'tanggal'
                  },
                  {
                      data: 'gudang_asal',
                      name: 'gudang_asal'
                  },
                  {
                      data: 'gudang_tujuan',
                      name: 'gudang_tujuan'
                  },
                  {
                      data: 'jumlah_mutasi',
                      name: 'jumlah_mutasi'
                  }
              ],
              columnDefs: [
              {"width": "3%", "targets": 0, "className": "text-center", visible: false, searchable: false},
              {"width": "10%", "targets": 3, orderable: false, searchable: false}
  ],
              order: [0, 'desc'],
              drawCallback: function(settings) {
                  feather.replace()
              }
          });
          $('#inventory-table').parent().addClass('table-responsive');
      });

      $('table').on('draw.dt', function() {
          $('[data-toggle="tooltip"]').tooltip();
      })
  </script>
@endsection