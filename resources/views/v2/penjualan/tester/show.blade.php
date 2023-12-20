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

<a href="{{ route('permintaan-tester.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<!-- Data permintaan tester -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Permintaan Tester
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td style="width: 250px;">Status </td>
                <td>
                    @if($tester->status_proses == 0)
                    <span class="badge bg-label-warning">Belum diproses</span>
                    @else
                    <span class="badge bg-label-success">Sudah diproses</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 250px;">Nomer </td>
                <td>{{ $tester->nomer_permintaan_tester }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Tanggal </td>
                <td>{{ date('d-m-Y',strtotime($tester->tanggal)) }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Pelanggan </td>
                <td>{{ $tester->pelanggan->nama_pelanggan }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Sales </td>
                <td>{{ $tester->sales->nama_sales }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Nomer pesanan</td>
                <td>{{ $tester->nomer_pesanan }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Ekspedisi </td>
                <td>{{ $tester->ekspedisi }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Resi </td>
                <td>{{ $tester->resi }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Keterangan </td>
                <td>{{ $tester->keterangan }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Penerima </td>
                <td>{{ $tester->penerima }}</td>
            </tr>

            <tr>
                <td style="width: 250px;">Alamat penerima </td>
                <td>{{ $tester->alamat_penerima }}</td>
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
                @foreach($rincian as $r)
                <tr>
                    <td>{{ $r->produk->kode_barang }} | {{ $r->produk->nama_barang }}</td>
                    <td>{{ $r->kuantitas }}</td>
                    <td>{{ $r->catatan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<br>

<div class="card">
    <div class="card-body">
        <div class="col-md-6">
            <table class="table table-bordered">
                @foreach($tester->berkas as $berkas)
                <tr>
                    <td>Berkas</td>
                    <td>{{ $berkas->berkas }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<br>

<div class="card">
    <div class="card-body">
        <a href="{{ route('permintaan-tester.edit',$tester->id) }}" class="btn btn-outline-warning">
            Ubah
        </a>
        <button type="button" class="btn btn-outline-danger btn-hapus">
            Hapus
        </button>
        <a href="{{ route('permintaan-tester.print',$tester->id) }}" class="btn btn-outline-success">
            Print
        </a>
        @if(Auth::user()->position == 'gudang' || Auth::user()->name == 'Super Admin')
        @if($tester->status_proses == 0)
        <button class="btn btn-outline-success btn-proses-kirim">Proses Kirim</button>
        @else
        <a href="{{ route('permintaan-tester.print-sj',$tester->id) }}" class="btn btn-outline-success">Print Surat Jalan</a>
        @endif
        @endif
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('permintaan-tester.destroy',$tester->id) }}" method="post">
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
        <form style="width: 100%;" action="{{ route('permintaan-tester.proses-kirim',$tester->id) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Konfirmasi!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input name="pesanan" type="text" value="{{ $tester->id }}" hidden>
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