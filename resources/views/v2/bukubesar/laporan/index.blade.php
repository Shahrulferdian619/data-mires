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
            Laporan Bukubesar
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr class="table-info">
                    <th>nomer</th>
                    <th>tanggal</th>
                    <th>akun</th>
                    <th>transaksi</th>
                    <th>debit</th>
                    <th>kredit</th>
                    <th>keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['bukubesar'] as $bukubesar)
                <tr>
                    <td>{{ $bukubesar->nomer_sumber }}</td>
                    <td>{{ date('d/m/Y', strtotime($bukubesar->tanggal)) }}</td>
                    <td>{{ $bukubesar->coa->nama_coa }}</td>
                    <td>{{ $bukubesar->sumber_transaksi }}</td>
                    <td>
                        {{ $bukubesar->tipe_mutasi == 'D' ? number_format($bukubesar->nominal,2) : '-' }}
                    </td>
                    <td>
                        {{ $bukubesar->tipe_mutasi == 'K' ? number_format($bukubesar->nominal,2) : '-' }}
                    </td>
                    <td>
                        {{ $bukubesar->keterangan }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>TOTAL</td>
                    <td>{{ number_format($data['total_debit'],2) }}</td>
                    <td>{{ number_format($data['total_kredit'],2) }}</td>
                    <td></td>
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