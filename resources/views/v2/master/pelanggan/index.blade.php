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
            Daftar Pelanggan
            <a href="{{ route('master-data.pelanggan.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 100px;">kode</th>
                    <th style="width: 100px;">nama</th>
                    <th>no. hp</th>
                    <th>alamat</th>
                    <th>keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['pelanggan'] as $pelanggan)
                <tr>
                    <td>
                        <a href="{{ route('master-data.pelanggan.edit',$pelanggan->id) }}">{{ $pelanggan->kode_pelanggan }}</a>
                    </td>
                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                    <td>{{ $pelanggan->no_handphone }}</td>
                    <td>{{ $pelanggan->detil_alamat }} {{ $pelanggan->provinsi }} {{ $pelanggan->kota }}</td>
                    <td>{{ $pelanggan->keterangan }}</td>
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