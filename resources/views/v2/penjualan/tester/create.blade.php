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
</div>

<a href="{{ route('permintaan-tester.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('permintaan-tester.store')}}" method="post" enctype="multipart/form-data">
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
            <select name="pelanggan_id" class="form-control select2 pelanggan" required>
                <option value="">-- PILIH PELANGGAN --</option>
                <option value="pelanggan-baru">-- PELANGGAN BARU --</option>
                @foreach($pelanggan as $row)
                <option value="{{ $row->id }}">{{$row->kode_pelanggan}} | {{$row->nama_pelanggan}}</option>
                @endforeach
            </select>

            <label for="">Kode pelanggan</label>
            <input id="kode_pelanggan" value="" type="text" class="form-control" readonly>

            <label for="">Nama pemesan</label>
            <input name="nama_pelanggan" value="" type="text" class="form-control" readonly>

            <label for="">Alamat pemesan</label>
            <textarea name="alamat_pelanggan" cols="30" rows="5" class="form-control" readonly></textarea>

            <hr>

            <div class="form-check form-check-inline">
                <input class="form-check-input sama-dengan-pemesan" type="checkbox" value="checked" />
                <label class="form-check-label" for=""><b>Sama dengan pemesan</b></label>
            </div>

            <br>

            <label for="">Nama penerima</label>
            <input name="penerima" value="" type="text" class="form-control penerima" required>

            <label for="">Alamat penerima</label>
            <textarea name="alamat_penerima" cols="30" rows="5" class="form-control alamat-penerima" required></textarea>
        </div>
    </div>

    <br>

    <!-- Data Pesanan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Permintaan Tester
            </h5>
        </div>

        <div class="card-body">
            <label for="">Nomer permintaan tester</label>
            <input name="nomer_permintaan_tester" type="text" class="form-control" value="{{ $generateNomer }}" readonly>

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
                            <th>produk</th>
                            <th style="width: 50px;">qty</th>
                            <th style="width: 200px;">catatan</th>
                            <th style="width: 50px;">
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
                                <input name="catatan[]" type="text" class="form-control">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-produk">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Berkas pendukung</td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-success btn-tambah-berkas">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="tabel-berkas">
                        <tr>
                            <td>
                                <input type="file" name="berkas[]" class="form-control">
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-danger btn-hapus-berkas">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('permintaan-tester.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@include('v2.component.modal-pelanggan')

<!-- Modal proses -->
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

        const $btnTambahProduk = $('.btn-tambah-row');
        const $btnTambahBerkas = $('.btn-tambah-berkas');
        const $tabelProduk = $('.rincian-produk');
        const $tabelBerkas = $('.tabel-berkas');

        $('.select2').select2();

        // select pelanggan autofill dan modal u/ pelanggan baru
        $('.pelanggan').on('change', function(e) {
            const pelanggan_id = $('select[name="pelanggan_id"]').val();

            // jika pilih pelanggan baru, tampilkan modal pelanggan baru
            if (pelanggan_id == 'pelanggan-baru') {
                $('#modalPelanggan').modal('show');
            }

            $.ajax({
                method: 'get',
                url: '/api/v2/getPelanggan/' + pelanggan_id,
                dataType: 'json',
                success: function(data) {
                    $('#kode_pelanggan').val(data.kode_pelanggan);
                    $('input[name="nama_pelanggan"]').val(data.nama_pelanggan);
                    $('textarea[name="alamat_pelanggan"]').val(data.detil_alamat + ' ' + data.provinsi);
                }
            })
        });

        // centang autofill penerima & alamat penerima sama dengan pemesan
        $('.sama-dengan-pemesan').on('change', function(e) {
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
        })

        // pilih provinsi
        $('.provinsi').on('change', function(event) {
            let provinsi_id = $(this).val();

            $.ajax({
                method: 'get',
                url: '/api/v2/getKotaByProv/' + provinsi_id,
                dataType: 'json',
                success: function(data) {
                    let city = '';

                    data.map((item, index) => {
                        city += `<option value="${item.name}">${item.name}</option>`
                    });

                    $(".kota").html(city);
                }
            })
        });

        // tambah baris produk
        $btnTambahProduk.click(function(e) {
            const rowProduk = `<tr>
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
                                <input name="catatan[]" type="text" class="form-control">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-produk">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;

            $tabelProduk.append(rowProduk);
            $('.select2').select2();
        });

        // tambah baris produk
        $btnTambahBerkas.click(function(e) {
            const rowBerkas = `<tr>
                                    <td>
                                        <input type="file" name="berkas[]" class="form-control">
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-sm btn-danger btn-hapus-berkas">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            $tabelBerkas.append(rowBerkas);
        })

        // hapus baris produk
        $(document).on('click', '.btn-hapus-produk', function(e) {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection