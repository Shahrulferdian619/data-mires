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
            Daftar Jurnal Umum |
            <a href="{{ route('bukubesar.jurnal-umum.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr class="table-info">
                    <th>nomer</th>
                    <th>tanggal</th>
                    <th>total</th>
                    <th>keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['jurnal'] as $jurnal)
                <tr>
                    <td>
                        <a href="{{ route('bukubesar.jurnal-umum.edit', $jurnal->id) }}">{{ $jurnal->nomer }}</a>
                    </td>
                    <td>{{ date('d/m/Y', strtotime($jurnal->tanggal)) }}</td>
                    <td>{{ number_format($jurnal->total,2) }}</td>
                    <td>{{ $jurnal->keterangan}} </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>

                </tr>
            </tfoot>
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
        // button modal download Excel
        $('.btn-download-excel').click(function(e) {
            $('#modalDownloadExcel').modal('show');
        });
    });
</script>
@endsection