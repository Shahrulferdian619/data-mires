@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
    }
</style>
@endsection

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

@section('content')
@if($pesanan_penjualan->status_proses == 1)
<div class="alert alert-warning" role="alert">
    <h4>Peringatan</h4>
    <ul>
        <li>
            Data pesanan ini sudah diproses
        </li>
        <li>
            Silahkan koordinasi ulang dengan gudang apabila ada perubahan kuantitas atau produk
        </li>
        <li>
            Silahkan koordinasi ulang dengan departemen Finance / Akunting apabila ada perubahan data penting
        </li>
    </ul>
</div>
@endif
<form action="{{ route('pesanan-penjualan.update',$pesanan_penjualan->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')
    <!-- Data pelanggan -->
    <div class="card">
        <div class="card-header">
            <h5 class="m-0 me-2 card-title">
                Data Pelanggan
            </h5>
        </div>

        <div class="card-body">
            <select name="pelanggan_id" class="form-control select2 pelanggan">
                <option value="{{ $pesanan_penjualan->pelanggan_id }}">{{ $pesanan_penjualan->pelanggan->kode_pelanggan }} | {{ $pesanan_penjualan->pelanggan->nama_pelanggan }}</option>
                <option value="pelanggan-baru">-- PELANGGAN BARU --</option>
                @foreach($pelanggan as $row)
                <option value="{{ $row->id }}">{{$row->kode_pelanggan}} | {{$row->nama_pelanggan}}</option>
                @endforeach
            </select>

            <label for="">Nama pelanggan</label>
            <input name="nama_pelanggan" type="text" class="form-control nama-pelanggan" value="{{ $pesanan_penjualan->pelanggan->nama_pelanggan }}" readonly style="background-color: #ebebeb;">

            <label for="">Alamat pelanggan</label>
            <textarea name="alamat_pelanggan" cols="30" rows="5" class="form-control alamat-pelanggan" style="background-color: #ebebeb;" readonly>{{ $pesanan_penjualan->pelanggan->detil_alamat }} {{ $pesanan_penjualan->pelanggan->provinsi }}</textarea>

            <hr>
            <label for=""><b>Alamat pengiriman</b></label>
            <br>
            <input class="form-check-input sama-dengan-pemesan" type="checkbox" value="checked" />
            <label class="form-check-label" for="">Sama dengan pelanggan</label>

            <br>
            <label for="">Penerima</label>
            <input name="penerima" type="text" class="form-control penerima" required>

            <label for="">Alamat penerima</label>
            <textarea name="alamat_penerima" cols="30" rows="5" class="form-control alamat-penerima" required></textarea>
        </div>
    </div>

    <br>

    <!-- Data pesanan -->
    <div class="card">
        <div class="card-header">
            <h5 class="m-0 me-2 card-title">
                Data Pesanan
            </h5>
        </div>

        <div class="card-body">
            <label for="">Bank penerima</label>
            <select name="akun_bank" class="form-control select2" required>
                <option value="{{ $pesanan_penjualan->akun_bank_id }}">{{$pesanan_penjualan->akun_bank->nomer_coa}} | {{$pesanan_penjualan->akun_bank->nama_coa}}</option>
                @foreach($akun_bank as $b)
                <option value="{{ $b->id }}">{{ $b->nomer_coa }} | {{ $b->nama_coa }}</option>
                @endforeach
            </select>

            <label for="">Nomer pesanan penjualan</label>
            <input name="nomer_pesanan_penjualan" type="text" class="form-control" value="{{ $pesanan_penjualan->nomer_pesanan_penjualan }}" readonly style="background-color: #ebebeb;">

            <label for="">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pesanan_penjualan->tanggal }}">

            <label for="">Jenis penjualan</label>
            <select name="jenis_penjualan" id="" class="form-control select2" required>
                <option value="{{ $pesanan_penjualan->jenis_penjualan }}">{{ $pesanan_penjualan->jenis_penjualan }}</option>
                @foreach($jenis_penjualan as $j)
                <option value="{{ $j->jenis_penjualan }}">{{ $j->jenis_penjualan }}</option>
                @endforeach
            </select>

            <label for="">No. pesanan</label>
            <input name="nomer_pesanan" type="text" class="form-control" value="{{ $pesanan_penjualan->nomer_pesanan }}">

            <label for="">Sales</label>
            <select name="sales_id" id="" class="form-control select2">
                <option value="{{ $pesanan_penjualan->sales_id }}">{{ $pesanan_penjualan->sales->nama_sales }}</option>
                @foreach($sales as $s)
                <option value="{{ $s->id }}">{{ $s->nama_sales }}</option>
                @endforeach
            </select>

            <label for="">PPn 11%</label>
            <select name="ppn" class="form-control select-ppn">
                <option value="{{ $pesanan_penjualan->ppn }}">@if($pesanan_penjualan->ppn == 0)Tidak @elseif($pesanan_penjualan->ppn == 1) Ya @elseif($pesanan_penjualan->ppn == 2) Include ppn @endif</option>
                <option value="0">Tidak</option>
                <option value="2">Include ppn</option>
                <option value="1">Ya</option>
            </select>

            <label for="">Ekspedisi</label>
            <select name="ekspedisi" id="" class="form-control select2">
                <option value="{{ $pesanan_penjualan->ekspedisi }}">{{ $pesanan_penjualan->ekspedisi }}</option>
                @foreach($ekspedisi as $e)
                <option value="{{ $e->nama_ekspedisi }}">{{ $e->nama_ekspedisi }}</option>
                @endforeach
            </select>

            <label for="">Resi</label>
            <input name="resi" type="text" class="form-control" value="{{ $pesanan_penjualan->resi }}">

            <label for="">Keterangan</label>
            <textarea name="keterangan" id="" cols="30" rows="5" class="form-control">{{ $pesanan_penjualan->keterangan }}</textarea>
        </div>
    </div>

    <br>

    <!-- Rincian produk -->
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
                    <thead>
                        <tr>
                            <th>produk</th>
                            <th>qty</th>
                            <th style="width: 200px;">harga</th>
                            <th>dsc(%)</th>
                            <th style="width: 200px;">dsc(Rp)</th>
                            <th style="width: 200px;">potongan</th>
                            <th style="width: 200px;">cashback</th>
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
                        @foreach($pesanan_penjualan_rinci as $rinci)
                        <tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 produk" required>
                                    <option value="{{ $rinci->produk_id }}">{{ $rinci->produk->kode_barang }} | {{ $rinci->produk->nama_barang }}</option>
                                    <option value="">-- CARI PRODUK --</option>
                                    @foreach($produk as $row)
                                    <option value="{{$row->id}}">{{$row->kode_barang}} | {{$row->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input name="kuantitas[]" type="number" class="form-control kuantitas" value="{{ $rinci->kuantitas }}" required>
                            </td>

                            <td>
                                <input name="harga_produk[]" type="number" class="form-control harga-produk" value="{{ $rinci->harga_produk }}" required>
                            </td>

                            <td>
                                <input name="diskon_persen[]" type="number" class="form-control diskon-persen" value="{{ $rinci->diskon_persen }}">
                            </td>

                            <td>
                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="{{ $rinci->diskon_nominal }}" style="background-color: #ebebeb;" readonly>
                            </td>

                            <td>
                                <input name="potongan[]" type="text" class="form-control potongan" value="{{ $rinci->potongan_admin }}">
                            </td>

                            <td>
                                <input name="cashback[]" type="text" class="form-control cashback" value="{{ $rinci->cashback }}">
                            </td>

                            <td>
                                <input type="text" class="form-control subtotal-konversi" value="0" readonly style="background-color: #ebebeb;">
                                <input name="subtotal[]" type="text" class="form-control subtotal" value="" readonly hidden>
                            </td>

                            <td>
                                <input name="catatan[]" type="text" class="form-control" value="{{ $rinci->catatan }}">
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
                            <td colspan="7" style="text-align: right;">TOTAL HARGA : </td>
                            <td colspan="3">
                                <input type="text" class="form-control total-harga" readonly style="background-color: #ebebeb;">
                            </td>
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
                                    <input type="text" class="form-control total-harga" readonly style="background-color: #ebebeb;">
                                    <input name="grandtotal" type="text" class="total-harga-hidden" hidden>
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
                                    <input name="diskon_global" type="text" class="form-control diskon-global" value="0" required readonly style="background-color: #ebebeb;">
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
                                    <input type="text" class="form-control total-setelah-ppn" readonly style="background-color: #ebebeb;">
                                </td>
                            </tr>
                            <tr>
                                <td>Biaya kirim (isikan bila ditagihkan ke customer)</td>
                                <td>
                                    <input type="number" name="biaya_kirim" class="form-control biaya-kirim" value="{{ $pesanan_penjualan->biaya_kirim }}">
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

                        <br>

                    </div>

                    <div class="col-md-10">
                        <table class="table table-bordered">
                            <tr>
                                <td style="width: 400px;">Akun Diskon Penjualan</td>
                                <td>
                                    <select name="akun_diskon" id="akun_diskon" class="form-control select2" required>
                                        <option value="74">Diskon penjualan</option>
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
                                        <option value="50">PPn Keluaran</option>
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
                                        <option value="{{ $pesanan_penjualan->akun_biayakirim_id }}">{{ $pesanan_penjualan->akun_biayakirim->nama_coa }}</option>
                                        @foreach($akun_biayakirim as $r)
                                        <option value="{{ $r->id }}">{{ $r->nomer_coa }} | {{ $r->nama_coa }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>

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
    @endif

    <br>

    <!-- Button -->
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary" type="submit">Simpan</button>
            <a href="{{ route('pesanan-penjualan.index') }}" class="btn btn-outline-warning">Batal</a>
        </div>
    </div>
</form>

@include('v2.component.modal-pelanggan')
@include('v2.component.modal-paket')

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        const $tableProduk = $('.rincian-produk');
        const $btnTambahRow = $('.btn-tambah-row');
        const $btnModalPaket = $('.btn-modal-paket');
        const $totalHarga = $('.total-harga');
        const $grandTotal = $('.grandtotal');

        $('.select2').select2();

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

        // fungsi untuk menghitung diskon produk (satuan)
        function hitungDiskon() {
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

        // autofill data pelanggan 
        $('.pelanggan').on('change', function(e) {
            const pelanggan = $(this).val();

            $.ajax({
                method: 'get',
                url: '/api/v2/getPelanggan/' + pelanggan,
                dataType: 'json',
                success: function(data) {
                    $('.nama-pelanggan').val(data.nama_pelanggan);
                    $('.alamat-pelanggan').val(data.detil_alamat + ' ' + data.kota + ' ' + data.provinsi);
                }
            });
        });

        // menambah baris rincian produk
        $btnTambahRow.click(function(e) {
            const row = `<tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 produk" required>
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
                                <input name="diskon_persen[]" type="number" class="form-control diskon-persen" value="0">
                            </td>

                            <td>
                                <input name="diskon_nominal[]" type="text" class="form-control diskon-nominal" value="0" readonly style="background-color: #ebebeb">
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
                                <input name="catatan[]" type="text" class="form-control" value="">
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
        $(document).on('click', '.btn-hapus', function(e) {
            $(this).closest('tr').remove();
            hitungTotal();
        });

        // modal paket
        $btnModalPaket.click(function(e) {
            $('#modalPaket').modal('show');
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

        // centang pengiriman sama dengan pelanggan
        $('.sama-dengan-pemesan').on('change', function() {
            if ($('.sama-dengan-pemesan').is(':checked')) {
                $('.penerima').val($('input[name="nama_pelanggan"]').val());
                $('.alamat-penerima').val($('textarea[name="alamat_pelanggan"]').val());

                $('.penerima').css('background-color', '#ebebeb').attr('readonly', true);
                $('.alamat-penerima').css('background-color', '#ebebeb').attr('readonly', true);
            } else {
                $('.penerima').val('');
                $('.alamat-penerima').val('');

                $('.penerima').css('background-color', '').removeAttr('readonly');
                $('.alamat-penerima').css('background-color', '').removeAttr('readonly');
            }
        });

        // modal pelanggan baru
        $('.pelanggan').on('change', function(event) {
            if ($(this).val() == 'pelanggan-baru') {
                $('#modalPelanggan').modal('show');
            }
        });

        // button hitung rincian produk
        $('.btn-hitung').click(function(e) {
            hitungDiskon();
            hitungDiskonGlobal();
            hitungTotal();
        });

        // menghitung ppn
        $('.select-ppn').on('change', function(e) {
            hitungTotal();
        });

        // menghitung subtotal dan grandtotal ketika inputan berubah
        $(document).on('input', '.kuantitas, .harga-produk, .diskon-persen, .diskon-nominal, .potongan, .cashback, .biaya-kirim', function(e) {
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

        function formatRupiah(nominal) {
            return nominal.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });
        }

        hitungTotal();
    });

    // Kode cadangan bila akan dipakai next
    // cek inputan akun biaya kirim untuk show-hide akun biaya kirim
    // $(document).on('input', '.biaya-kirim', function(e) {
    //     let biaya_kirim = $(this).val();

    //     if (biaya_kirim === '' || biaya_kirim === null || biaya_kirim == 0) {
    //         $('#akun_biayakirim').select2('destroy').hide();
    //     } else {
    //         $('#akun_biayakirim').select2().show();
    //     }
    // });

    // cek inputan diskon persen global
    // $(document).on('input', '.diskon-persen-global', function(e) {
    //     let diskon_persen_global = $(this).val();

    //     if (diskon_persen_global === '' || diskon_persen_global === null || diskon_persen_global == 0) {
    //         $('#akun_diskon').select2('destroy').hide();
    //     } else {
    //         $('#akun_diskon').select2().show();
    //     }
    // });

    // cek select ppn
    // $(document).on('change', '.select-ppn', function(e) {
    //     let select_ppn = $(this).val();

    //     if (select_ppn == 0) {
    //         $('#akun_ppn').select2('destroy').hide();
    //     } else {
    //         $('#akun_ppn').select2().show();
    //     }
    // });
</script>
@endsection