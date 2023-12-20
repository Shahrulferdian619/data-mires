@extends('layouts.vuexy')

@section('header')
Report Purchase | Order Per Vendor
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12 mb-1">
        <a href="{{ url('admin/report-purchase') }}"><i data-feather='arrow-left-circle'></i> Kembali ke daftar laporan</a>
    </div>
    <div class="col-lg-12 col-12">
        <div class="card">
            <div class="card-body">
                <div class="form-modal-ex">
                    <button type="button" class="btn btn-outline-primary" id="btn-modal" data-bs-toggle="modal" data-bs-target="#filter">
                        Filter Data
                    </button>
                    <!-- Modal -->
                    <div class="modal fade text-start" id="filter" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                </div>
                                <form action="{{ url('admin/report-purchase/order-per-vendor') }}" method="GET">
                                    <div class="modal-body">
                                        <label for="">Pilih Supplier</label>
                                        <select name="supplier_id" id="supplier_id" class="form-control">
                                            @foreach ($vendor as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_supplier }}</option>
                                            @endforeach
                                        </select>
    
                                        <label>Tanggal Awal : </label>
                                        <input type="date" class="form-control" name="start" value="{{ date('Y-m-d') }}">

                                        <label>Tanggal Akhir : </label>
                                        <input type="date" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table-dashboard table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nomor Order</th>
                                <th>Tanggal</th>
                                <th>Termin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $item)
                            <tr>
                                <td> {{ $item->nomer_fakturpembelian }} </td>
                                <td> {{ $item->tanggal }} </td>
                                <td> {{ $item->termin }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-dashboard').DataTable()
    })
    $('#btn-modal').on('click', function(){
        $('#filter').modal('show')
    })
</script>
@endsection