@extends('v2.layout.vuexy')

@section('content')

<a href="{{ route('pengiriman-penjualan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<div class="card">
    <div class="card-header">
        <h5 class="m-0 me-2 card-title">
            Daftar Pesanan
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td style="width: 250px;">Status</td>
                <td>
                    @if($pesanan->status_proses == 0)
                    <span class="badge bg-label-warning">belum dikirim</span>
                    @else
                    <span class="badge bg-label-success">sudah dikirim</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Nomer Pesanan Penjualan</td>
                <td>{{ $pesanan->nomer_pesanan_penjualan }}</td>
            </tr>
            <tr>
                <td>Tanggal Pesanan</td>
                <td>{{ date('d/m/Y',strtotime($pesanan->tanggal)) }}</td>
            </tr>
            <tr>
                <td>Marketplace</td>
                <td>{{ $pesanan->jenis_penjualan }}</td>
            </tr>
            <tr>
                <td>Nomer Pesanan</td>
                <td>{{ $pesanan->nomer_pesanan }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>{{ $pesanan->pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <td>Ekspedisi</td>
                <td>{{ $pesanan->ekspedisi }}</td>
            </tr>
            <tr>
                <td>Resi</td>
                <td>{{ $pesanan->resi }}</td>
            </tr>
            <tr>
                <td>Penerima</td>
                <td>{{ $pesanan->penerima }}</td>
            </tr>
            <tr>
                <td>Alamat penerima</td>
                <td>{{ $pesanan->alamat_penerima }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>{{ $pesanan->keterangan }}</td>
            </tr>
        </table>
    </div>
</div>

<br>

<div class="card">
    <div class="card-header">
        <h5 class="m-0 me-2 card-title">
            Daftar Pesanan
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>kode produk</th>
                    <th>nama produk</th>
                    <th>kuantitas</th>
                    <th>catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->rincian as $rinci)
                <tr>
                    <td>{{ $rinci->produk->kode_barang }}</td>
                    <td>{{ $rinci->produk->nama_barang }}</td>
                    <td>{{ $rinci->kuantitas }}</td>
                    <td>{{ $rinci->catatan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<br>

@if(!empty($pesanan_penjualan->berkas->berkas1))
<!-- Data Berkas -->
<div class="card">
    <div class="card-header">
        <h5 class="m-0 me-2 card-title">
            Berkas
        </h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            @if($pesanan_penjualan->berkas->berkas1 != '')
            <tr>
                <td style="width: 100px;">Berkas1</td>
                <td>
                    <a href="{{ route('pesanan-penjualan.download-berkas',$pesanan_penjualan->berkas->berkas1) }}">{{ $pesanan_penjualan->berkas->berkas1 }}</a>
                </td>
            </tr>
            @endif

            @if($pesanan_penjualan->berkas->berkas2 != '')
            <tr>
                <td style="width: 100px;">Berkas2</td>
                <td>
                    <a href="{{ route('pesanan-penjualan.download-berkas',$pesanan_penjualan->berkas->berkas2) }}">{{ $pesanan_penjualan->berkas->berkas2 }}</a>
                </td>
            </tr>
            @endif

            @if($pesanan_penjualan->berkas->berkas3 != '')
            <tr>
                <td style="width: 100px;">Berkas3</td>
                <td>
                    <a href="{{ route('pesanan-penjualan.download-berkas',$pesanan_penjualan->berkas->berkas3) }}">{{ $pesanan_penjualan->berkas->berkas3 }}</a>
                </td>
            </tr>
            @endif

            @if($pesanan_penjualan->berkas->berkas4 != '')
            <tr>
                <td style="width: 100px;">Berkas4</td>
                <td>
                    <a href="{{ route('pesanan-penjualan.download-berkas',$pesanan_penjualan->berkas->berkas4) }}">{{ $pesanan_penjualan->berkas->berkas4 }}</a>
                </td>
            </tr>
            @endif

            @if($pesanan_penjualan->berkas->berkas5 != '')
            <tr>
                <td style="width: 100px;">Berkas5</td>
                <td>
                    <a href="{{ route('pesanan-penjualan.download-berkas',$pesanan_penjualan->berkas->berkas4) }}">{{ $pesanan_penjualan->berkas->berkas5 }}</a>
                </td>
            </tr>
            @endif
        </table>
    </div>
</div>

<br>
@endif

<div class="card">
    <div class="card-body">
        @if($pesanan->status_proses == 0)
        <button class="btn btn-outline-primary btn-proses-kirim" type="button">Proses Kirim!</button>
        @else
        <a href="{{ route('pengiriman-penjualan.print-sj', $pesanan->pengiriman->id) }}" target="_blank" class="btn btn-outline-success">
            <i class="fa fa-print"></i>
            Print SJ Baru
        </a>
        @endif
    </div>
</div>

<!-- Modal Proses Kirim -->
<div class="modal fade" id="modalProsesKirim" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pengiriman-penjualan.proses-kirim') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Konfirmasi!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input name="pesanan" type="text" value="{{ $pesanan->id }}" hidden>
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
        // modal konfirmasi proses kirim barang
        $('.btn-proses-kirim').click(function(e) {
            $('#modalProsesKirim').modal('show');
        });
    });
</script>
@endsection