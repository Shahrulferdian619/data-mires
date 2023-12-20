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
                                <a href="{{ url('admin/report-purchase/order-per-vendor') }}" type="button" id="btn-modal" data-bs-toggle="modal" data-bs-target="#filter" class="badge badge-light-primary" >
                                    Lihat Detail
                                </a>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade text-start" id="filter" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                        </div>
                                        <form action="{{ url('admin/report-purchase/order-per-vendor') }}" method="POST">
                                            @csrf
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
                    </div>
                    <div class="fw-bolder text-info">{{ number_format($order_per_vendor, 0, ',', '.') }}</div>
                </div>
                <hr>
                <h6>Barang</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-success rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="package" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pembelian Per Barang ( Total Harga )</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-per-item-total') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-success">Rp. {{ number_format($order_per_item_price, 0, ',', '.') }}</div>
                </div>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-warning rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="package" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pembelian Per Barang ( Total Kuantitas )</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-per-item-qty') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-warning">{{ number_format($order_per_item_qty, 0, ',', '.') }}</div>
                </div>
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
                                <a href="{{ url('admin/report-purchase/order-per-item') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-info">{{ number_format($order_per_item, 0, ',', '.') }}</div>
                </div>
                <hr>
                <h6>Persetujuan</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-danger rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="file-text" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Permintaan Pembelian Belum Disetujui</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/request-not-approve') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-danger">{{ number_format($request, 0, ',', '.') }}</div>
                </div>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-danger rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="file-text" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pesanan Pembelian Belum Disetujui</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-not-approve') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-danger">{{ number_format($order_not_approve, 0, ',', '.') }}</div>
                </div>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-success rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="file-text" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pesanan Pembelian Sudah Disetujui</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-approve') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-success">{{ number_format($order_approve, 0, ',', '.') }}</div>
                </div>
                <hr>
                <h6>Pembayaran</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-success rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="dollar-sign" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Pembelian Per Biaya</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/order-per-pay') }}" id="btn-modal-filter" data-bs-toggle="modal" data-bs-target="#filter-payment" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                            <!-- Modal -->
                            <div class="modal fade text-start" id="filter-payment" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Filter Data</h4>
                                        </div>
                                        <form action="{{ url('admin/report-purchase/order-per-pay') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <label>Tanggal Awal : </label>
                                                <input type="date" class="form-control" name="start" value="{{ date('Y-m-d') }}">

                                                <label>Tanggal Akhir : </label>
                                                <input type="date" class="form-control" name="end" value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="semua" class="btn btn-success">Semua</button>
                                                <button type="submit" name="filter" class="btn btn-primary">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fw-bolder text-success">Rp. {{ number_format($order_per_pay, 0, ',', '.') }}</div>
                </div>
                {{-- <hr>
                <h6>Histori</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-info rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="bar-chart-2" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Histori Permintaan Pembelian</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/omzet-order') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-info">{{ number_format(0, 0, ',', '.') }}</div>
                </div>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-info rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="bar-chart-2" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Histori Pesanan Pembelian</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/omzet-order') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-info">{{ number_format(0, 0, ',', '.') }}</div>
                </div>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-info rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="bar-chart-2" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Histori Penerimaan Pembelian</h6>
                            <small>
                                <a href="{{ url('admin/report-purchase/omzet-order') }}" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                    <div class="fw-bolder text-info">{{ number_format(0, 0, ',', '.') }}</div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
                    
{{-- <div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table-dashboard table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Pengajuan </th>
                        <th>Jumlah</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td> Permintaan Pembelian Belum Disetujui Direktur </td>
                            <td>  {{ $pmtpembelian->count() }} </td>
                            <td>
                                <a href="/admin/pmtpembelian" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td> Pesanan PO Belum Disetujui </td>
                            <td>  {{ $popembelian->count() }} </td>
                            <td>
                                <a href="/admin/po" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td> Pembayaran Belum Disetujui </td>
                            <td>  {{ $fakturpembelian }} </td>
                            <td>
                                <a href="/admin/po" class="badge badge-light-secondary">
                                    <i data-feather="eye"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> --}}
@endsection

@section('myjs')
<script type="text/javascript">
    $('#btn-modal').on('click', function(e){
        e.preventDefault()
        $('#filter').modal('show')
    })
    $('#btn-modal-filter').on('click', function(e){
        e.preventDefault()
        $('#filter-payment').modal('show')
    })
</script>
@endsection