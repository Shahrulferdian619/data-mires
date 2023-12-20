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
        <li>Fitur buat invoice manual hanya dipakai untuk transaksi penjualan jenis Konsinyasi</li>
        <li>Untuk transaksi penjualan selain Konsinyasi, invoice sudah digenerate otomatis oleh sistem</li>
    </ul>
</div>

<a href="{{ route('invoice-penjualan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('invoice-penjualan.store')}}" method="post" enctype="multipart/form-data">
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
            <select name="pelanggan_id" class="form-control select2 select-pelanggan" required>
                <option value="">-- PILIH PELANGGAN --</option>
                @foreach($pelanggan as $row)
                <option value="{{ $row->id }}">{{$row->kode_pelanggan}} | {{$row->nama_pelanggan}}</option>
                @endforeach
            </select>

            <label for="">Kode pelanggan</label>
            <input type="text" class="form-control kode-pelanggan" readonly>

            <label for="">Nama pelanggan</label>
            <input type="text" class="form-control nama-pelanggan" readonly>

            <label for="">Alamat pelanggan</label>
            <textarea cols="30" rows="5" class="form-control alamat-pelanggan"></textarea>
        </div>
    </div>

    <br>

    <!-- Data Invoice Konsinyasi -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Invoice
            </h5>
        </div>

        <div class="card-body">
            <label for="">Nomer invoice</label>
            <input name="nomer_invoice_penjualan" type="text" class="form-control" value="{{ $nomer_invoice }}" required>

            <label>Jenis penjualan</label>
            <select name="jenis_penjualan" class="form-control select2" required>
                <option value="">-- PILIH JENIS PENJUALAN --</option>
                <option value="KONSINYASI">KONSINYASI</option>
                <option value="EVENT">EVENT</option>
            </select>

            <label for="">Nomer ref</label>
            <input name="nomer_ref" type="text" class="form-control">

            <label for="">Tanggal</label>
            <input name="tanggal" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>

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
                            <th style="width: 200px;">produk</th>
                            <th style="width: 85px;">qty</th>
                            <th style="width: 200px;">harga</th>
                            <th style="width: 85px;">dsc(%)</th>
                            <th style="width: 200px;">dsc(Rp)</th>
                            <th style="width: 200px;">subtotal</th>
                            <th style="width: 200px;">catatan</th>
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
                                <select name="produk[]" class="form-control select2 select-produk" required>
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
                                <input name="harga_produk[]" type="number" class="form-control harga-produk" value="0" required>
                            </td>

                            <td>
                                <input name="diskon_persen[]" type="text" min="0" max="100" step="0.01" class="form-control diskon-persen" value="0">
                            </td>

                            <td>
                                <input name="diskon_nominal[]" type="number" min="0" class="form-control diskon-nominal" value="0">
                            </td>

                            <td>
                                <input name="subtotal[]" type="text" class="form-control subtotal" value="0" readonly style="background-color: #ebebeb;">
                            </td>

                            <td>
                                <textarea name="catatan[]" class="form-control"></textarea>
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
                            <td colspan="5" style="text-align: right;">TOTAL HARGA : </td>
                            <td colspan="1">
                                <input type="text" class="form-control total-harga" readonly style="background-color: #ebebeb;">
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <div class="row">
                    <div class="col-md-10">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width: 400px;">Total Harga</td>
                                <td>
                                    <input name="total_sebelum_diskon" type="text" class="form-control total-harga" style="background-color: #ebebeb;">
                                </td>
                            </tr>
                            <tr>
                                <td>Diskon persen <i>(isikan 0 bila tanpa diskon)</i></td>
                                <td>
                                    <input type="number" name="diskon_persen_global" class="form-control diskon-persen-global" value="0" required>
                                </td>
                            </tr>
                            <tr>
                                <td>Diskon nominal <i>(global)</i></td>
                                <td>
                                    <input name="diskon_nominal_global" type="text" class="form-control diskon-nominal-global" value="0" required>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Harga <i>(setelah disc)</i></td>
                                <td>
                                    <input name="total_setelah_diskon" type="text" class="form-control total-setelah-diskon" readonly style="background-color: #ebebeb;">
                                </td>
                            </tr>
                            <tr>
                                <td>PPn</td>
                                <td>
                                    <input name="nilai_ppn" type="text" class="form-control nilai-ppn" value="0" readonly style="background-color: #ebebeb;">
                                </td>
                            </tr>
                            <tr>
                                <td>Grand total</td>
                                <td>
                                    <input name="grandtotal" type="text" class="form-control grandtotal" readonly style="background-color: #ebebeb;">
                                </td>
                            </tr>
                        </table>

                        <br>
                    </div>

                    <div class="col-md-10">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width: 400px;">Akun Diskon Penjualan</td>
                                <td>
                                    <select name="akun_diskon_id" id="akun_diskon" class="form-control select2" required>
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
                                    <select name="akun_ppn_id" id="akun_ppn" class="form-control select2" required>
                                        <option value="177">PPn Keluaran</option>
                                        @foreach($akun_ppn as $r)
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

    <br>

    <!-- Data Berkas -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Berkas
            </h5>
        </div>

        <div class="card-body">
            <label for="">Berkas</label>
            <input name="berkas" type="file" class="form-control">
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('invoice-penjualan.index') }}" class="btn btn-outline-warning">
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

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {

        const $tabelProduk = $('.rincian-produk');
        const $btnTambahProduk = $('.btn-tambah-row');

        $('.select2').select2();

        // button untuk menambah row rincian produk
        $btnTambahProduk.click(function() {
            const rowProduk = `<tr>
                                    <td>
                                        <select name="produk[]" class="form-control select2 select-produk" required>
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
                                        <input name="harga_produk[]" type="number" class="form-control harga-produk" value="0" required>
                                    </td>

                                    <td>
                                        <input name="diskon_persen[]" type="number" min="0" max="100" step="0.01" class="form-control diskon-persen" value="0">
                                    </td>

                                    <td>
                                        <input name="diskon_nominal[]" type="number" min="0" class="form-control diskon-nominal" value="0">
                                    </td>

                                    <td>
                                        <input name="subtotal[]" type="text" class="form-control subtotal" value="0" readonly style="background-color: #ebebeb;">
                                    </td>

                                    <td>
                                        <textarea name="catatan[]" class="form-control"></textarea>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            $tabelProduk.append(rowProduk);
            $('.select2').select2();
        });

        // button untuk menghapus row rincian produk
        $(document).on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();

            hitungTotal();
        });

        // Select pelanggan
        $(document).on('change', '.select-pelanggan', function(e) {
            const pelanggan = $(this).val();

            // jika pilih pelanggan baru, maka tampilkan modal buat pelanggan baru
            if (pelanggan == 'pelanggan-baru') {
                $('#modalPelanggan').modal('show');
            }

            // autofill nama pelanggan & alamat pelanggan
            $.ajax({
                method: 'get',
                url: '/api/v2/getPelanggan/' + pelanggan,
                dataType: 'json',
                success: function(data) {
                    $('.kode-pelanggan').val(data.kode_pelanggan).attr('disabled', true);
                    $('.nama-pelanggan').val(data.nama_pelanggan).attr('disabled', true);
                    $('.alamat-pelanggan').val(data.detil_alamat + ' ' + data.provinsi).attr('disabled', true);
                }
            });
        });

        // Select ppn
        $(document).on('change', '.select-ppn', function() {
            hitungTotal();
        });

        // Modal tambah paket
        $('.btn-modal-paket').click(function(e) {
            $('#modalPaket').modal('show');
        });

        // Proses tambah paket
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
                    for (i = 0; i < data.jumlah; i++) {
                        let rowPaket = `<tr>
                                            <td style="background: #ebebeb;">${data[i].barang.nama_barang}</td>
                                            <td style="background: #ebebeb;">
                                                <input name="produk[]" type="hidden" value="${data[i].id_barang}">
                                                <input name="kuantitas[]" type="text" class="form-control kuantitas" value="${qty_paket}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="harga_produk[]" type="text" class="form-control harga-produk" value="${data[i].harga}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="diskon_persen[]" type="number" min="0" step="0.01" class="form-control diskon-persen" value="${dsc_paket}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="0">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="subtotal[]" style="background: #ebebeb;" type="text" class="form-control subtotal" value="0">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <textarea name="catatan[]" class="form-control" style="background: #ebebeb;" readonly>${data.nama_paket}</textarea>
                                                <input name="catatan[]" class="form-control" type="hidden" value="${data.nama_paket}">
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`;
                        $tabelProduk.append(rowPaket);
                    }

                    hitungTotal();
                }
            });
        });

        // Select produk
        $(document).on('change', '.select-produk', function() {
            const produk = $(this).val();
            const row = $(this).closest('tr').find('.harga-produk');

            // autofill harga produk
            $.ajax({
                method: 'get',
                url: '/api/v2/getProduk/' + produk,
                dataType: 'json',
                success: function(data) {
                    if (row.val() == null) {
                        row.val(0);
                    } else {
                        row.val(data.harga_barang1);
                    }

                    hitungTotal();
                }
            });

        });

        // hitung ulang total harga ketika ada perubahan inputan pada rincian produk
        $(document).on('input', '.kuantitas, .harga-produk', function(e) {
            hitungTotal();
        });

        // hitung ulang diskon persen produk setiap ada perubahan input
        $(document).on('input', '.diskon-persen', function(e) {
            hitungDiskonProduk();
        });

        // hitung ulang diskon nominal setiap ada perubahan input
        $(document).on('input', '.diskon-nominal', function() {
            hitungDiskonNominalProduk();
        });

        // hitung ulang diskon global setiap ada perubahan input
        $(document).on('input', '.diskon-persen-global', function() {
            //hitungDiskonGlobal();
            hitungTotal();
        });

        // fungsi untuk menghitung diskon nominal menjadi diskon persen
        function hitungDiskonNominalProduk() {
            $tabelProduk.find('tr').each(function() {
                const $row = $(this);
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const harga_produk = $row.find('.harga-produk').val();
                const diskon_persen = diskon_nominal / harga_produk * 100;

                // manipulasi nilai diskon nominal
                $row.find('.diskon-persen').val(diskon_persen.toFixed(2));
            });
            hitungSubtotalProduk();
        }

        // fungsi untuk menghitung diskon persen menjadi nominal
        function hitungDiskonProduk() {
            $tabelProduk.find('tr').each(function() {
                const $row = $(this);
                const diskon_persen = $row.find('.diskon-persen').val();
                const harga_produk = $row.find('.harga-produk').val();
                const diskon_nominal = harga_produk * diskon_persen / 100;

                // manipulasi nilai diskon nominal
                $row.find('.diskon-nominal').val(diskon_nominal);
            });
            hitungSubtotalProduk();
        }
        
        // fungsi untuk menghitung subtotal setiap baris produk
        function hitungSubtotalProduk() {
            let total_harga = 0;

            $tabelProduk.find('tr').each(function() {
                const $row = $(this);
                const kuantitas = $row.find('.kuantitas').val();
                const harga_produk = $row.find('.harga-produk').val();
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const subtotal = (harga_produk - diskon_nominal) * kuantitas;

                // isikan tabel subtotal otomatis
                $row.find('.subtotal').val(formatRupiah(subtotal));

                // hitung total harga
                total_harga += subtotal;
            });

            // isi total harga dari subtotal
            $('.total-harga').val(formatRupiah(total_harga));

            hitungTotal();
        }

        // fungsi untuk menghitung total keseluruhan dari rincian produk
        function hitungTotal() {

            let total_harga = 0;
            let total_sebelum_diskon = 0;
            let grandtotal = 0;

            $tabelProduk.find('tr').each(function() {
                const $row = $(this);
                const kuantitas = $row.find('.kuantitas').val();
                const harga_produk = $row.find('.harga-produk').val();
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const subtotal = (harga_produk - formatDouble(diskon_nominal)) * kuantitas;

                $row.find('.subtotal').val(formatRupiah(subtotal));

                // hitung total harga dari keseluruhan subtotal
                total_harga += subtotal;
            });

            $('.total-harga').val(formatRupiah(total_harga));

            hitungDiskonGlobal();
            hitungPpn();
        }

        // fungsi untuk menghitung diskon invoice
        function hitungDiskonGlobal() {
            const total_sebelum_diskon = formatDouble($('.total-harga').val());
            const diskon_persen_global = $('.diskon-persen-global').val();
            const diskon_nominal_global = total_sebelum_diskon * diskon_persen_global / 100;

            $('.diskon-nominal-global').val(formatRupiah(diskon_nominal_global));
            $('.total-setelah-diskon').val(formatRupiah(total_sebelum_diskon - diskon_nominal_global));
        }

        // fungsi untuk menghitung ppn
        function hitungPpn() {
            const select_ppn = $('.select-ppn').val();
            let nilai_ppn = 0;
            let total_setelah_diskon = formatDouble($('.total-setelah-diskon').val());
            let grandtotal = 0;

            // di sini proses sekalian menghitung grandtotal
            if (select_ppn == 0) {
                $('.nilai-ppn').val(formatRupiah(nilai_ppn));
                grandtotal = total_setelah_diskon;
            } else if (select_ppn == 1) {
                nilai_ppn = total_setelah_diskon * 11 / 100;
                $('.nilai-ppn').val(formatRupiah(nilai_ppn));

                grandtotal = total_setelah_diskon + nilai_ppn;
            } else if (select_ppn == 2) {
                nilai_ppn = total_setelah_diskon - (total_setelah_diskon / 1.11);
                $('.nilai-ppn').val(formatRupiah(nilai_ppn));

                grandtotal = total_setelah_diskon;
            }

            $('.grandtotal').val(formatRupiah(grandtotal));
        }

        // merubah ke format rupiah
        function formatRupiah(nilai) {
            return nilai.toLocaleString('id-ID');
        }

        // merubah ke format double dari rupiah
        function formatDouble(nilai) {
            // Menghapus karakter non-numerik (spasi, Rp, titik, koma ribuan)
            var numberString = nilai.replace(/[^\d,.-]/g, '');

            // Mengganti koma ribuan dengan tanda kosong
            numberString = numberString.replace(/\./g, '');

            // Mengganti koma desimal dengan titik desimal
            numberString = numberString.replace(",", ".");

            // Mengonversi string menjadi tipe data double
            var result = parseFloat(numberString);

            return result;
        }

        hitungTotal();
    });
</script>
@endsection