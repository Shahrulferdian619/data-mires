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
            Daftar Akun (COA)
            <a href="{{ route('bukubesar.coa.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 100px;">nomer</th>
                    <th style="width: 200px;">nama</th>
                    <th>keterangan</th>
                    <th style="width: 100px;">tipe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['coa'] as $coa)
                <tr>
                    <td>
                        <a href="{{ route('bukubesar.coa.edit',$coa->id) }}">{{ $coa->nomer_coa }}</a>
                    </td>
                    <td>{{ $coa->nama_coa }}</td>
                    <td>{{ $coa->keterangan }}</td>
                    <td>{{ $coa->tipe->coa_tipe }}</td>
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
        // button modal download Excel
        $('.btn-download-excel').click(function(e) {
            $('#modalDownloadExcel').modal('show');
        });
    });
</script>
@endsection