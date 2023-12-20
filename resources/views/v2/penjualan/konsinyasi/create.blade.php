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

<a href="{{ route('konsinyasi.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<form action="{{ route('konsinyasi.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Header Konsinyasi -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pelanggan
            </h5>
        </div>

        <div class="card-body">
            <select name="pelanggan_id" class="form-control select2 pelanggan" required>
                <option value="">-- PILIH PELANGGAN --</option>
                <option value="pelanggan-baru">-- PELANGGAN BARU --</option>
                @foreach($pelanggan as $pel)
                <option value="{{ $pel->id }}">{{ $pel->kode_pelanggan }} | {{ $pel->nama_pelanggan }}</option>
                @endforeach
            </select>

            <label for="">Pelanggan</label>
            <input name="nama_pelanggan" type="text" class="form-control nama-pelanggan" readonly>

            <label for="">Alamat pelanggan</label>
            <textarea name="alamat_pelanggan" cols="30" rows="5" class="form-control alamat-pelanggan" readonly></textarea>
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
            <select name="gudang_asal" class="form-control select2 gudang" required>
                <option value="">-- PILIH GUDANG --</option>
                <option value="gudang-baru">-- GUDANG BARU --</option>
                @foreach($gudang as $g)
                <option value="{{ $g->nama_gudang }}">{{ $g->nama_gudang }}</option>
                @endforeach
            </select>

            <label for="">Gudang Tujuan</label>
            <select name="gudang_tujuan" class="form-control select2 gudang" required>
                <option value="">-- PILIH GUDANG --</option>
                <option value="gudang-baru">-- GUDANG BARU --</option>
                @foreach($gudang as $g)
                <option value="{{ $g->nama_gudang }}">{{ $g->nama_gudang }}</option>
                @endforeach
            </select>
            <label for="">Nomer konsinyasi</label>
            <input name="nomer_konsinyasi" type="text" class="form-control" value="{{ $nomer }}" required>

            <label for="">Tanggal konsinyasi</label>
            <input name="tanggal_konsinyasi" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>

            <label for="">Keterangan</label>
            <textarea name="keterangan" cols="30" rows="5" class="form-control"></textarea>

            <hr>
            <label for=""><b>Alamat pengiriman</b></label>
            <br>
            <input class="form-check-input sama-dengan-pemesan" type="checkbox" value="checked" />
            <label class="form-check-label" for="">Sama dengan pelanggan</label>

            <br>
            <label for="">Penerima</label>
            <input name="penerima" type="text" class="form-control penerima">

            <label for="">Alamat penerima</label>
            <textarea name="alamat_penerima" cols="30" rows="5" class="form-control alamat-penerima"></textarea>
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
                <button class="btn btn-sm btn-outline-warning btn-modal-paket" type="button">tambah paket</button>
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
                            <th style="text-align: center; width: 50px">
                                <button type="button" class="btn btn-success btn-sm btn-tambah-row">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="rincian-produk">
                        <tr>
                            <td>
                                <select name="produk_id[]" class="form-control select2 produk" required>
                                    <option value="">-- PILIH PRODUK --</option>
                                    @foreach($produk as $p)
                                    <option value="{{ $p->id }}">{{ $p->kode_barang }} | {{ $p->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="kuantitas[]" type="text" class="form-control kuantitas" required>
                            </td>
                            <td>
                                <input name="harga[]" type="text" class="form-control harga-produk" required>
                            </td>
                            <td>
                                <input name="subtotal[]" type="text" class="form-control subtotal" readonly hidden>
                                <input type="text" class="form-control konversi-subtotal" readonly>
                            </td>
                            <td>
                                <input name="catatan[]" type="text" class="form-control catatan">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-row">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;">Total</td>
                            <td colspan="3">
                                <input name="grandtotal" type="text" class="form-control grandtotal" readonly hidden>
                                <input type="text" class="form-control konversi-grandtotal" readonly>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Berkas Pendukung -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">Data Berkas</h5>
        </div>

        <div class="card-body">
            <input type="file" name="berkas1" class="form-control">
        </div>
    </div>

    <br>

    <!-- Button Simpan -->
    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-primary btn-simpan">Simpan</button>
            <a href="{{ route('konsinyasi.index') }}" class="btn btn-outline-warning">Batal</a>
        </div>
    </div>

</form>

<!-- Modal tambah gudang baru direct -->
<div class="modal fade" id="modalGudang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('konsinyasi.gudang-baru') }}" method="post" style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Gudang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Nama Gudang</label>
                    <input name="nama_gudang" type="text" class="form-control" required>

                    <label for="">PIC Gudang</label>
                    <input name="pic_gudang" type="text" class="form-control">

                    <label for="">Alamat</label>
                    <textarea name="alamat_gudang" cols="30" rows="5" class="form-control"></textarea>

                    <label for="">Keterangan</label>
                    <textarea name="keterangan" cols="30" rows="5" class="form-control"></textarea>
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
                    <select name="provinsi" class="form-control provinsi" required>
                        <option value="">-- PILIH PROVINSI --</option>
                        @foreach($provinsi as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>

                    <label for="">Kota</label>
                    <select name="kota" class="form-control kota" required>
                        <option value="">-- PILIH KOTA --</option>
                    </select>

                    <label for="">Detil alamat</label>
                    <textarea name="detail_alamat" cols="30" rows="5" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-simpan-pelanggan">Simpan</button>
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
                        <div class="col-md-10">
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
        const $tambahRow = $('.btn-tambah-row');
        const $tabelProduk = $('.rincian-produk');

        $('.select2').select2();

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

        // centang alamat sama dengan pemesan
        $('.sama-dengan-pemesan').on('change', function() {
            if ($('.sama-dengan-pemesan').is(':checked')) {
                $('.penerima').val($('input[name="nama_pelanggan"]').val());
                $('.alamat-penerima').val($('textarea[name="alamat_pelanggan"]').val());

                $('.penerima').css('background-color','#ebebeb').attr('readonly',true);
                $('.alamat-penerima').css('background-color', '#ebebeb').attr('readonly',true);
            } else {
                $('.penerima').val('');
                $('.alamat-penerima').val('');

                $('.penerima').css('background-color','').removeAttr('readonly');
                $('.alamat-penerima').css('background-color', '').removeAttr('readonly');
            }
        });

        // menambah baris rincian produk
        $tambahRow.click(function() {
            const row = `<tr>
                        <td>
                            <select name="produk_id[]" class="form-control select2 produk" required>
                                <option value="">-- PILIH PRODUK --</option>
                                @foreach($produk as $p)
                                <option value="{{ $p->id }}">{{ $p->kode_barang }} | {{ $p->nama_barang }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input name="kuantitas[]" type="text" class="form-control kuantitas" required>
                        </td>
                        <td>
                            <input name="harga[]" type="text" class="form-control harga-produk" required>
                        </td>
                        <td>
                            <input name="subtotal[]" type="text" class="form-control subtotal" readonly hidden>
                            <input type="text" class="form-control konversi-subtotal" readonly>
                        </td>
                        <td>
                            <input name="catatan[]" type="text" class="form-control catatan">
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-danger btn-hapus-row">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;

            $tabelProduk.append(row);
            $('.select2').select2();
        });

        // menghapus baris rincian produk
        $(document).on('click', '.btn-hapus-row', function(event) {
            $(this).closest('tr').remove();
            hitungTotal();
        })

        // modal pelanggan baru
        $('.pelanggan').on('change', function(event) {
            if ($(this).val() == 'pelanggan-baru') {
                $('#modalPelanggan').modal('show');
            }
        })

        // modal gudang baru
        $('.gudang').on('change', function(event) {
            let gudang = $(this).val();

            if (gudang == 'gudang-baru') {
                $('#modalGudang').modal('show');
            }

            compareGudang();
        });

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
                        $(".btn-simpan-pelanggan").attr('disabled', true);
                        $("#modal_kode_pelanggan").addClass('is-invalid');
                    } else {
                        $("#sudah_ada").attr('hidden', true);
                        $(".btn-simpan-pelanggan").attr('disabled', false);
                        $("#modal_kode_pelanggan").removeClass('is-invalid');
                    }

                }
            })

        });

        // melakukan perhitungan ulang apabila ada perubahan pada input rincian produk
        $(document).on('input', '.kuantitas, .harga-produk', function(event) {
            hitungTotal();
        });

        // modal paket
        $('.btn-modal-paket').click(function(event) {
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
                    // console.log(data);

                    for (i = 0; i < data.jumlah; i++) {
                        let rowPaket = `<tr>
                                            <td style="background: #ebebeb;">${data[i].barang.nama_barang}</td>
                                            <td style="background: #ebebeb;">
                                                <input name="produk_id[]" type="hidden" value="${data[i].id_barang}">
                                                <input name="kuantitas[]" type="text" class="form-control kuantitas" value="${qty_paket}">
                                            </td>
                                            <td style="background: #ebebeb;">
                                                <input name="harga[]" type="text" class="form-control harga-produk" value="${data[i].harga}">
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


                        $tabelProduk.append(rowPaket);
                        hitungTotal();
                    }
                }
            });

        });

        // cek apakah gudang asal dan gudang tujuan sama
        function compareGudang() {
            let gudang_asal = $('select[name="gudang_asal"]').val();
            let gudang_tujuan = $('select[name="gudang_tujuan"]').val();

            if (gudang_asal == gudang_tujuan) {
                alert('Gudang Asal & Tujuan tidak boleh sama');
                $('.btn-simpan').attr('disabled', true);
            } else {
                $('.btn-simpan').attr('disabled', false);
            }
        }

        // menghitung subtotal 
        function hitungTotal() {
            let grandtotal = 0;

            $tabelProduk.find('tr').each(function(event) {
                const $row = $(this);
                const harga_produk = $row.find('.harga-produk').val();
                const kuantitas = $row.find('.kuantitas').val();
                const subtotal = harga_produk * kuantitas;
                grandtotal += subtotal

                $row.find('.konversi-subtotal').val(formatRupiah(subtotal))
                $row.find('.subtotal').val(subtotal)
            });
            $('.konversi-grandtotal').val(formatRupiah(grandtotal));
            $('.grandtotal').val(grandtotal);
        }

        // konversi ke format rupiah
        function formatRupiah(nominal) {
            return nominal.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });
        }
    });
</script>
@endsection