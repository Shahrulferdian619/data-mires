@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
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

<form action="{{route('pesanan-penjualan.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Data Pelanggan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pelanggan
            </h5>
        </div>

        <div class="card-body">
            <label for="">Pelanggan</label>
            <select onchange="pilihPelanggan(this)" name="pelanggan_id" class="form-control select2" required>
                <option value="">-- PILIH PELANGGAN --</option>
                <option value="baru">-- PELANGGAN BARU --</option>
                @foreach($pelanggan as $row)
                <option value="{{ $row->id }}">{{$row->kode_pelanggan}} | {{$row->nama_pelanggan}}</option>
                @endforeach
            </select>

            <label for="">Kode pelanggan</label>
            <input id="kode_pelanggan" value="" type="text" class="form-control" readonly>

            <label for="">Nama pemesan</label>
            <input name="nama_pelanggan" id="nama_pemesan" value="" type="text" class="form-control" readonly>

            <label for="">Alamat pemesan</label>
            <textarea name="alamat_pelanggan" id="alamat_pemesan" cols="30" rows="5" class="form-control"></textarea>

            <hr>

            <div class="form-check form-check-inline">
                <input onclick="samaPemesan()" id="sama_pemesan" class="form-check-input" type="checkbox" value="checked" />
                <label class="form-check-label" for=""><b>Sama dengan pemesan</b></label>
            </div>

            <br>

            <label for="">Nama penerima</label>
            <input name="penerima" id="penerima" value="" type="text" class="form-control" required>

            <label for="">Alamat penerima</label>
            <textarea name="alamat_penerima" id="alamat_penerima" cols="30" rows="5" class="form-control" required></textarea>
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
            <label for="">Bank penerima</label>
            <select name="akun_bank" class="form-control select2" required>
                <option value="">-- PILIH BANK --</option>
                @foreach($akun_bank as $b)
                <option value="{{ $b->id }}">{{ $b->nomer_coa }} | {{ $b->nama_coa }}</option>
                @endforeach
            </select>

            <label for="">Nomer pesanan penjualan</label>
            <input name="nomer_pesanan_penjualan" type="text" class="form-control" value="{{$nomer}}" readonly>

            <label for="">Tanggal</label>
            <input name="tanggal" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>

            <label for="">Jenis penjualan</label>
            <select name="jenis_penjualan" class="form-control select2" required>
                <option value="">-- JENIS PENJUALAN --</option>
                @foreach($jenis_penjualan as $row)
                <option value="{{$row->jenis_penjualan}}">{{$row->jenis_penjualan}}</option>
                @endforeach
            </select>

            <label for="">No. pesanan</label>
            <input name="nomer_pesanan" type="text" class="form-control">

            <label for="">Sales</label>
            <select name="sales_id" class="form-control select2">
                <option value="1000">-- DIRECT --</option>
                @foreach($sales as $row)
                <option value="{{$row->id}}">{{$row->nama_sales}}</option>
                @endforeach
            </select>

            <label for="">PPn 11%</label>
            <select name="ppn" class="form-control select-ppn" required>
                <option value="2">-- INCLUDE PPN --</option>
                <option value="0">-- TIDAK --</option>
                <option value="1">-- YA --</option>
            </select>

            <label for="">Ekspedisi</label>
            <select name="ekspedisi" class="form-control select2">
                <option value="">-- EKSPEDISI --</option>
                @foreach($ekspedisi as $row)
                <option value="{{$row->nama_ekspedisi}}">{{$row->nama_ekspedisi}}</option>
                @endforeach
            </select>

            <label for="">Resi</label>
            <input name="resi" type="text" class="form-control">

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control"></textarea>

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
                <option value="">-- PILIH GUDANG --</option>
                @foreach($gudang as $g)
                <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                @endforeach
            </select>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 300px;">produk</th>
                            <th style="width: 80px;">qty</th>
                            <th style="width: 300px;">harga</th>
                            <th>dsc(%)</th>
                            <th style="width: 200px;">dsc(Rp)</th>
                            <th style="width: 100px;">potongan</th>
                            <th style="width: 100px;">cashback</th>
                            <th style="width: 200px;">subtotal</th>
                            <th>catatan</th>
                            <th>
                                <button type="button" class="btn btn-sm btn-success btn-tambah-row">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="rincian-produk">
                        <tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 selectProduk" required>
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
                                <input name="harga_produk[]" type="number" class="form-control harga-produk" required>
                            </td>

                            <td>
                                <input name="diskon_persen[]" type="number" class="form-control diskon-persen" value="0">
                            </td>

                            <td>
                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="0" readonly style="background-color: #ebebeb;">
                            </td>

                            <td>
                                <input name="potongan[]" type="number" class="form-control potongan" value="0">
                            </td>

                            <td>
                                <input name="cashback[]" type="number" class="form-control cashback" value="0">
                            </td>

                            <td>
                                <input type="text" class="form-control subtotal-konversi" value="0" readonly style="background-color: #ebebeb;">
                                <input name="subtotal[]" type="text" class="form-control subtotal" value="0" readonly hidden>
                            </td>

                            <td>
                                <input name="catatan[]" type="text" class="form-control">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" style="text-align: right;">TOTAL HARGA : </td>
                            <td colspan="3">
                                <input type="text" class="form-control total-harga" readonly style="background-color: #ebebeb;">
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
                                    <td style="width: 200px;">Total Harga</td>
                                    <td>
                                        <input type="text" class="form-control total-harga" style="background-color: #ebebeb;">
                                        <input name="grandtotal" type="text" class="total-harga-hidden" hidden>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Diskon persen (isikan 0 bila tanpa diskon)</td>
                                    <td>
                                        <input type="number" name="diskon_persen_global" class="form-control diskon-persen-global" value="0" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Diskon nominal (global)</td>
                                    <td>
                                        <input name="diskon_global" type="text" class="form-control diskon-global" value="0" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Harga setelah diskon</td>
                                    <td>
                                        <input type="text" class="form-control total-harga-setelah-diskon" readonly style="background-color: #ebebeb;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>PPn</td>
                                    <td>
                                        <input type="text" class="form-control ppn" value="0" readonly style="background-color: #ebebeb;">
                                        <input name="nilai_ppn" type="text" class="form-control nilai-ppn" hidden>
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
                                        <input type="number" name="biaya_kirim" class="form-control biaya-kirim" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Grand total</td>
                                    <td>
                                        <input type="text" class="form-control grandtotal" readonly style="background-color: #ebebeb;">
                                        <input name="grandtotal_setelah_diskon" type="text" class="grandtotal-hidden" hidden>
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
                                    <td style="width: 200px;">Akun Diskon Penjualan</td>
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

                        <br>
                        <button class="btn btn-sm btn-info btn-hitung" type="button">
                            <i class="fa fa-calculator"></i>-Hitung
                        </button>
                        <label for="">klik Hitung dulu sebelum klik <strong>Simpan</strong></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Berkas -->
    <!-- <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Berkas
            </h5>
        </div>

        <div class="card-body">
            <label for="">Berkas 1</label>
            <input name="berkas1" type="file" class="form-control">

            <label for="">Berkas 2</label>
            <input name="berkas2" type="file" class="form-control">

            <label for="">Berkas 3</label>
            <input name="berkas3" type="file" class="form-control">

            <label for="">Berkas 4</label>
            <input name="berkas4" type="file" class="form-control">

            <label for="">Berkas 5</label>
            <input name="berkas5" type="file" class="form-control">
        </div>
    </div>

    <br> -->

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit" disabled>Simpan</button>
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
    let produk = 1;

    $(document).ready(function() {

        const $tableProduk = $('.rincian-produk');
        const $btnTambahRow = $('.btn-tambah-row');
        const $totalHarga = $('.total-harga');
        const $grandTotal = $('.grandtotal');

        $('.select2').select2();

        // fungsi untuk menghitung diskon produk (satuan)
        function hitungDiskon() {
            //console.log('cek');
            let diskon = 0;
            $tableProduk.find('tr').each(function() {
                const $row = $(this);
                const harga_produk = $row.find('.harga-produk').val();
                const diskon_persen = $row.find('.diskon-persen').val();
                const diskon = harga_produk * diskon_persen / 100;
                $row.find('.diskon-nominal').val(diskon);
            });
        }

        function hitungDiskonGlobal() {
            let diskon_nominal_global = 0;

            const diskon_persen_global = $('.diskon-persen-global').val();
            diskon_nominal_global = $('.total-harga-hidden').val() * diskon_persen_global / 100;
            $('.diskon-global').val(diskon_nominal_global);
        }

        // fungsi untuk menghitung subtotal dan grandtotal
        function hitungTotal() {
            const ppn = $('select[name="ppn"]').val();

            let biaya_kirim = 0;
            let totalHarga = 0;
            let diskonGlobal = 0;
            let afterDiskonGlobal = 0;

            $tableProduk.find('tr').each(function() {
                const $row = $(this);
                const harga_produk = $row.find('.harga-produk').val();
                const kuantitas = $row.find('.kuantitas').val();
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const potongan = $row.find('.potongan').val();
                const cashback = $row.find('.cashback').val();

                const sebelumCashback = ((harga_produk - diskon_nominal) * kuantitas) - potongan;
                const subtotal = sebelumCashback + parseFloat(cashback);

                $row.find('.subtotal-konversi').val(formatRupiah(subtotal));
                $row.find('.subtotal').val(subtotal);
                totalHarga += subtotal;
            });

            $totalHarga.val(formatRupiah(totalHarga));
            $('.total-harga-hidden').val(totalHarga);

            diskonGlobal = $('.diskon-global').val();
            afterDiskonGlobal = totalHarga - diskonGlobal;

            $('.total-harga-setelah-diskon').val(formatRupiah(afterDiskonGlobal));

            if (ppn == 1) {
                let nominal_ppn = afterDiskonGlobal * 11 / 100;
                afterDiskonGlobal = afterDiskonGlobal + nominal_ppn;
                $('.ppn').val(formatRupiah(nominal_ppn));
                $('.nilai-ppn').val(nominal_ppn);
            } else if (ppn == 0) {
                $('.ppn').val(0);
                $('.nilai-ppn').val(0);
            } else if (ppn == 2) {
                let nominal_ppn = afterDiskonGlobal - (afterDiskonGlobal / (1.11));
                $('.ppn').val(formatRupiah(nominal_ppn));
                $('.nilai-ppn').val(nominal_ppn);
            }

            $('.total-setelah-ppn').val(formatRupiah(afterDiskonGlobal));

            afterDiskonGlobal = afterDiskonGlobal + parseFloat($('.biaya-kirim').val());

            $grandTotal.val(formatRupiah(afterDiskonGlobal));
            $('.grandtotal-hidden').val(afterDiskonGlobal);
        }

        // sebelum klik simpan, hitung ulang semuanya lagi
        $('.btn-simpan').click(function(e) {
            hitungTotal();
        });

        // menghitung ppn
        $('.select-ppn').on('change', function(e) {
            console.log($(this).val());
            hitungTotal();
        });

        // menambah baris rincian produk
        $btnTambahRow.click(function() {
            const row = `<tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 selectProduk" required>
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
                                <input name="harga_produk[]" type="number" class="form-control harga-produk" required>
                            </td>

                            <td>
                                <input name="diskon_persen[]" type="number" class="form-control diskon-persen" value="0">
                            </td>

                            <td>
                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="0" readonly style="background-color: #ebebeb;">
                            </td>

                            <td>
                                <input name="potongan[]" type="text" class="form-control potongan" value="0">
                            </td>

                            <td>
                                <input name="cashback[]" type="text" class="form-control cashback" value="0">
                            </td>

                            <td>
                                <input type="text" class="form-control subtotal-konversi" value="0" readonly style="background-color: #ebebeb;">
                                <input name="subtotal[]" type="text" class="form-control subtotal" value="0" readonly hidden>
                            </td>

                            <td>
                                <input name="catatan[]" type="text" class="form-control">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;

            $tableProduk.append(row);
            $('.select2').select2();

            hitungTotal();
        });

        // menghapus baris rincian produk
        $(document).on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            hitungTotal();
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

        // menghitung subtotal dan grandtotal ketika inputan berubah
        $(document).on('input', '.kuantitas, .harga-produk, .diskon-persen, .diskon-nominal, .potongan, .cashback, .biaya-kirim', function(e) {
            //console.log($(this).val());
            hitungDiskon();
            hitungTotal();
        });

        // mengkonversi diskon persen ke nominal
        $(document).on('input', '.diskon-persen', function(e) {
            //console.log($(this).val());
            hitungDiskon();
        });

        // mengkonversi diskon persen global ke nominal
        $(document).on('input', '.diskon-persen-global', function(e) {
            hitungDiskonGlobal();
        });

        // menghitung ulang semua
        $('.btn-hitung').on('click', function() {
            hitungDiskon();
            hitungDiskonGlobal();
            hitungTotal();
            $('.btn-simpan').attr('disabled', false);
        });

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
                                            <td style="background: #ebebeb;">${data[i].barang.nama_barang}</td>
                                            <td style="background: #ebebeb;">
                                                <input name="produk_id[]" type="hidden" value="${data[i].id_barang}">
                                                <input name="kuantitas[]" type="text" class="form-control kuantitas" value="${qty_paket}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="harga_produk[]" type="text" class="form-control harga-produk" value="${data[i].harga}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="diskon_persen[]" type="number" class="form-control diskon-persen" value="${dsc_paket}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="0">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="potongan[]" type="text" class="form-control potongan" value="0">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="cashback[]" type="text" class="form-control cashback" value="0">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="subtotal[]" style="background: #ebebeb;" type="text" class="form-control subtotal" value="0">
                                            </td>
                                            <td style="background: #ebebeb;">${data.nama_paket}
                                                <input name="catatan[]" class="form-control" type="hidden" value="${data.nama_paket}">
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`;


                        $tableProduk.append(rowPaket);
                        hitungDiskon();
                        hitungTotal();
                    }
                }
            });

        });

        function formatRupiah(nilai) {
            return nilai.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });
        }

    });

    // select produk
    $(document).on('change', '.selectProduk', function() {
        let produk_id = $(this).val();
        let row = $(this).closest('tr'); // simpan elemen closest tr ke dalam variable row

        $.ajax({
            method: 'GET',
            url: '/v2/getDataProduk/' + produk_id,
            dataType: 'json',
            success: function(data) {
                row.find('.harga-produk').val(data.harga_barang1); // akses .harga_produk dari variable row
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });

    // proses mengambil harga produk
    function selectProduk(produk) {

        $.ajax({
            method: 'get',
            url: '/api/v2/getHargaProduk/' + produk.value,
            dataType: 'json',
            success: function(data) {
                const row = $(this).closest('tr');

                row.find('input[name="harga_produk[]"]').val(data)
            }
        });
    }

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

            $('#penerima').css('background-color', '#ebebeb');
            $('#alamat_penerima').css('background-color', '#ebebeb');
        } else {
            $('#penerima').val('');
            $('#alamat_penerima').val('');

            $('#penerima').css('background-color', '');
            $('#alamat_penerima').css('background-color', '');
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