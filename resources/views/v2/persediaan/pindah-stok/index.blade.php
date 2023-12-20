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
            Daftar Pindah Stok
            <a href="{{ route('pindah-stok.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-plus"></i> Baru
            </a>
        </h5>
    </div>

    <div class="col-md-4">
        <table class="table table-bordered">
            <tr>
                <td colspan="2" style="background-color: #8bd6fc;">Keterangan warna tabel</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #ffa1b7;">Belum diproses</td>
            </tr>
            <tr>
                <td style="width: 50px;">Warna</td>
                <td style="background-color: #fcd88b;">Sudah diproses/dikirim</td>
            </tr>
        </table>
    </div>

    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tabel_dt">
            <thead>
                <tr>
                    <th style="width: 200px;">no.</th>
                    <th style="width: 100px;">tgl.</th>
                    <th style="width: 100px;">tgl.kirim</th>
                    <th>keterangan</th>
                    <th>dibuat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['pindah_stok'] as $pindah_stok)
                <tr style="background-color: @if($pindah_stok->status_proses == 0) #ffa1b7 @elseif($pindah_stok->status_proses == 1) #fcd88b @else table-info @endif;">
                    <td>
                        <a href="{{ route('pindah-stok.show',$pindah_stok->id) }}" class="">
                            {{ $pindah_stok->nomer_ref }}
                        </a>
                    </td>
                    <td>{{ date('d-m-Y',strtotime($pindah_stok->tanggal)) }}</td>
                    <td>
                        @if($pindah_stok->tanggal_kirim == null)
                        -
                        @else
                        {{ date('d-m-Y',strtotime($pindah_stok->tanggal_kirim)) }}
                        @endif
                    </td>
                    <td>{{ $pindah_stok->keterangan }}</td>
                    <td>{{ $pindah_stok->dibuatOleh->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Download Excel -->
<div class="modal fade" id="modalDownloadExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pesanan-penjualan.download-excel') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Download Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Dari tanggal</label>
                    <input name="dari_tanggal" type="date" class="form-control" required>

                    <label for="">Sampai tanggal</label>
                    <input name="sampai_tanggal" type="date" class="form-control" required>
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
    });
</script>
@endsection