@extends('layouts.vuexy')

@section('header')
Report Purchase | Quantity Order
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
                                <th>Tanggal</th>
                                <th>Total Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td> {{ $item->nomer_po }} </td>
                                <td> {{ date('l,d M Y', $item->tanggal) }} </td>
                                @php
                                    $quantity_total = 0;
                                @endphp
                                @foreach ($item->rinci as $value)
                                    @php
                                        $quantity_total += $value->jumlah
                                    @endphp
                                @endforeach
                                <td> {{ $quantity_total }} </td>
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