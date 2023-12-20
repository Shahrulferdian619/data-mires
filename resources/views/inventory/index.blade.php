@extends('layouts.vuexy')

@section('header')
List Inventory (Daftar Inventory)
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
            <select class="form-control mb-1" id="select" name="type" required>
                <option value=""> - Filter Tipe - </option>
                <option <?php if($type == 'all'){ echo 'selected'; } ?> value="all">All</option>
                <option <?php if($type == 1){ echo 'selected'; } ?> value="1">Produk</option>
                <option <?php if($type == 2){ echo 'selected'; } ?> value="2">Asset</option>
                <option <?php if($type == 3){ echo 'selected'; } ?> value="3">Jasa</option>
                <option <?php if($type == 4){ echo 'selected'; } ?> value="4">Lainnya</option>
            </select>
        <a href="{{ url('admin/list-inventory/menu/stock-in') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
            <!-- <i class="fa fa-plus mr-1"></i> -->
            <i data-feather="plus"></i>
            Baru
        </a>
        </div>
        <div class="col-md-9 form-group d-flex justify-content-end">
               
        </div>
    </div>
        <table class="table table-hover table-bordered" id="inventory-table">
            <thead>
            <tr class="text-center">
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Balance Stock</th>
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
          $('#inventory-table').DataTable({
              processing: false,
              info: true,
              serverSide: true, //aktifkan server-side 
              ajax: {
                  url: "{{ url('/admin/list-inventory/'.$type) }}",
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
                      data: 'nama_barang',
                      name: 'nama_barang'
                  },
                  {
                      data: 'balance_stok',
                      name: 'balance_stok'
                  },
                  {
                      data: 'actions',
                      name: 'actions'
                  },
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