@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Sales
            <a href="{{ route('master-data.sales.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th>nama sales</th>
                    <th>kode sales</th>
                    <th>target total invoice</th>
                    <th>bonus persentase</th>
                    <th>keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>
                        <a href="{{ route('master-data.sales.edit',$item->id) }}">{{$item->nama_sales}}</a>
                    </td>
                    <td>{{$item->kode}}</td>
                    <td>Rp. {{ number_format($item->target_total_invoice, 0, ',', '.') }}</td>
                    <td>{{$item->bonus_presentase}} %</td>
                    <td>{{$item->keterangan}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tabel_dt').DataTable({
            'ordering': false,
            'pageLength': 100
        });
    });
</script>
@endsection