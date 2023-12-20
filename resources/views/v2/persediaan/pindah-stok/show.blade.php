@extends('v2.layout.vuexy')

@section('content')

@if($errors->any())
@include('v2.component.error')
@endif

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<a href="{{ route('pindah-stok.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<!-- Data Stok -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Pindah Stok
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td>Gudang asal</td>
                <td>{{ $pindah_stok->gudangAsal->nama_gudang }}</td>
            </tr>
            <tr>
                <td>Gudang tujuan</td>
                <td>{{ $pindah_stok->gudangTujuan->nama_gudang }}</td>
            </tr>
            <tr>
                <td style="width: 250px;">Status </td>
                <td>
                    @if($pindah_stok->status_proses == 0)
                    <span class="badge bg-label-warning">Belum diproses</span>
                    @else
                    <span class="badge bg-label-success">Sudah diproses</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 250px;">Nomer ref </td>
                <td>{{ $pindah_stok->nomer_ref }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Tanggal </td>
                <td>{{ date('d-m-Y',strtotime($pindah_stok->tanggal)) }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Tanggal kirim </td>
                <td>
                    @if($pindah_stok->tanggal_kirim == null)
                    -
                    @else
                    {{ date('d-m-Y',strtotime($pindah_stok->tanggal_kirim)) }}
                    @endif
                </td>
            </tr>

            <tr>
                <td style="width: 250px;">Keterangan </td>
                <td>{{ $pindah_stok->keterangan }}</td>
            </tr>
        </table>
    </div>
</div>

<br>

<!-- Data rincian produk -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Rincian produk
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>produk</th>
                    <th>qty</th>
                    <th>catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pindah_stok->rincianProduk as $rincian)
                <tr>
                    <td>{{ $rincian->produk->kode_barang }} | {{ $rincian->produk->nama_barang }}</td>
                    <td>{{ $rincian->kuantitas }}</td>
                    <td>{{ $rincian->catatan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<br>

<div class="card">
    <div class="card-body">
        <a href="{{ route('pindah-stok.edit',$pindah_stok->id) }}" class="btn btn-outline-warning">
            Ubah
        </a>
        <!-- <button type="button" class="btn btn-outline-danger btn-hapus">
            Hapus
        </button> -->
        <a href="{{ route('pindah-stok.print',$pindah_stok->id) }}" class="btn btn-outline-success">
            Print
        </a>
        @if(Auth::user()->position == 'gudang' || Auth::user()->name == 'Super Admin')
        @if($pindah_stok->status_proses == 0)
        <button class="btn btn-outline-success btn-proses-kirim">Proses Kirim</button>
        @else
        <a href="{{ route('pindah-stok.print-sj',$pindah_stok->id) }}" class="btn btn-outline-success">Print Surat Jalan</a>
        @endif
        @endif
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pindah-stok.destroy',$pindah_stok->id) }}" method="post">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah yakin akan menghapus Data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-danger btn-tambah-paket">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Proses Kirim -->
<div class="modal fade" id="modalProsesKirim" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pindah-stok.proses-kirim',$pindah_stok->id) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Konfirmasi!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input name="pesanan" type="text" value="{{ $pindah_stok->id }}" hidden>
                    <h4>Apakah Anda yakin memproses Data ini?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-danger btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-hapus').on('click', function() {
            $('#modalHapus').modal('show');
        });

        // modal konfirmasi proses kirim barang
        $('.btn-proses-kirim').click(function(e) {
            $('#modalProsesKirim').modal('show');
        });
    })
</script>
@endsection