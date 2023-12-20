@extends('layouts.vuexy')

@section('header')
Report Purchase | Request Not Approve
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12 mb-1">
        <a href="{{ url('admin/report-purchase') }}"><i data-feather='arrow-left-circle'></i> Kembali ke daftar laporan</a>
    </div>
    <div class="col-lg-12 col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-dashboard table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nomor Permintaan</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Direktur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td> {{ $item->nomer_pmtpembelian }} </td>
                                <td> {{ $item->tanggal }} </td>
                                <td> {{ $item->keterangan ?? '-' }} </td>
                                <td>
                                    @if ($item->approve_direktur == 0)
                                        <span class="badge badge-light-danger">Belum Disetujui</span>
                                    @else 
                                        <span class="badge badge-light-success">Sudah Disetujui</span>
                                    @endif
                                </td>
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
</script>
@endsection