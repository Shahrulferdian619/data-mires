@extends('layouts.vuexy')

@section('header')
Laporan Penjualan
@endsection

@section('content')
    <div class="row match-height ">
        <!-- Earnings Card -->
        <div class="col-12">
            <div class="card earnings-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="earnings-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card card-transaction">
                <div class="card-body">
                    <h6>Pelanggan</h6>
                    <hr>
                    <div class="transaction-item">
                        <div class="d-flex">
                            <div class="avatar bg-light-info rounded float-start">
                                <div class="avatar-content">
                                    <i data-feather="user" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="transation-percentage">
                                <h6 class="transaction-title">Penjualan Per Pelanggan</h6>
                                <small>
                                    <a href="#" type="button" id="btn-filter-pelanggan" data-bs-toggle="modal" data-bs-target="#filter-penjualan-per-pelanggan" class="badge badge-light-primary btn-modal" >
                                        Lihat Detail
                                    </a>
                                </small>
                                <!-- Modal -->
                                <div class="modal fade text-start" id="filter-penjualan-per-pelanggan" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                            </div>
                                            <form action="{{ url('admin/report-sales/sales-per-customer') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="">Pelanggan</label>
                                                        <select name="pelanggan_id[]" id="" class="select2 form-control" multiple required>
                                                            @foreach ($pelanggan as $item)
                                                                <option value="{{ $item->id }}">{{ $item->nama_pelanggan }}</option>
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
                                                                <input type="radio" name="filter_time" id="a_week_ago_per_sales" value="7" class="custom-control-input">
                                                                <label for="a_week_ago_per_sales" class="custom-control-label" >1 Minggu yang lalu</label>
                                                            </div>
                                                            <div class="custom-radio custom-control">
                                                                <input type="radio" name="filter_time" id="a_month_ago_per_sales" value="30" class="custom-control-input">
                                                                <label for="a_month_ago_per_sales" class="custom-control-label" >1 Bulan yang lalu (30)</label>
                                                            </div>
                                                            <div class="custom-radio custom-control">
                                                                <input type="radio" name="filter_time" id="a_year_ago_per_sales" value="365" class="custom-control-input">
                                                                <label for="a_year_ago_per_sales" class="custom-control-label" >1 Tahun yang lalu (365)</label>
                                                            </div>
                                                            <div class="custom-radio custom-control">
                                                                <input type="radio" name="filter_time" id="two_year_ago_per_sales" value="730" class="custom-control-input">
                                                                <label for="two_year_ago_per_sales" class="custom-control-label" >2 Tahun yang lalu (730)</label>
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
                            <div class="avatar bg-light-warning rounded float-start">
                                <div class="avatar-content">
                                    <i data-feather="package" class="avatar-icon font-medium-3"></i>
                                </div>
                            </div>
                            <div class="transaction-percentage">
                                <h6 class="transaction-title">Penjualan Per Barang</h6>
                                <small>
                                    <a href="{{ url('admin/report-sales/sales-per-item') }}" type="button" data-bs-toggle="modal" data-bs-target="#filter-penjualan-per-item" class="badge badge-light-primary btn-modal" >
                                        Lihat Detail
                                    </a>
                                </small>
                                <div class="modal fade text-start" id="filter-penjualan-per-item" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                            </div>
                                            <form action="{{ url('admin/report-sales/sales-per-item') }}" method="POST">
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
                                <a href="{{ url('admin/report-sales/payment') }}" type="button" data-bs-toggle="modal" data-bs-target="#filter-5" class="badge badge-light-primary btn-modal" >
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
                                        <form action="{{ url('admin/report-sales/payment') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="">Pilih Supplier</label>
                                                    <select name="pelanggan_id[]" id="" class="select2 form-control" multiple required>
                                                        @foreach ($pelanggan as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nama_pelanggan }}</option>
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

    $('#btn-filter-pelanggan').on('click', function(e){
        e.preventDefault()
        $('#filter-pelanggan').modal('show')
    })
    $('#btn-filter-pelanggan-rinci').on('click', function(e){
        e.preventDefault()
        $('#filter-pelanggan-rinci').modal('show')
    })
    $('#btn-modal-filter').on('click', function(e){
        e.preventDefault()
        $('#filter-payment').modal('show')
    })
</script>
@endsection
@push('script-additional')
    <script>
        $(document).ready(function(){
            let url = '<?php echo url("/api"); ?>'

            const xhttp = new XMLHttpRequest();
            xhttp.onload = function(){
                let data = JSON.parse(this.responseText)
                let count = 0;
                let provinceName = [];
                let provincePercent = [];
                let provinceColor = [];
                data.map((item, index) => {
                    count += item.total
                })
                data.map((item, index) => {
                    provinceName.push(item.province)
                    provincePercent.push( Math.round((item.total / count) * 100) )
                    provinceColor.push('#28c76f' + Math.floor((Math.random() * 9) + 0) + '' + Math.floor((Math.random() * 9) + 0))
                })

                chartPie(provinceName, provincePercent, provinceColor)
            }
            xhttp.open('GET', url + '/get-pie-chart-data')
            xhttp.send()

            function chartPie(provinceName, provincePercent, provinceColor){
                var $earningsStrokeColor2 = '#28c76f66';
                var $earningsStrokeColor3 = '#28c76f33';
                var $earningsChart = document.querySelector('#earnings-chart');
                earningsChartOptions = {
                    chart: {
                        type: 'donut',
                        height: 200,
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: provincePercent,
                    legend: { show: false },
                    comparedResult: [2, -3, 8],
                    labels: provinceName,
                    stroke: { width: 0 },
                    colors: provinceColor,
                    grid: {
                        padding: {
                            right: -20,
                            bottom: -8,
                            left: -20
                        }
                    },
                    plotOptions: {
                        pie: {
                            startAngle: -10,
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        offsetY: 15
                                    },
                                    value: {
                                        offsetY: -15,
                                        formatter: function (val) {
                                            return parseInt(val) + '%';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        offsetY: 15,
                                        label: provinceName[0],
                                        formatter: function (w) {
                                            return provincePercent[0] + '%';
                                        }
                                    }
                                }
                            }
                        }
                    },
                    responsive: [
                        {
                            breakpoint: 1325,
                            options: {
                                chart: {
                                    height: 100
                                }
                            }
                        },
                        {
                            breakpoint: 1200,
                            options: {
                                chart: {
                                    height: 120
                                }
                            }
                        },
                        {
                            breakpoint: 1045,
                            options: {
                                chart: {
                                    height: 100
                                }
                            }
                        },
                        {
                            breakpoint: 992,
                            options: {
                                chart: {
                                    height: 120
                                }
                            }
                        }
                    ]
                };
                earningsChart = new ApexCharts($earningsChart, earningsChartOptions);
                earningsChart.render();
            }
        })
    </script>
@endpush