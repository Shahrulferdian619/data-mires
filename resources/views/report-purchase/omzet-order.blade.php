@extends('layouts.vuexy')

@section('header')
Report Purchase | Order Per Pay
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
                                <th>Nomor Order</th>
                                <th>Nama Supplier</th>
                                <th>Tanggal</th>
                                <th>Jumlah Tagihan</th>
                                <th>Jumlah Yang Dibayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td> {{ $item['nomer_payment'] }} </td>
                                <td> {{ $item['vendor'] }} </td>
                                <td> {{ $item['tanggal'] }} </td>
                                <td>Rp. {{ number_format($item['jumlah_tagihan'], 0, ',', '.') }} </td>
                                <td>Rp. {{ number_format($item['jumlah_bayar'], 0, ',', '.') }} </td>
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