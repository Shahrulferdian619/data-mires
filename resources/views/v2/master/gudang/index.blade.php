@extends('v2.layout.vuexy')

@section('content')

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Daftar Gudang
            <a href="{{ route('master-data.gudang.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="tabel_dt">
            <thead>
                <tr>
                    <th>Gudang</th>
                    <th>PIC</th>
                    <th>Alamat</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gudang as $item)
                    <tr>
                        <td>
                            <a href="{{ route('master-data.gudang.edit',$item->id) }}">{{ $item->nama_gudang }}</a>
                        </td>
                        <td>{{ $item->pic_gudang }}</td>
                        <td>{{ $item->alamat_gudang }}</td>
                        <td>{{ $item->keterangan }}</td>
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
