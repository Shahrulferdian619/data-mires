@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Update fitur</h4>
    <ul>
        <li>Fitur download excel sudah bisa digunakan, silahkan dicoba.</li>
    </ul>
</div>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Stok Persediaan

            <button class="btn btn-sm btn-outline-success btn-download-excel">
                <i class="fa fa-download"></i>
                Download Excel
            </button>
        </h5>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th>gudang</th>
                    <th>kode produk</th>
                    <th>nama produk</th>
                    <th>kuantitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stok_produk as $rincian)
                <tr>
                    <td>{{ $rincian->nama_gudang }}</td>
                    <td>{{ $rincian->kode_produk }}</td>
                    <td>{{ $rincian->nama_produk }}</td>
                    <td>{{ $rincian->kuantitas }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Download Excel -->
<div class="modal fade" id="modalDownloadExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('persediaan.download-excel') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Download Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Lanjutkan?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-info btn-tambah-paket">Download</button>
                </div>
            </div>
        </form>
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

        // button modal download PDF
        $('.btn-download-pdf').click(function(e) {
            $('#modalDownloadPDF').modal('show');
        });

        // button modal download PDF
    });
</script>
@endsection