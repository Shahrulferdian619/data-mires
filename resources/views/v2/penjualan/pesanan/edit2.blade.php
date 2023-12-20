@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
    }

    .table th {
        padding: 0.2rem;
    }
</style>
@endsection

@section('content')

@if($errors->any())
@include('v2.component.error')
@endif

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
    <ul>
        <li>Untuk meng-input transaksi Konsinyasi, sudah tidak dapat dilakukan dari sini.</li>
        <li>Per tanggal 9 Mei 2023, Data inputan SO sudah tidak masuk ke tabel SO lama.</li>
    </ul>
</div>

<a href="{{ route('pesanan-penjualan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pesanan-penjualan.update',$pesanan_penjualan->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- create versi 2 -->
    <!-- Data pelanggan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pelanggan & Pesanan
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <td>Pelanggan*</td>
                            <td>
                                <select onchange="pilihPelanggan(this)" name="pelanggan_id" class="form-control select2" required>
                                    <option value="{{ $pesanan_penjualan->pelanggan_id }}">{{ $pesanan_penjualan->pelanggan->nama_pelanggan }}</option>
                                    <option value="baru">-- PELANGGAN BARU --</option>
                                    @foreach($pelanggan as $row)
                                    <option value="{{ $row->id }}">{{$row->kode_pelanggan}} | {{$row->nama_pelanggan}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama pemesan</td>
                            <td>
                                <input name="nama_pelanggan" id="nama_pemesan" value="{{ $pesanan_penjualan->pelanggan->nama_pelanggan }}" type="text" class="form-control bg-light" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat pemesan </td>
                            <td>
                                <textarea name="alamat_pelanggan" id="alamat_pemesan" cols="30" rows="5" class="form-control bg-light" readonly>{{ $pesanan_penjualan->pelanggan->detil_alamat }} {{ $pesanan_penjualan->pelanggan->provinsi }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input onclick="samaPemesan()" id="sama_pemesan" class="form-check-input" type="checkbox" value="checked" />
                                <label class="form-check-label" for=""><b>Sama dengan pemesan</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama penerima*</td>
                            <td>
                                <input name="penerima" id="penerima" value="" type="text" class="form-control" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Alamat penerima* </td>
                            <td>
                                <textarea name="alamat_penerima" id="alamat_penerima" cols="30" rows="5" class="form-control" required></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <td>Nomer pesanan penjualan*</td>
                            <td>
                                <input name="nomer_pesanan_penjualan" type="text" class="form-control bg-light" value="{{ $pesanan_penjualan->nomer_pesanan_penjualan }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal*</td>
                            <td>
                                <input name="tanggal" type="date" class="form-control" value="{{ $pesanan_penjualan->tanggal }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Jenis penjualan*</td>
                            <td>
                                <select name="jenis_penjualan" class="form-control select2" required>
                                    <option value="{{ $pesanan_penjualan->jenis_penjualan }}">{{ $pesanan_penjualan->jenis_penjualan }}</option>
                                    @foreach($jenis_penjualan as $row)
                                    <option value="{{$row->jenis_penjualan}}">{{$row->jenis_penjualan}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomer pesanan</td>
                            <td>
                                <input name="nomer_pesanan" type="text" value="{{ $pesanan_penjualan->nomer_pesanan }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td>Sales*</td>
                            <td>
                                <select name="sales_id" class="form-control select2">
                                    <option value="{{ $pesanan_penjualan->sales_id }}">{{ $pesanan_penjualan->sales->nama_sales }}</option>
                                    @foreach($sales as $row)
                                    <option value="{{$row->id}}">{{$row->nama_sales}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>PPn 11%*</td>
                            <td>
                                <select name="ppn" class="form-control select-ppn" required>
                                    <option value="{{ $pesanan_penjualan->ppn }}">@if($pesanan_penjualan->ppn == 0)Tidak @elseif($pesanan_penjualan->ppn == 1) Ya @elseif($pesanan_penjualan->ppn == 2) Include ppn @endif</option>
                                    <option value="0">-- TIDAK --</option>
                                    <option value="1">-- YA --</option>
                                    <option value="2">-- INCLUDE --</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Ekspedisi</td>
                            <td>
                                <select name="ekspedisi" class="form-control select2">
                                    <option value="{{ $pesanan_penjualan->ekspedisi }}">{{ $pesanan_penjualan->ekspedisi }}</option>
                                    @foreach($ekspedisi as $row)
                                    <option value="{{$row->nama_ekspedisi}}">{{$row->nama_ekspedisi}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Resi</td>
                            <td>
                                <input name="resi" type="text" value="{{ $pesanan_penjualan->resi }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>
                                <textarea name="keterangan" cols="30" rows="5" class="form-control">{{ $pesanan_penjualan->keterangan }}</textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Rincian Produk -->
    <div class="card">
        <div class="card-header pb-3">
            <div class="col-12 d-flex justify-content-between">
                <h5 class="m-0 me-2 card-title">
                    Rincian Produk
                </h5>
                <button class="btn btn-sm btn-outline-warning btn-modal-paket" type="button">tambah paket</button>
            </div>
        </div>

        <div class="card-body">
            <label for="">Diambil dari gudang (<u>pastikan gudang sudah sesuai</u>)</label>
            <select name="gudang_id" class="form-control select2" required>
                <option value="{{ $pesanan_penjualan->gudang_id }}">{{ $pesanan_penjualan->gudang->nama_gudang }}</option>
                @foreach($gudang as $g)
                <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                @endforeach
            </select>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-info" style="text-align:center;">
                        <tr>
                            <th style="width: 300px;">produk</th>
                            <th style="width: 100px;">qty</th>
                            <th style="width: 200px;">harga</th>
                            <th style="width: 200px;">
                                dsc(%) <br>
                                dsc(Rp)
                            </th>
                            <th style="width: 200px;">
                                potongan <br>
                                cashback
                            </th>
                            <th style="width: 200px;">subtotal</th>
                            <th style="width: 200px;">catatan</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody class="rincian-produk">
                        @foreach($pesanan_penjualan_rinci as $rinci)
                        <tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 select-produk" required>
                                    <option value="{{ $rinci->produk_id }}">{{ $rinci->produk->nama_barang }}</option>
                                    @foreach($produk as $row)
                                    <option value="{{$row->id}}">{{$row->kode_barang}} | {{$row->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input name="kuantitas[]" type="number" class="form-control kuantitas" value="{{ $rinci->kuantitas }}" required>
                            </td>

                            <td>
                                <input name="harga_produk[]" type="text" id="harga-produk-0" class="form-control harga-produk" value="{{ $rinci->harga_produk }}" required>
                            </td>

                            <td>
                                <input name="diskon_persen[]" type="number" min="0" step="0.1" max="100" class="form-control diskon-persen" value="{{ $rinci->diskon_persen }}">
                                <input name="diskon_nominal[]" type="text" id="diskon-nominal-0" class="form-control diskon-nominal bg-light" value="" readonly>
                            </td>

                            <td>
                                <input name="potongan[]" type="text" id="potongan-0" class="form-control potongan" value="{{ $rinci->potongan_admin }}">
                                <input name="cashback[]" type="text" id="cashback-0" class="form-control cashback" value="{{ $rinci->cashback }}">
                            </td>

                            <td>
                                <input name="subtotal[]" type="text" id="subtotal-0" class="form-control subtotal bg-light" value="0" readonly>
                            </td>

                            <td>
                                <textarea name="catatan[]" id="" cols="30" rows="4" class="form-control">{{ $rinci->catatan }}</textarea>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align: right;">Total : </td>
                            <td>
                                <input type="text" name="" id="" class="form-control total-harga bg-light">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success btn-tambah-row">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width: 100%;">
                                <tr>
                                    <td style="width: 300px;">Total Harga</td>
                                    <td>
                                        <input name="grandtotal" type="text" class="form-control total-harga bg-light">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Diskon persen (isikan 0 bila tanpa diskon)</td>
                                    <td>
                                        <input type="number" name="diskon_persen_global" class="form-control diskon-persen-global" value="{{ $pesanan_penjualan->diskon_persen }}" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Diskon nominal (global)</td>
                                    <td>
                                        <input name="diskon_nominal_global" type="text" class="form-control diskon-nominal-global bg-light" value="0" required readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Harga setelah diskon</td>
                                    <td>
                                        <input type="text" class="form-control total-setelah-diskon bg-light" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PPn</td>
                                    <td>
                                        <input name="nilai_ppn" type="text" class="form-control nilai-ppn bg-light" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total setelah PPn</td>
                                    <td>
                                        <input type="text" class="form-control total-setelah-ppn">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Biaya kirim (isikan bila ditagihkan ke customer)</td>
                                    <td>
                                        <input type="text" name="biaya_kirim" class="form-control biaya-kirim" value="{{ $pesanan_penjualan->biaya_kirim }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Grand total</td>
                                    <td>
                                        <input name="grandtotal_setelah_diskon" type="text" class="form-control grandtotal-setelah-diskon bg-light" readonly>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <br>
                    </div>

                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="width: 300px;">Akun Diskon Penjualan</td>
                                    <td>
                                        <select name="akun_diskon" id="akun_diskon" class="form-control select2" required>
                                            <option value="247">Diskon penjualan</option>
                                            @foreach($akun_diskon as $r)
                                            <option value="{{ $r->id }}">{{ $r->nomer_coa }} | {{ $r->nama_coa }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Akun PPn Keluaran</td>
                                    <td>
                                        <select name="akun_ppn" id="akun_ppn" class="form-control select2" required>
                                            <option value="177">PPn Keluaran</option>
                                            @foreach($akun_ppn as $r)
                                            <option value="{{ $r->id }}">{{ $r->nomer_coa }} | {{ $r->nama_coa }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Akun Biaya Kirim</td>
                                    <td>
                                        <select name="akun_biayakirim" id="akun_biayakirim" class="form-control select2" required>
                                            <option value="190">Biaya Kirim</option>
                                            @foreach($akun_biayakirim as $r)
                                            <option value="{{ $r->id }}">{{ $r->nomer_coa }} | {{ $r->nama_coa }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('pesanan-penjualan.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

<!-- Modal tambah pelanggan baru direct -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('api.store-pelanggan') }}" method="post" style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Pelanggan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Nama</label>
                    <input name="nama_pelanggan" type="text" class="form-control" required>

                    <label for="">No. Handphone</label>
                    <input name="handphone_pelanggan" type="text" class="form-control" required>

                    <label for="">Provinsi</label>
                    <select onchange="pilihProvinsi(this)" name="provinsi" class="form-control" required>
                        <option value="">-- PILIH PROVINSI --</option>
                        @foreach($provinsi as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>

                    <label for="">Kota</label>
                    <select name="kota" id="pilih_kota" class="form-control" required>
                        <option value="">-- PILIH KOTA --</option>
                    </select>

                    <label for="">Detil alamat</label>
                    <textarea name="detail_alamat" cols="30" rows="5" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button id="simpan_pelanggan" type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal tambah paket -->
<div class="modal fade" id="modalPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="">Paket produk</label>
                            <select name="paket_id" class="form-control">
                                <option value="">-- PILIH PAKET --</option>
                                @foreach($paket as $row)
                                <option value="{{ $row->id }}">{{ $row->packet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Dsc %</label>
                            <input name="diskon_persen_paket" type="number" class="form-control" value="0">
                        </div>
                        <div class="col-md-2">
                            <label for="">Qty</label>
                            <input name="qty_paket" type="number" class="form-control" value="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary btn-tambah-paket">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Alert -->
<div class="modal fade" id="modalAlert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">INFORMASI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Jenis Penjualan <strong>KONSINYASI</strong> sudah tidak ada dalam update versi2.
                        Untuk melakukan transaksi <strong>KONSINYASI</strong> silahkan gunakan versi sebelumnya atau pada
                        menu baru Permintaan Konsinyasi.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {

        let index = 0; // define row produk

        inputRibuan([
            '#potongan-' + index,
            '#cashback-' + index,
            '.biaya-kirim'
        ]);

        const $tableProduk = $('.rincian-produk');
        const $btnTambahRow = $('.btn-tambah-row');
        const $totalHarga = $('.total-harga');
        const $grandTotal = $('.grandtotal');

        $('.select2').select2();

        // BARIS KODE UNTUK RUMUS RINCIAN PRODUK //
        // menambah baris rincian produk
        $btnTambahRow.click(function() {
            index++;
            const row = `<tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 select-produk" required>
                                    <option value="">-- CARI PRODUK --</option>
                                    @foreach($produk as $row)
                                    <option value="{{$row->id}}">{{$row->kode_barang}} | {{$row->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input name="kuantitas[]" type="number" class="form-control kuantitas" value="1" required>
                            </td>

                            <td>
                                <input name="harga_produk[]" type="text" id="harga-produk-${index}" class="form-control harga-produk" required>
                            </td>

                            <td>
                                <input name="diskon_persen[]" type="number" min="0" step="0.1" max="100" class="form-control diskon-persen" value="0">
                                <input name="diskon_nominal[]" type="text" id="diskon-nominal-${index}" class="form-control diskon-nominal" value="0" readonly style="background-color: #ebebeb;">
                            </td>

                            <td>
                                <input name="potongan[]" type="text" id="potongan-${index}" class="form-control potongan" value="0">
                                <input name="cashback[]" type="text" id="cashback-${index}" class="form-control cashback" value="0">
                            </td>

                            <td>
                                <input name="subtotal[]" type="text" id="subtotal-${index}" class="form-control subtotal bg-light" value="0" readonly>
                            </td>

                            <td>
                                <textarea name="catatan[]" id="" cols="30" rows="4" class="form-control"></textarea>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;

            $tableProduk.append(row);
            $('.select2').select2();
            inputRibuan([
                '#potongan-' + index,
                '#cashback-' + index,
            ]);
        });

        // menghapus baris rincian produk
        $(document).on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            hitungGrandTotal();
        });

        // select produk untuk mengambil harga produk
        $(document).on('change', '.select-produk', function() {
            const produk_id = $(this).val();
            const $row = $(this).closest('tr');

            $.ajax({
                method: 'get',
                url: '/v2/getDataProduk/' + produk_id,
                dataType: 'json',
                success: function(data) {
                    $row.find('.harga-produk').val(data.harga_barang1);
                    hitungGrandTotal();
                }
            });
        });

        // event ketika ada perubahan input pada harga, kuantitas, diskon persen
        // potongan, cashback, diskon persen global, biaya kirim
        $(document).on('input', '.diskon-persen, .harga-produk, .potongan, .cashback, .biaya-kirim, .diskon-persen-global', function(e) {
            hitungGrandTotal();
        });

        // event ketika ada perubahan select-ppn
        $(document).on('change', '.select-ppn', function() {
            hitungGrandTotal();
        });

        // rumus untuk menghitung diskon produk
        function hitungDiskonProduk() {
            $tableProduk.find('tr').each(function() {
                const $row = $(this);
                const harga_produk = $row.find('.harga-produk').val();
                const diskon_persen = $row.find('.diskon-persen').val();
                const diskon_nominal = convertDouble(harga_produk) * diskon_persen / 100;

                // fill diskon nominal otomatis
                $row.find('.diskon-nominal').val(formatRibuan(diskon_nominal));
            });
        }

        // rumus untuk menghitung subtotal setiap produk
        function hitungSubtotalProduk() {
            $tableProduk.find('tr').each(function() {
                const $row = $(this);
                const harga_produk = convertDouble($row.find('.harga-produk').val());
                const kuantitas = $row.find('.kuantitas').val();
                const diskon_nominal = convertDouble($row.find('.diskon-nominal').val());
                const potongan = convertDouble($row.find('.potongan').val());
                const cashback = convertDouble($row.find('.cashback').val());
                const subtotal = (harga_produk - diskon_nominal) * kuantitas - potongan + cashback;

                // autofill subtotal
                $row.find('.subtotal').val(formatRibuan(subtotal));
            });
        }

        // rumus menghitung total dari setiap subtotal produk
        function hitungTotal() {
            let total = 0;

            $tableProduk.find('tr').each(function() {
                const $row = $(this);
                const subtotal = convertDouble($row.find('.subtotal').val());
                total += subtotal;
            });

            // autofill total harga
            $('.total-harga').val(formatRibuan(total));
        }

        // rumus untuk menghitung diskon persen global
        function hitungDiskonGlobal() {
            const diskon_persen_global = $('.diskon-persen-global').val();
            const total = convertDouble($('.total-harga').val());
            const diskon_nominal_global = total * diskon_persen_global / 100;

            // autofill diskon nominal global & total setelah diskon
            $('.diskon-nominal-global').val(formatRibuan(diskon_nominal_global));
            $('.total-setelah-diskon').val(formatRibuan(total - diskon_nominal_global)); // total - diskon nominal global
        }

        // rumus menghitung grandtotal + ppn
        function hitungGrandTotal() {

            // panggil hitung diskon produk
            // panggil hitung subtotal
            // panggil hitung total
            // panggil hitung diskon global
            hitungDiskonProduk();
            hitungSubtotalProduk();
            hitungTotal();
            hitungDiskonGlobal();

            let ppn = $('.select-ppn').val();
            let nilai_ppn = 0;

            let grandtotal = convertDouble($('.total-harga').val());
            let grandtotal_setelah_diskon = convertDouble($('.total-setelah-diskon').val());

            let biaya_kirim = convertDouble($('.biaya-kirim').val());

            if (ppn == 1) {
                nilai_ppn = grandtotal_setelah_diskon * 11 / 100;
                $('.nilai-ppn').val(formatRibuan(nilai_ppn));
                grandtotal_setelah_diskon += nilai_ppn;
            } else if (ppn == 2) {
                nilai_ppn = grandtotal_setelah_diskon - (grandtotal_setelah_diskon / 1.11);
                $('.nilai-ppn').val(formatRibuan(nilai_ppn));
            }

            // autofill total setelah ppn & grandtotal
            $('.total-setelah-ppn').val(formatRibuan(grandtotal_setelah_diskon));
            $('.grandtotal-setelah-diskon').val(formatRibuan(grandtotal_setelah_diskon + biaya_kirim));
        }

        // END OF BARIS KODE UNTUK RUMUS RINCIAN PRODUK //

        // modal tambah paket
        $('.btn-modal-paket').on('click', function() {
            $('#modalPaket').modal('show');
            hitungTotal();
        });

        // proses tambah paket produk
        $('.btn-tambah-paket').on('click', function() {
            $('#modalPaket').modal('hide');

            const paket_id = $('select[name="paket_id"]').val();
            const qty_paket = $('input[name="qty_paket"]').val();
            const dsc_paket = $('input[name="diskon_persen_paket"]').val();

            $.ajax({
                type: 'get',
                url: '/api/v2/getRincianPaket?paket_id=' + paket_id,
                dataType: 'json',
                success: function(data) {
                    // console.log(data);

                    for (i = 0; i < data.jumlah; i++) {
                        let rowPaket = `<tr>
                                            <td class="bg-light">${data[i].barang.nama_barang}</td>
                                            <td class="bg-light">
                                                <input name="produk_id[]" type="hidden" value="${data[i].id_barang}">
                                                <input name="kuantitas[]" type="text" class="form-control kuantitas" value="${qty_paket}">
                                            </td>
                                            <td class="bg-light">
                                                <input name="harga_produk[]" type="text" class="form-control harga-produk" value="${data[i].harga}">
                                            </td>
                                            <td class="bg-light">
                                                <input name="diskon_persen[]" type="text" class="form-control diskon-persen" value="${dsc_paket}">
                                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="0">
                                            </td>
                                            <td class="bg-light">
                                                <input name="potongan[]" type="text" class="form-control potongan" value="0">
                                                <input name="cashback[]" type="text" class="form-control cashback" value="0">
                                            </td>
                                            <td class="bg-light">
                                                <input name="subtotal[]" type="text" class="form-control subtotal bg-light" value="0">
                                            </td>
                                            <td class="bg-light">${data.nama_paket}
                                                <input name="catatan[]" class="form-control" type="hidden" value="${data.nama_paket}">
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`;


                        $tableProduk.append(rowPaket);
                        hitungGrandTotal();
                    }
                }
            });

        });

        // validasi kode pelanggan
        $("#modal_kode_pelanggan").keyup(function() {

            let kode_pelanggan = $("#modal_kode_pelanggan").val();

            $.ajax({
                method: 'get',
                url: '/api/v2/getKodePelanggan/' + kode_pelanggan,
                dataType: 'json',
                success: function(data) {

                    if (data.kode_pelanggan != undefined) {
                        $("#sudah_ada").attr('hidden', false);
                        $("#simpan_pelanggan").attr('disabled', true);
                        $("#modal_kode_pelanggan").addClass('is-invalid');
                    } else {
                        $("#sudah_ada").attr('hidden', true);
                        $("#simpan_pelanggan").attr('disabled', false);
                        $("#modal_kode_pelanggan").removeClass('is-invalid');
                    }

                }
            })

        });

        hitungGrandTotal();
    });

    function pilihPelanggan(pelanggan) {

        // tampilkan modal pelanggan baru
        // apabila pilih pelanggan baru
        if (pelanggan.value == 'baru') {

            $("#modalPelanggan").modal('show');

        } else {

            // get data pelanggan
            $.ajax({
                method: 'get',
                url: '/api/v2/getPelanggan/' + pelanggan.value,
                dataType: 'json',
                success: function(data) {

                    $("#kode_pelanggan").val(data.kode_pelanggan);
                    $("#nama_pemesan").val(data.nama_pelanggan);
                    $("#alamat_pemesan").val(data.detil_alamat + ' ' + data.kota + ' ' + data.provinsi);

                }
            })
        }
    }

    function samaPemesan() {
        if ($("#sama_pemesan").is(":checked")) {
            $('#penerima').val($('#nama_pemesan').val());
            $('#alamat_penerima').val($('#alamat_pemesan').val());

            $('#penerima').addClass('bg-light');
            $('#alamat_penerima').addClass('bg-light');
        } else {
            $('#penerima').val('');
            $('#alamat_penerima').val('');

            $('#penerima').removeClass('bg-light');
            $('#alamat_penerima').removeClass('bg-light');
        }
    }

    function pilihProvinsi(provinsi) {

        $.ajax({
            method: 'get',
            url: '/api/v2/getKotaByProv/' + provinsi.value,
            dataType: 'json',
            success: function(data) {

                let city = '';

                data.map((item, index) => {
                    city += `<option value="${item.name}">${item.name}</option>`
                });

                $("#pilih_kota").html(city);

            }
        });

    }

    function tambahPelanggan() {

        let kode_pelanggan = $("#modal_kode").val();

        $.ajax({
            method: 'post',
            url: '/api/v2/getKodePelanggan/',
            data: {
                kode: kode_pelanggan,
            },
            dataType: 'json',
            success: function(data) {

            }
        })

    }
</script>
@endsection