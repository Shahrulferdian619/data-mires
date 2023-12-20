@extends('v2.layout.vuexy')

@section('content')

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Pelanggan
            <a href="{{ route('master-kategori.pelanggan.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th>Kategori Pelanggan</th>
                    <th style="">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['kategori_pelanggan'] as $item)
                    <tr>
                        <td>
                            <a href="{{ route('master-kategori.pelanggan.edit', $item->id) }}">{{ $item->kategori_pelanggan }}</a>
                        </td>
                        <td>
                            @if($item->status_aktif)
                            <span class="badge bg-label-success">Aktif</span>
                            @else
                            <span class="badge bg-label-danger">Tidak Aktif</span>
                            @endif
                        </td>
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