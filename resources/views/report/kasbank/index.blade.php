@extends('layouts.vuexy')

@section('header')
Report Kas & Bank
@endsection

@section('content')
<div class="row match-height">
    <div class="col-lg-12 col-12">
        <div class="card card-transaction">
            <div class="card-body">
                
                <h6>Laporan</h6>
                <hr>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-success rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="file-text" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Buku Bank</h6>
                            <small>
                                <a href="#" type="button" id="btn-modal-buku-bank" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="transaction-item">
                    <div class="d-flex">
                        <div class="avatar bg-light-success rounded float-start">
                            <div class="avatar-content">
                                <i data-feather="file-text" class="avatar-icon font-medium-3"></i>
                            </div>
                        </div>
                        <div class="transaction-percentage">
                            <h6 class="transaction-title">Arus Kas per Akun</h6>
                            <small>
                                <a href="#" type="button" id="btn-modal-arus" class="badge badge-light-primary" >Lihat Detail</a>
                            </small>
                        </div>
                    </div>
                </div>
                <!-- MODAL FILTER -->
                <div class="modal fade text-start" id="filter-arus" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel33">Filter Data Arus Kas per Akun</h4>
                            </div>
                            <form action="{{ route('admin.report.kas-bank.aruskas') }}" method="POST">
                                @csrf
                                <div class="modal-body">
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
                <div class="modal fade text-start" id="filter-buku-bank" tabindex="-1" aria-labelledby="myModalLabel33" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel33">Filter Data Buku Bank</h4>
                            </div>
                            <form action="{{ route('admin.report.kas-bank.bukubank') }}" method="POST">
                                @csrf
                                <div class="modal-body">
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
    </div>
</div>
        
@endsection

@section('myjs')
<script type="text/javascript">
    $('#btn-modal-arus').on('click', function(e){
        e.preventDefault()
        $('#filter-arus').modal('show')
    })
    $('#btn-modal-buku-bank').on('click', function(e){
        e.preventDefault()
        $('#filter-buku-bank').modal('show')
    })
</script>
@endsection