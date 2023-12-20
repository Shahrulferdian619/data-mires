@extends('v2.layout.vuexy')

@section('content')

@if($errors->any())
@include('v2.component.error')
@endif

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<a href="{{ route('konsinyasi.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

@if($konsinyasi->status_proses == 0)
<div class="alert alert-warning" role="alert">Belum dikirim/proses</div>
@else
<div class="alert alert-success" role="alert">Sudah dikirim/proses</div>
@endif

<small>Dibuat pada : {{ $konsinyasi->created_at }} </small>
<br>
<small>Terakhir diperbarui : {{ $konsinyasi->updated_at }}</small>
<!-- Header Konsinyasi -->
<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Pelanggan
        </h5>
    </div>

    <div class="card-body">
        <label for="">Kode pelanggan</label>
        <input type="text" class="form-control" value="{{ $konsinyasi->pelanggan->kode_pelanggan }}" disabled>

        <label for="">Nama pelanggan</label>
        <input type="text" class="form-control" value="{{ $konsinyasi->pelanggan->nama_pelanggan }}" disabled>

        <label for="">Alamat pelanggan</label>
        <textarea name="" id="" cols="30" rows="5" class="form-control" disabled>{{ $konsinyasi->pelanggan->detil_alamat }} {{ $konsinyasi->pelanggan->kota }} {{ $konsinyasi->pelanggan->provinsi }}</textarea>
    </div>
</div>

<br>

<div class="card">
    <div class="card-header pb-3">
        <h5 class="m-0 me-2 card-title">
            Data Konsinyasi
        </h5>
    </div>

    <div class="card-body">
        <label for="">Gudang Asal</label>
        <input type="text" class="form-control" value="{{ $konsinyasi->gudang_asal }}" disabled>

        <label for="">Gudang Tujuan</label>
        <input type="text" class="form-control" value="{{ $konsinyasi->gudang_tujuan }}" disabled>

        <label for="">Nomer konsinyasi</label>
        <input type="text" class="form-control" value="{{ $konsinyasi->nomer_konsinyasi }}" disabled>

        <label for="">Tanggal konsinyasi</label>
        <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($konsinyasi->tanggal_konsinyasi)) }}" disabled>

        <label for="">Keterangan</label>
        <textarea name="keterangan" cols="30" rows="5" class="form-control" disabled>{{ $konsinyasi->keterangan }}</textarea>

        <label for="">Penerima</label>
        <input type="text" class="form-control" value="{{ $konsinyasi->penerima }}" disabled>

        <label for="">Alamat penerima</label>
        <textarea name="" id="" cols="30" rows="5" class="form-control" disabled>{{ $konsinyasi->alamat_penerima }}</textarea>
    </div>
</div>

<br>

<!-- Rincian Produk -->
<div class="card">
    <div class="card-header pb-3">
        <div class="col-12 d-flex justify-content-between">
            <h5 class="m-0 me-2 card-title">
                Rincian Produk
            </h5>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 400px;">produk</th>
                        <th style="width: 50px;">qty</th>
                        <th>harga</th>
                        <th>subtotal</th>
                        <th>catatan</th>
                    </tr>
                </thead>
                <tbody class="rincian-produk">
                    @foreach($konsinyasi->rinci as $r)
                    <tr>
                        <td>{{ $r->produk->kode_barang }} | {{ $r->produk->nama_barang }}</td>
                        <td>{{ $r->kuantitas }}</td>
                        <td>{{ number_format($r->harga,2) }}</td>
                        <td>{{ number_format($r->subtotal,2) }}</td>
                        <td>{{ $r->catatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;">Total</td>
                        <td colspan="3">{{ number_format($konsinyasi->grandtotal,2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<br>

@if(!empty($konsinyasi->berkas->berkas1))
<!-- Berkas -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td style="width: 100px;">Berkas</td>
                <td>
                    <a href="{{ route('konsinyasi.download-berkas',$konsinyasi->berkas->berkas1) }}">{{ $konsinyasi->berkas->berkas1 }}</a>
                </td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Button Simpan -->
<div class="card">
    <div class="card-body">
        <a href="{{ route('konsinyasi.edit',$konsinyasi->id) }}" class="btn btn-outline-warning">
            Ubah
        </a>
        <button class="btn btn-outline-danger btn-hapus">Hapus</button>
        <a href="{{ route('konsinyasi.print',$konsinyasi->id) }}" class="btn btn-outline-success">Print Konsinyasi</a>
        @if(Auth::user()->position == 'gudang' || Auth::user()->name == 'Super Admin')
            @if($konsinyasi->status_proses == 0)
            <button class="btn btn-outline-success btn-proses-kirim">Proses Kirim</button>
            @else 
            <a href="{{ route('konsinyasi.print-sj',$konsinyasi->id) }}" class="btn btn-outline-success">Print Surat Jalan</a>
            @endif
        @endif
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('konsinyasi.destroy',$konsinyasi->id) }}" method="post">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Data Konsinyasi yang sudah diproses hanya bisa dihapus oleh SPV</h5>
                    <h5>Apakah anda yakin menghapus data ini?</h5>
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
        <form style="width: 100%;" action="{{ route('konsinyasi.proses-kirim',$konsinyasi->id) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Proses Kirim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Apakah Anda yakin akan memproses pengiriman data ini?</h4>
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
        // modal hapus data
        $('.btn-hapus').on('click', function(e) {
            $('#modalHapus').modal('show');
        });

        // modal konfirmasi proses kirim
        $('.btn-proses-kirim').on('click',function(e) {
            $('#modalProsesKirim').modal('show');
        });
    });
</script>
@endsection