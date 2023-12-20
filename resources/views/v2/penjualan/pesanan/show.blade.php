@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<a href="{{ route('pesanan-penjualan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

@if($pesanan_penjualan->status_proses == 0)
<div class="alert alert-warning" role="alert">Belum diproses</div>
@else
<div class="alert alert-success" role="alert">Sudah diproses</div>
@endif

<small>Dibuat pada : {{$pesanan_penjualan->created_at}}</small>
<br>
<small>Terakhir diperbarui : {{$pesanan_penjualan->updated_at}} </small>
<!-- Data Pelanggan -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Pelanggan
        </h5>
    </div>

    <div class="card-body">
        <label for="">Kode pelanggan</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->pelanggan->kode_pelanggan }}" disabled>

        <label for="">Nama pemesan</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->pelanggan->nama_pelanggan }}" disabled>

        <label for="">Alamat pemesan</label>
        <textarea cols="30" rows="5" class="form-control" disabled>{{ $pesanan_penjualan->pelanggan->detil_alamat }} {{ $pesanan_penjualan->pelanggan->kota }} {{ $pesanan_penjualan->pelanggan->provinsi }}</textarea>

        <hr>

        <label for="">Nama penerima</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->penerima }}" disabled>

        <label for="">Alamat penerima</label>
        <textarea cols="30" rows="5" class="form-control" disabled>{{ $pesanan_penjualan->alamat_penerima }}</textarea>
    </div>
</div>

<br>

<!-- Data Pesanan -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Pesanan
        </h5>
    </div>

    <div class="card-body">
        <label for="">Nomer pesanan penjualan</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->nomer_pesanan_penjualan }}" disabled>

        <label for="">Tanggal</label>
        <input name="tanggal" type="text" class="form-control" value="{{ date('d/m/Y', strtotime($pesanan_penjualan->tanggal)) }}" disabled>

        <label for="">Jenis penjualan</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->jenis_penjualan }}" disabled>

        <label for="">No. pesanan</label>
        <input name="nomer_pesanan" type="text" class="form-control" value="{{ $pesanan_penjualan->nomer_pesanan }}" disabled>

        <label for="">Sales</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->sales->nama_sales }}" disabled>

        <label for="">PPn</label>
        <input type="text" class="form-control" value="@if($pesanan_penjualan->ppn == 1) Ya @elseif($pesanan_penjualan->ppn == 2) Include ppn @else Tidak @endif" disabled>

        <label for="">Ekspedisi</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->ekspedisi }}" disabled>

        <label for="">Resi</label>
        <input name="resi" type="text" class="form-control" value="{{ $pesanan_penjualan->resi }}" disabled>

        <label for="">Keterangan</label>
        <textarea name="keterangan" cols="30" rows="5" class="form-control" disabled>{{ $pesanan_penjualan->keterangan }}</textarea>
    </div>
</div>

<br>

<!-- Data Rincian Produk -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Rincian Produk
        </h5>
    </div>

    <div class="card-body">
        @if($pesanan_penjualan->gudang != null)
        <label for="">Diambil dari gudang</label>
        <input type="text" class="form-control" value="{{ $pesanan_penjualan->gudang->nama_gudang }}" disabled>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>produk</th>
                        <th>qty</th>
                        <th>harga</th>
                        <th>dsc(%)</th>
                        <th>dsc(Rp)</th>
                        <th>potongan</th>
                        <th>cashback</th>
                        <th>subtotal</th>
                        <th>catatan</th>
                    </tr>
                </thead>
                <tbody class="rincianProduk">
                    @foreach($pesanan_penjualan_rinci as $row)
                    <tr>
                        <td>{{ $row->produk->kode_barang }} | {{ $row->produk->nama_barang }}</td>
                        <td>{{ $row->kuantitas }}</td>
                        <td>{{ number_format($row->harga_produk,2) }}</td>
                        <td>{{ number_format($row->diskon_persen,2) }}</td>
                        <td>{{ number_format($row->diskon_nominal,2) }}</td>
                        <td>{{ number_format($row->potongan_admin,2) }}</td>
                        <td>{{ number_format($row->cashback,2) }}</td>
                        <td>{{ number_format($row->subtotal,2) }}</td>
                        <td>{{ $row->catatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" style="text-align: right;">TOTAL HARGA : </td>
                        <td colspan="3">{{ number_format($pesanan_penjualan->grandtotal,2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <div class="col-md-4">
                <table class="table table-bordered">
                    <tr>
                        <td>Total Harga</td>
                        <td>{{ number_format($pesanan_penjualan->grandtotal,2) }}</td>
                    </tr>
                    <tr>
                        <td>Diskon Global</td>
                        <td>{{ number_format($pesanan_penjualan->diskon_global,2) }}</td>
                    </tr>
                    <tr>
                        <td>PPn</td>
                        <td>{{ number_format($pesanan_penjualan->nilai_ppn,2) }}</td>
                    </tr>
                    <tr>
                        <td>Biaya kirim</td>
                        <td>{{ number_format($pesanan_penjualan->biaya_kirim,2) }}</td>
                    </tr>
                    <tr>
                        <td>Grand total</td>
                        <td>{{ number_format($pesanan_penjualan->grandtotal_setelah_diskon,2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
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

<!-- Button -->
<div class="card">
    <div class="card-body">
        <a href="{{ route('pesanan-penjualan.edit',$pesanan_penjualan->id) }}" class="btn btn-outline-warning">Ubah</a>
        <button class="btn btn-outline-danger btn-hapus" type="button">Hapus</button>
        <a href="{{ route('pesanan-penjualan.print',$pesanan_penjualan->id) }}" class="btn btn-outline-success">Print</a>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('pesanan-penjualan.destroy',$pesanan_penjualan->id) }}" method="post">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Peringatan</h4>
                    <ul>
                        <li>
                            Menghapus transaksi Pesanan Penjualan yang <strong>sudah diproses</strong> akan menghapus juga transaksi
                            Pengiriman dan Invoice
                        </li>
                        <li>
                            Harap koordinasi dengan departemen terkait sebelum menghapus data
                        </li>
                    </ul>
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

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        // modal Konfirmasi hapus data
        $('.btn-hapus').click(function(e) {
            $('#modalHapus').modal('show');
        });
    })
</script>
@endsection