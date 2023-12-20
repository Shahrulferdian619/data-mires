@extends('v2.layout.vuexy')

@section('content')

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<a href="{{ route('invoice-penjualan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

@if($invoice->status_proses == 0)
<div class="alert alert-danger" role="alert">Belum lunas</div>
@else
<div class="alert alert-success" role="alert">Sudah lunas</div>
@endif

<small>Dibuat pada : {{$invoice->created_at}}</small>
<br>
<small>Terakhir diperbarui : {{$invoice->updated_at}} </small>
<!-- Data Pelanggan -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Pelanggan
        </h5>
    </div>

    <div class="card-body">
        <label for="">Kode pelanggan</label>
        <input type="text" class="form-control" value="{{ $invoice->pelanggan->kode_pelanggan }}" disabled>

        <label for="">Nama pemesan</label>
        <input type="text" class="form-control" value="{{ $invoice->pelanggan->nama_pelanggan }}" disabled>

        <label for="">Alamat pemesan</label>
        <textarea cols="30" rows="5" class="form-control" disabled>{{ $invoice->pelanggan->detil_alamat }} {{ $invoice->pelanggan->kota }} {{ $invoice->pelanggan->provinsi }}</textarea>

        <hr>

        <label for="">Nama penerima</label>
        <input type="text" class="form-control" value="{{ $invoice->penerima }}" disabled>

        <label for="">Alamat penerima</label>
        <textarea cols="30" rows="5" class="form-control" disabled>{{ $invoice->alamat_penerima }}</textarea>
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
        <label for="">Nomer referensi</label>
        <input type="text" class="form-control" value="@if($invoice->pesanan == ''){{ $invoice->nomer_ref }}@else {{ $invoice->pesanan->nomer_pesanan_penjualan }}@endif" disabled>

        <label for="">Nomer invoice penjualan</label>
        <input type="text" class="form-control" value="{{ $invoice->nomer_invoice_penjualan }}" disabled>

        <label for="">Tanggal</label>
        <input name="tanggal" type="text" class="form-control" value="{{ date('d/m/Y', strtotime($invoice->tanggal)) }}" disabled>

        <label for="">Jenis penjualan</label>
        <input type="text" class="form-control" value="{{ $invoice->jenis_penjualan }}" disabled>

        <label for="">No. pesanan</label>
        <input name="nomer_pesanan" type="text" class="form-control" value="{{ $invoice->nomer_pesanan }}" disabled>

        <label for="">Sales</label>
        <input type="text" class="form-control" value="{{ $invoice->sales->nama_sales }}" disabled>

        <label for="">PPn</label>
        <input type="text" class="form-control" value="@if($invoice->ppn == 1) Ya @elseif($invoice->ppn == 2) Include ppn @else Tidak @endif" disabled>

        <label for="">Ekspedisi</label>
        <input type="text" class="form-control" value="{{ $invoice->ekspedisi }}" disabled>

        <label for="">Resi</label>
        <input name="resi" type="text" class="form-control" value="{{ $invoice->resi }}" disabled>

        <label for="">Keterangan</label>
        <textarea name="keterangan" cols="30" rows="5" class="form-control" disabled>{{ $invoice->keterangan }}</textarea>
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
        @if($invoice->gudang != null)
        <label for="">Diambil dari gudang</label>
        <input type="text" class="form-control" value="{{ $invoice->gudang->nama_gudang }}" disabled>
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
                    @foreach($invoice_rinci as $row)
                    <tr>
                        <td>{{ $row->produk->kode_barang }} | {{ $row->produk->nama_barang }}</td>
                        <td>{{ $row->kuantitas }}</td>
                        <td>{{ number_format($row->harga_produk,2) }}</td>
                        <td>{{ number_format($row->diskon_persen,2) }}</td>
                        <td>{{ number_format($row->diskon_nominal,2) }}</td>
                        <td>{{ number_format($row->potongan,2) }}</td>
                        <td>{{ number_format($row->cashback,2) }}</td>
                        <td>{{ number_format($row->subtotal,2) }}</td>
                        <td>{{ $row->catatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" style="text-align: right;">TOTAL HARGA : </td>
                        <td colspan="3">{{ number_format($invoice->grandtotal,2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <div class="col-md-4">
                <table class="table table-bordered">
                    <tr>
                        <td>Total Harga</td>
                        <td>{{ number_format($invoice->grandtotal,2) }}</td>
                    </tr>
                    <tr>
                        <td>Diskon Global</td>
                        <td>{{ number_format($invoice->diskon_global,2) }}</td>
                    </tr>
                    <tr>
                        <td>PPn</td>
                        <td>{{ number_format($invoice->nilai_ppn,2) }}</td>
                    </tr>
                    <tr>
                        <td>Biaya kirim</td>
                        <td>{{ number_format($invoice->biaya_kirim,2) }}</td>
                    </tr>
                    <tr>
                        <td>Grand total</td>
                        <td>{{ number_format($invoice->grandtotal_setelah_diskon,2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<br>

@if($invoice->berkas()->exists())
<!-- Data Berkas -->
<div class="card">
    <div class="card-header">
        <h5 class="m-0 me-2 card-title">
            Berkas
        </h5>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td style="width: 150px;">Berkas</td>
                <td>
                    <a href="{{ route('invoice-penjualan.download-berkas',$invoice->berkas->nama_berkas) }}" target="_blank">{{$invoice->berkas->nama_berkas}}</a>
                </td>
            </tr>
        </table>
    </div>
</div>

<br>
@endif

<!-- Button -->
<div class="card">
    <div class="card-body">
        <a href="{{ route('invoice-penjualan.edit',$invoice->id) }}" class="btn btn-outline-warning">
            Ubah
        </a>
        @if($invoice->jenis_penjualan == 'KONSINYASI')
        <button class="btn btn-outline-danger btn-hapus">Hapus</button>
        @endif
        <a href="{{ route('invoice-penjualan.print',$invoice->id) }}" class="btn btn-outline-success">Print Invoice</a>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('invoice-penjualan.destroy',$invoice->id) }}" method="post">
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
                            Apakah anda yakin menghapus transaksi ini?
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