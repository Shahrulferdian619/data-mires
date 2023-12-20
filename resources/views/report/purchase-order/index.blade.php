@extends('layouts.vuexy')

@section('header')
Report Purchase
@endsection

@section('content')
<div class="row match-height">
    <div class="col-lg-12 col-12">
        <div class="card card-transaction">
            <div class="card-body">
                <h6>Vendor</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-info rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="user" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pembelian Per Vendor</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-per-vendor') }}" type="button" data-bs-toggle="modal" data-bs-target="#filter-1" class="badge badge-light-primary btn-modal" >
                                    Lihat Detail
                                </a>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade text-start" id="filter-1" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                        </div>
                                        <form action="{{ url('admin/report-purchase/order-per-vendor') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="">Pilih Supplier</label>
                                                    <select name="supplier_id[]" class="select2 form-control" multiple required>
                                                        @foreach ($vendor as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama_supplier }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" name="all_supplier" id="all_supplier" class="custom-control-input">
                                                        <label for="all_supplier" class="custom-control-label" >Semua Supplier</label>
                                                    </div>
                                                </div> --}}
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
                                                <div class="form-group">
                                                    <div class="demo-inline-spacing">
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_week_ago" value="7" class="custom-control-input">
                                                            <label for="a_week_ago" class="custom-control-label" >1 Minggu yang lalu</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_month_ago" value="30" class="custom-control-input">
                                                            <label for="a_month_ago" class="custom-control-label" >1 Bulan yang lalu (30)</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_year_ago" value="365" class="custom-control-input">
                                                            <label for="a_year_ago" class="custom-control-label" >1 Tahun yang lalu (365)</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="two_year_ago" value="730" class="custom-control-input">
                                                            <label for="two_year_ago" class="custom-control-label" >2 Tahun yang lalu (730)</label>
                                                        </div>
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
                        </div>
                    </div>
                </div>
                <hr>
                <h6>Barang</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-info rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="package" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pembelian Per Barang</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-per-item') }}" class="badge badge-light-primary btn-modal" type="button" data-bs-toggle="modal" data-bs-target="#filter-4" >Lihat Detail</a>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade text-start" id="filter-4" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                        </div>
                                        <form action="{{ url('admin/report-purchase/order-per-item') }}" method="POST">
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
                                                        <div class="form-group">
                                                            <label>Tanggal Akhir : </label>
                                                            <input type="date" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Berdasarkan waktu</label>
                                                    <div class="demo-inline-spacing">
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_week_ago_per_item" value="7" class="custom-control-input">
                                                            <label for="a_week_ago_per_item" class="custom-control-label" >1 Minggu yang lalu</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_month_ago_per_item" value="30" class="custom-control-input">
                                                            <label for="a_month_ago_per_item" class="custom-control-label" >1 Bulan yang lalu (30)</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_year_ago_per_item" value="365" class="custom-control-input">
                                                            <label for="a_year_ago_per_item" class="custom-control-label" >1 Tahun yang lalu (365)</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="two_year_ago_filter_time" value="730" class="custom-control-input">
                                                            <label for="two_year_ago_filter_time" class="custom-control-label" >2 Tahun yang lalu (730)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Berdasarkan Kolom</label>
                                                    <div class="demo-inline-spacing">
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_column" id="all_per_item" value="all" checked class="custom-control-input">
                                                            <label for="all_per_item" class="custom-control-label" >Semua</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_column" id="quantity_per_item" value="qty" class="custom-control-input">
                                                            <label for="quantity_per_item" class="custom-control-label" >Total Jumlah Kuantitas Yang Dibeli</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_column" id="price_per_item" value="price" class="custom-control-input">
                                                            <label for="price_per_item" class="custom-control-label" >Total Harga Yang Dibeli</label>
                                                        </div>
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
                        </div>
                    </div>
                </div>
                <hr>
                <h6>Pembayaran</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-info rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pembayaran</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/payment') }}" type="button" data-bs-toggle="modal" data-bs-target="#filter-5" class="badge badge-light-primary btn-modal" >
                                    Lihat Detail
                                </a>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade text-start" id="filter-5" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                        </div>
                                        <form action="{{ url('admin/report-purchase/payment') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="">Pilih Supplier</label>
                                                    <select name="supplier_id[]" class="select2 form-control" multiple required>
                                                        @foreach ($vendor as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama_supplier }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Tanggal Awal : </label>
                                                            <input type="date" class="form-control" name="start" value="{{ date('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Tanggal Akhir : </label>
                                                            <input type="date" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Berdasarkan waktu</label>
                                                    <div class="demo-inline-spacing">
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_week_ago_per_payment" value="7" class="custom-control-input">
                                                            <label for="a_week_ago_per_payment" class="custom-control-label" >1 Minggu yang lalu</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_month_ago_per_payment" value="30" class="custom-control-input">
                                                            <label for="a_month_ago_per_payment" class="custom-control-label" >1 Bulan yang lalu (30)</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="a_year_ago_per_payment" value="365" class="custom-control-input">
                                                            <label for="a_year_ago_per_payment" class="custom-control-label" >1 Tahun yang lalu (365)</label>
                                                        </div>
                                                        <div class="custom-radio custom-control">
                                                            <input type="radio" name="filter_time" id="two_year_ago_per_payment" value="730" class="custom-control-input">
                                                            <label for="two_year_ago_per_payment" class="custom-control-label" >2 Tahun yang lalu (730)</label>
                                                        </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $('.btn-modal').on('click', function(e){
        e.preventDefault()
        let attr = $(this).attr('data-bs-target')
        $(attr).modal('show')
    })
    $('#btn-modal-filter').on('click', function(e){
        e.preventDefault()
        $('#filter-payment').modal('show')
    })
</script>
@endsection

@push('script-additional')
<script>

    // var browserState = document.querySelectorAll('.state-chart');
    // var $trackBgColor = '#EBEBEB';

    // browserState.forEach(function(item, index){
    //     var color = item.getAttribute('data-color')
    //     var percentage = item.getAttribute('data-percentage')

    //     browserStatePrimaryChartOptions = {
    //         chart: {
    //         height: 30,
    //         width: 30,
    //         type: 'radialBar'
    //         },
    //         grid: {
    //         show: false,
    //         padding: {
    //             left: -15,
    //             right: -15,
    //             top: -12,
    //             bottom: -15
    //         }
    //         },
    //         colors: [window.colors.solid.danger],
    //         series: [parseFloat(percentage)],
    //         plotOptions: {
    //         radialBar: {
    //             hollow: {
    //             size: '22%'
    //             },
    //             track: {
    //             background: $trackBgColor
    //             },
    //             dataLabels: {
    //             showOn: 'always',
    //             name: {
    //                 show: false
    //             },
    //             value: {
    //                 show: false
    //             }
    //             }
    //         }
    //         },
    //         stroke: {
    //         lineCap: 'round'
    //         }
    //     };
    //     browserStatePrimaryChart = new ApexCharts(item, browserStatePrimaryChartOptions);
    //     browserStatePrimaryChart.render();
    })
</script>
@endpush