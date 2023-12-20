@extends('layouts.vuexy')

@section('mycss')
<style>
    .table td {
        padding: 0;
        vertical-align: middle;
    }
</style>
@endsection

@section('header')
Create Sales Order (Buat Pesanan Penjualan)
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@endif

<form id="form-save">
    @csrf
    <div class="row">
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-body">


                    <div class="mb-1">
                        <label for="so_nomer" class="form-label">Nomor Penjualan * <small class="text-danger">(Jika Nomer Sama, Nomer Akan Otomatis Urut)</small></label>
                        <input type="text" name="so_nomer" id="so_nomer" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_nomer'] : $so_nomer }}" readonly class="form-control" required>
                    </div>

                    <div class="mb-1">
                        <label for="jenis_penjualan" class="form-label">Jenis Penjualan *</label>
                        <select class="select2 form-control form-select" required name="jenis_penjualan" id="jenis_penjualan" required>
                            <option value="">-- Pilih Jenis Penjualan --</option>
                            @foreach ($jenis_penjualan as $item)
                            <option value="{{ $item->id }}" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == $item->id ? 'selected' : '') : '' }}>{{ $item->jenis_penjualan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="no_pesanan" class="form-label">Nomor Pesanan</label>
                        <input type="text" name="no_pesanan" id="no_pesanan" value="{{ session()->has('no_pesanan') ? session()->get('so_penjualan')['so_nomer'] : '' }}" class="form-control">
                    </div>

                    <div class="mb-1">
                        <label for="id_sales" class="form-label">Sales</label>
                        <select name="id_sales" id="" class="form-control">
                            <option value="0">-- Tanpa Sales / Direct Online --</option>
                            @foreach ($sales as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_sales }} || {{ $item->kode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="so_tanggal" class="form-label">Tanggal Penjualan *</label>
                        <input type="date" name="so_tanggal" id="so_tanggal" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_tanggal'] : date('Y-m-d') }}" class="form-control flatpickr-basic flatpickr-input" required>
                    </div>
                    <div class="mb-1">
                        <label for="is_tax" class="form-label">PPN</label>
                        <select class="select2 form-control form-select" name="is_tax" id="is_tax" required>
                            <option value="0" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 0 ? 'selected' : '') : '' }}>Tidak</option>
                            <option value="1" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 1 ? 'selected' : '') : '' }}>Iya</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="ekspedisi" class="form-label">Ekspedisi</label>
                        <select class="select2 form-control form-select" name="ekspedisi" id="ekspedisi">
                            <option value="">-- Tanpa Ekspedisi --</option>
                            @foreach($ekspedisi as $ekspedisiRow)
                            <option value="{{ $ekspedisiRow->nama_ekspedisi }}">{{ $ekspedisiRow->nama_ekspedisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="resi" class="form-label">Nomor Resi</label>
                        <input type="text" name="resi" id="resi" value="{{ session()->has('resi') ? session()->get('so_penjualan')['so_nomer'] : '' }}" class="form-control">
                    </div>

                    <div class="mb-1">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control" value="-">{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['keterangan'] : '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-header bg-primary text-light">Data Pelanggan</div>
                <div class="card-body">
                    <div class="mb-1 mt-1">
                        <select class="select2 form-control form-select" name="id_pelanggan" onchange="setPelanggan()" id="id_pelanggan" required>
                            <option value="">-- Pilih Customer --</option>
                            <option value="0">-- Buat Baru --</option>
                            @foreach ($customer as $item)
                            <option value="{{$item->id}}" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['id_pelanggan'] == $item->id ? 'selected' : '') : '' }}>@if(!empty($item->kode_area))({{ $item->kode_area }})@endif {{ $item->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="">Tipe</label>
                            <select disabled class="form-control" name="id_tipepelanggan" id="id_tipepelanggan">
                                @foreach($tipe_pelanggan as $tipe)
                                <option value="{{ $tipe->id }}">{{ $tipe->tipepelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="">Kode Customer *</label>
                            <input disabled type="text" name="kode_pelanggan" id="kode_pelanggan" class="form-control">
                        </div>
                    </div>

                    <label for="">Nama Pemesan *</label>
                    <input disabled id="nama_pelanggan" type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan">
                    <label for="">No. Telp *</label>
                    <input disabled id="handphone_pelanggan" type="text" name="handphone_pelanggan" class="form-control" id="handphone_pelanggan">
                    <div class="row">
                        <div class="col-6">
                            <label>Provinsi</label>
                            <select disabled name="provinsi" id="select_provinsi" class="form-control">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Kota</label>
                            <select disabled name="kota" class="form-control" id="select_city">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <label for="">Alamat Pemesan</label>
                    <input disabled type="text" name="detail_alamat" id="detail_alamat" class="form-control">

                    <div>
                        <label for="penerima" class="form-label">Nama Penerima</label>
                        <input type="text" name="penerima" id="penerima" value="" class="form-control">
                    </div>
                    <div>
                        <label for="alamat_pengiriman" class="form-label">Alamat Pengiriman</label>
                        <input type="text" name="alamat_pengiriman" id="alamat_pengiriman" value="" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-1">
            <div class="card">
                <div class="card-header">
                    <h3>Rincian Produk</h3>
                    <div class="btn btn-outline-primary btn-sm" onclick="tambahPaket()">Tambah Paket</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table style="width: 100%" class="text-center" id="flag">
                            <thead>
                                <tr>
                                    <th>Barang <span class="text-danger">*</span></th>
                                    <th style="width: 60px">QTY <span class="text-danger">*</span></th>
                                    <th>Harga <span class="text-danger">*</span></th>
                                    <th style="width: 60px">Dsc (%)</th>
                                    <th>Dsc (Rp)</th>
                                    <th>Harga Akhir</th>
                                    <th><small>Admin, Layanan, PPN</small></th>
                                    <th>Cashback Ongkir</th>
                                    <th>Keterangan</th>
                                    <th style="width: 10px">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-item">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="flag_row">
                                <tr class="flag_1">
                                    <td>
                                        <select onchange="pilih_produk(1)" id="pilih_1" class="selectNya form-control" name="id_barang[]" required>
                                            <option value="">-- PRODUK --</option>
                                            @foreach($items as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty" placeholder="Qty" id="qty_1" name="qty[]" onKeyup="setHargaAkhir(1)" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control harga" name="harga[]" placeholder="Rp.0" id="harga_1" data-flag="1" required onKeyup="set_asli(this)">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control diskon" name="diskon[]" placeholder="0" id="diskon_1" data-flag="1" value="0" onKeyup="set_dsc(this)" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="diskon_nominal[]" placeholder="Rp.0" id="diskon_nominal_1" data-flag="1" onKeyup="set_nom(this)" value="">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control" placeholder="Rp.0" id="harga_akhir_1" data-flag="1" onKeyup="set_dsc_nom(this)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="potongan_admin[]" placeholder="Rp.0" id="potongan_admin_1" onKeyup="rupiahJs(this)" value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="cashback_ongkir[]" placeholder="Rp.0" id="cashback_ongkir_1" onKeyup="rupiahJs(this)" value="">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control" placeholder="Note" name="note[]">
                                    </td>
                                    <td>
                                        <button type="button" onclick="btnHapusItem(this)" class="btn btn-danger btn-hps-item btn-sm" data-flag="flag_1"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9 col-12">
                            <label>Berkas</label>
                            <input type="file" name="berkasTemp" id="berkas_1" class="form-control">
                        </div>
                        <div class="col-md-3 col-12" style="padding-top: 4%">
                            <button type="button" class="btn btn-outline-primary btn-sm btn-tambah-berkas"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                        <div class="tambah-berkas" style="width:100%">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-1">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('admin/so') }}" class="btn btn-outline-danger">Kembali</a>
                    <button class="btn btn-outline-primary" id="btnSubmit" type="submit">Buat Data</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="tambahPaket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header"><strong>TAMBAH PAKET</strong></div>
            <div class="modal-body">
                <label for="">Paket</label>
                <select class="form-control" name="id_paket" id="id_paket">
                    @foreach($packet as $paket)
                    <option value="{{ $paket->id }}">{{ $paket->packet_name }} || Total = Rp.{{ number_format($paket->total) }}</option>
                    @endforeach
                </select>

            </div>
            <div class="modal-footer">

                <div onclick="batalAddPaket()" class="btn btn-sm btn-outline-danger">Batal</div>
                <div onclick="addPaket()" class="btn btn-sm btn-outline-primary">Add</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    // $(document).ready(function() {
    //     $('.modern-nav-toggle').click();
    // });
    function pilih_produk(flag) {
        let id_produk = $('#pilih_' + flag).val();
        $.ajax({
            method: 'GET',
            url: '/api/get-product-by-id/' + id_produk,
            dataType: 'JSON',
            success: function(data) {
                console.log(data.harga_barang1);
                if (data.harga_barang1 == null || data.harga_barang1 == 0) {
                    console.log('ya')
                    $('#harga_' + flag).val('')
                    $('#harga_akhir_' + flag).val('')
                } else {
                    console.log('tidak')
                    $('#harga_' + flag).val(formatRupiah(data.harga_barang1.toString(), 'Rp. '))
                    $('#harga_akhir_' + flag).val('Rp. 0')
                }
            }
        })
    }

    function set_dsc_nom(e) {
        rupiahJs(e)
        let harga_akhir = parseInt(e.value.replaceAll('Rp.', '').replaceAll('.', ''));
        let flag = $('#' + e.id).data("flag");
        let harga_asli = 0;
        if ($('#harga_' + flag).val() != '') {
            harga_asli = parseInt($('#harga_' + flag).val().replaceAll('Rp.', '').replaceAll('.', ''));
        }
        if (harga_akhir > harga_asli) {
            $('#' + e.id).val("")
            $('#diskon_nominal_' + flag).val("")
        } else {
            let dsc = harga_asli - harga_akhir;
            $('#diskon_nominal_' + flag).val(formatRupiah(dsc.toString(), 'Rp. '))
            $('#diskon_' + flag).val("")
        }
    }

    function set_nom(e) {
        rupiahJs(e)
        setHargaAkhir($('#' + e.id).data("flag"))
    }

    function set_asli(e) {
        rupiahJs(e)
        setHargaAkhir($('#' + e.id).data("flag"))
    }

    function set_dsc(e) {
        if (e.value > 100) {
            $('#' + e.id).val("100")
        }
        setHargaAkhir($('#' + e.id).data("flag"))
    }

    function setHargaAkhir(flag) {
        let dsc_nom = 0;
        let dsc = 0;
        let harga_akhir = 0;
        let harga_asli = 0;
        let qty = 0;
        if ($('#qty_' + flag).val() != '') {
            qty = parseInt($('#qty_' + flag).val());
        }
        if ($('#diskon_nominal_' + flag).val() != '') {
            dsc_nom = parseInt($('#diskon_nominal_' + flag).val().replaceAll('Rp.', '').replaceAll('.', ''));
        }
        if ($('#diskon_' + flag).val() != '') {
            dsc = parseInt($('#diskon_' + flag).val());
        }
        if ($('#harga_' + flag).val() != '') {
            harga_asli = parseInt($('#harga_' + flag).val().replaceAll('Rp.', '').replaceAll('.', ''));
            harga_akhir = qty * (harga_asli - (harga_asli * dsc / 100) - dsc_nom);
        }

        $('#harga_akhir_' + flag).val(formatRupiah(harga_akhir.toString(), 'Rp. '))

    }

    function setPelanggan() {
        let pelanggan = $('#id_pelanggan').val();
        console.log(pelanggan)
        if (pelanggan == 0) {
            $('#id_tipepelanggan').removeAttr('disabled').val('');
            $('#kode_pelanggan').removeAttr('disabled').val('');
            $('#nama_pelanggan').removeAttr('disabled').val('');
            $('#handphone_pelanggan').removeAttr('disabled').val('');
            $('#select_provinsi').removeAttr('disabled').val('');
            $('#select_city').removeAttr('disabled').val('');
            $('#detail_alamat').removeAttr('disabled').val('');
        } else {
            $('#id_tipepelanggan').attr('disabled', true);
            $('#kode_pelanggan').attr('disabled', true);
            $('#nama_pelanggan').attr('disabled', true);
            $('#handphone_pelanggan').attr('disabled', true);
            $('#select_provinsi').attr('disabled', true);
            $('#select_city').attr('disabled', true);
            $('#detail_alamat').attr('disabled', true);

            $.ajax({
                method: "GET",
                url: "/api/get-detail-pelanggan/" + pelanggan,
                dataType: "JSON",
                success: function(data) {
                    $('#kode_pelanggan').val(data.kode_pelanggan);
                    $('#nama_pelanggan').val(data.nama_pelanggan);
                    $('#handphone_pelanggan').val(data.handphone_pelanggan);
                    $('#detail_alamat').val(data.detail_alamat);
                    $('#penerima').val(data.nama_pelanggan);
                    $('#alamat_pengiriman').val(data.detail_alamat);
                },
                error: function(e) {
                    console.log(e)
                }
            })

        }
    }
    // Modal
    $(document).ready(function() {

        $('#select_provinsi').on('change', function(el) {

            el.preventDefault()
            $.ajax({
                method: 'GET',
                url: '/admin/pelanggan/get-city/' + $('#select_provinsi').val(),
                dataType: 'JSON',
                success: function(data) {
                    let html = ''
                    data.map((item, index) => {
                        html += `
                    <option value="${item.name}">${item.name}</option>
                    `
                    })
                    $('#select_city').html(html)
                }
            })
        })
    })

    function tambahPelanggan() {
        $('#tambahPelanggan').modal('show');
    }

    function tambahPaket() {
        $('#tambahPaket').modal('show');
    }

    function batalAddPaket() {
        $('#tambahPaket').modal('hide');
    }

    function saveNewCustomer() {
        if ($('#kode_pelanggan').val() == '' || $('#nama_pelanggan').val() == '' || $('#handphone_pelanggan').val() == '' || $('#select_provinsi').val() == '' || $('#select_city').val() == '') {
            return alert("Lengkapi Terlebih Dahulu!");
        }
        let nama = $('#nama_pelanggan').val();
        $('#pelangganNew').val(nama);
        $('#pelangganNew').removeAttr('hidden');
        // $('#id_pelanggan').removeClass('select2');
        $('#id_pelanggan').parent().attr('hidden', true);
        $('#buttonTambahCust').removeClass('btn-outline-primary').addClass('btn-outline-danger');
        $('#buttonTambahCust').html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Lihat')
        $('#tambahPelanggan').modal('hide');
    }

    function cancelNewCustomer() {
        $('#id_pelanggan').parent().removeAttr('hidden');
        $('#pelangganNew').val('');
        $('#kode_pelanggan').val('');
        $('#kode_area').val('');
        $('#nama_pelanggan').val('');
        $('#handphone_pelanggan').val('');
        $('#pelangganNew').attr('hidden', true);
        $('#buttonTambahCust').removeClass('btn-outline-danger').addClass('btn-outline-primary');
        $('#buttonTambahCust').html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Baru')
        $('#tambahPelanggan').modal('hide');
    }

    //table
    var flagNya = 2;

    function addPaket() {
        $.ajax({
            method: "GET",
            url: "/admin/so/get-paket/" + $('#id_paket').val(),
            dataType: "JSON",
            success: function(data) {
                let newRow = '';
                let nama = '';
                data.map((item, index) => {
                    let harga = formatRupiah(item.harga.toString(), 'Rp. ')
                    let str = '';
                    if (index == 0) {
                        str = `<td rowspan="${item.count}" class="text-center text-light bg-secondary rounded"><small>${item.nama}</small></td><td rowspan="${item.count}"><button type="button" onclick="btnHapusItem(this)" class="btn btn-danger btn-sm btn-hps-item" data-flag="flag_${flagNya}"><i class="fa fa-trash"></i></button></td>`
                        nama = item.nama
                    }
                    // console.log(harga);
                    newRow += `
                    <tr class="flag_${flagNya}">
                        <td><input type="text" class="form-control" readonly value="${item.barang.nama_barang}"><input type="hidden" class="form-control" name="id_barang[]" value="${item.id_barang}"></td>
                        <td><input type="number" readonly class="form-control qty" name="qty[]" placeholder="Qty" value="${item.qty}" required></td>
                        <td><input type="text" class="form-control harga" name="harga[]" id="harga_${flagNya}" placeholder="Rp.0" value="${harga}" required  onKeyup = "rupiahJs(this)"></td>
                        <td><input type="number" class="form-control diskon" name="diskon[]" value="0" required></td>
                        <td><input type="text" class="form-control" name="diskon_nominal[]" placeholder="Rp.0" id="diskon_nominal_${flagNya}" onKeyup = "rupiahJs(this)" value=""></td>
                        <td><input type="text" class="form-control" id="harga_akhir_${flagNya}" onKeyup = "rupiahJs(this)" disabled></td>
                        <td><input type="text" class="form-control" name="potongan_admin[]" placeholder="Rp.0" id="potongan_admin_${flagNya}" onKeyup = "rupiahJs(this)" value=""></td>
                        <td><input type="text" hidden class="form-control" value="${nama}" placeholder="Note" name="note[]"><input type="text" class="form-control" name="cashback_ongkir[]" placeholder="Rp.0" id="cashback_ongkir_${flagNya}" onKeyup = "rupiahJs(this)" value=""></td>
                        ${str}
                        
                    </tr>
                    `;

                    // console.log(newRow);
                })

                flagNya++;

                $('#flag tbody').after(newRow);
                $('.selectNya').select2();
            },
            error: function(e) {
                console.log(e)
            }
        })

        $('#tambahPaket').modal('hide');
    }
    $('.btn-item').on('click', function() {

        var newRow =
            '<tr class="flag_' + flagNya + '">' +
            '<td>' +
            '<select onchange="pilih_produk(' + flagNya + ')" id="pilih_' + flagNya + '" class="selectNya form-control" name="id_barang[]" required>' +
            '<option value="">-- PRODUK --</option>' +
            @foreach($items as $barang)
        '<option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>' +
        @endforeach
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control qty" placeholder="Qty" name="qty[]" id="qty_' + flagNya + '" onKeyup = "setHargaAkhir(' + flagNya + ')" required>' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control harga" name="harga[]"  data-flag="' + flagNya + '" placeholder="Rp.0" id="harga_' + flagNya + '" required  onKeyup = "set_asli(this)">' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control diskon" name="diskon[]" data-flag="' + flagNya + '" id="diskon_' + flagNya + '" value="0" onKeyup = "set_dsc(this)" required>' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="diskon_nominal[]" data-flag="' + flagNya + '" placeholder="Rp.0" id="diskon_nominal_' + flagNya + '" onKeyup = "set_nom(this)" value="">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" placeholder="Rp.0" id="harga_akhir_' + flagNya + '" data-flag="' + flagNya + '" onKeyup = "set_dsc_nom(this)">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="potongan_admin[]" placeholder="Rp.0" id="potongan_admin_' + flagNya + '" onKeyup = "rupiahJs(this)" value="">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="cashback_ongkir[]" placeholder="Rp.0" id="cashback_ongkir_' + flagNya + '" onKeyup = "rupiahJs(this)" value="">' +
            '</td>' +
            '<td class="text-center">' +
            '<input type="text" class="form-control" placeholder="Note" name="note[]">' +
            '</td>' +
            '<td><button type="button" onclick="btnHapusItem(this)" class="btn btn-danger btn-hps-item btn-sm"  data-flag="flag_' + flagNya + '"><i class="fa fa-trash"></i></button></td>' +
            '</tr>';

        $('#flag tbody').append(newRow);
        $('.selectNya').select2();

        flagNya++;

    });

    function btnHapusItem(e) {
        console.log(e);
        $('.' + $(e).data("flag")).remove()
    }

    //berkas
    var flag = 2;
    $('.btn-tambah-berkas').on('click', function() {
        var count = $("input:file").length;
        var berkas = count + 1;
        if (count >= 5) {
            alert('Maaf! Berkas tidak boleh lebih dari 5');
        } else {
            $(".tambah-berkas").append(
                '<div class="flag_' + flag + '">' +
                '<div class="row">' +
                '<div class="col-md-9 col-12" style="padding-left: 4%">' +
                '<label>Berkas</label>' +
                '<input type="file" name="berkasTemp" id="berkas_' + flag + '" class="form-control" style="width:98%">' +
                '</div>' +
                '<div class="col-md-3 col-12" style="padding-top: 4%">' +
                '<button type="button" class="btn btn-danger btn-sm btn-hapus-berkas" data-id="flag_' + flag + '"><i class="fa fa-trash"></i> Hapus</button>' +
                '</div>' +
                '</div>' +
                '</div>'
            );
            flag++;
        }

    });
    $('.tambah-berkas').on('click', '.btn-hapus-berkas', function() {
        // $(this).parent().remove();
        $('.' + $(this).data("id")).remove();
    });
    //sampai sini

    //submit form
    $('body').on('submit', '#form-save', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin ingin menyimpan data?',
            text: "Dengan menyimpan data, berkas yang di upload tidak bisa di hapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Saya yakin!',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                var formData = new FormData(document.getElementById("form-save"));

                $.each($("input[type=file]"), function(i, obj) {
                    $.each(obj.files, function(j, file) {
                        formData.append('berkas[]', file);

                    })
                });

                $('#btnSubmit').html('<i class="mr-1 fa fa-spinner fa-spin"></i> Loading...');
                document.getElementById("btnSubmit").disabled = true;

                $.ajax({
                    type: 'post',
                    url: "{{ url('admin/so') }}",
                    enctype: 'multipart/form-data',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (data) => {
                        $('#btnSubmit').html('Simpan');
                        if (data.nomer) {
                            Swal.fire({
                                title: 'Error!',
                                text: data.errors,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                            $('#btnSubmit').html('Simpan');
                            document.getElementById("btnSubmit").disabled = false;
                        } else if (data.pelanggan) {
                            Swal.fire({
                                title: 'Error!',
                                text: data.errors,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                            $('#btnSubmit').html('Simpan');
                            document.getElementById("btnSubmit").disabled = false;
                        } else {
                            Swal.fire({
                                title: 'success!',
                                text: 'Berhasil Menyimpan Data!',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(function() {
                                window.location = "{{ route('admin.so.index') }}";
                            });
                        }
                    },
                    error: function(data) {
                        Swal.fire({
                            title: 'Error!',
                            text: "Error pada server, Silahkan hubungi Administrator!",
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#btnSubmit').html('Simpan');

                        document.getElementById("btnSubmit").disabled = false;
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Batal',
                    text: 'Data tidak tersimpan',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });

            }
        });
    });

    function rupiahJs(e) {
        // console.log($('#'+$(e).attr('id')).val());

        $('#' + $(e).attr('id')).val(formatRupiah($(e).val(), 'Rp. '));
    }

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection