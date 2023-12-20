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

<a href="{{ route('pindah-stok.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pindah-stok.update',$pindah_stok->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Pindah stok -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pindah Stok
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Gudang asal</label>
                    <select name="gudang_asal_id" class="form-control select2 select-gudang" required>
                        <option value="{{ $pindah_stok->gudang_asal_id }}">{{ $pindah_stok->gudangAsal->nama_gudang }}</option>
                        <option value="gudang-baru">-- GUDANG BARU --</option>
                        @foreach($gudang as $g)
                        <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>

                    <label for="">Gudang tujuan</label>
                    <select name="gudang_tujuan_id" class="form-control select2 select-gudang" required>
                        <option value="{{ $pindah_stok->gudang_tujuan_id }}">{{ $pindah_stok->gudangTujuan->nama_gudang }}</option>
                        <option value="gudang-baru">-- GUDANG BARU --</option>
                        @foreach($gudang as $g)
                        <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>

                    <label for="">Nomer ref</label>
                    <input name="nomer_ref" type="text" class="form-control" value="{{ $pindah_stok->nomer_ref }}" required>

                    <label for="">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $pindah_stok->tanggal }}" required>
                </div>

                <div class="col-md-6">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" id="" cols="30" rows="8" class="form-control">{{ $pindah_stok->keterangan }}</textarea>
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
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>produk</th>
                            <th style="width: 100px;">qty</th>
                            <th style="width: 200px;">catatan</th>
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-success btn-tambah-produk">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="rincian-produk">
                        @php $row = 0 @endphp
                        @foreach($pindah_stok->rincianProduk as $rinci)
                        <tr class="rincian-produk-{{$row}}">
                            <td>
                                <select name="rincian[{{$row}}][produk_id]" class="form-control select2" required>
                                    <option value="{{ $rinci->produk_id }}">{{ $rinci->produk->nama_barang }}</option>
                                    @foreach($produk as $p)
                                    <option value="{{$p->id}}">{{$p->kode_barang}} | {{$p->nama_barang}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input name="rincian[{{$row}}][kuantitas]" type="number" min="1" class="form-control kuantitas" value="{{ $rinci->kuantitas }}" required>
                            </td>

                            <td>
                                <input name="rincian[{{$row}}][catatan]" type="text" class="form-control" value="{{ $rinci->catatan }}">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-produk">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @php $row++ @endphp
                        @endforeach
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
                <table class="table table-borderless">
                    <tr class="berkas-0">
                        <td>Berkas</td>
                        <td>
                            <input type="file" name="berkas[0][nama_berkas]" id="" class="form-control">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-success">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('pindah-stok.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

<!-- Modal gudang baru -->
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

<!-- Modal paket -->
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
                                @foreach($paket as $p)
                                <option value="{{ $p->id }}">{{ $p->packet_name }}</option>
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

        // deklarasi variable global
        let rowProduk = {{$countRincian}};
        const $btnTambahProduk = $('.btn-tambah-produk');
        const $tabelProduk = $('.rincian-produk');

        $('.select2').select2();

        //fungsi ketika melakukan select gudang baru
        $('.select-gudang').on('change', function(e) {
            let select_gudang = $(this).val();

            //tampilkan modal gudang baru
            if (select_gudang === 'gudang-baru') {
                $('#modalGudang').modal('show');
            }
        });

        // proses menambahkan baris rincian produk
        $btnTambahProduk.on('click', function(e) {
            const htmlProduk = `<tr class="rincian-produk-${rowProduk}">
                                    <td>
                                        <select name="rincian[${rowProduk}][produk_id]" class="form-control select2" required>
                                            <option value="">-- CARI PRODUK --</option>
                                            @foreach($produk as $p)
                                            <option value="{{$p->id}}">{{$p->kode_barang}} | {{$p->nama_barang}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input name="rincian[${rowProduk}][kuantitas]" type="number" min="1" class="form-control kuantitas" value="1" required>
                                    </td>

                                    <td>
                                        <input name="rincian[${rowProduk}][catatan]" type="text" class="form-control">
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-produk">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            $tabelProduk.append(htmlProduk);
            $('.select2').select2();
            rowProduk++;
        });

        // proses menghapus baris rincian produk
        $(document).on('click', '.btn-hapus-produk', function(e) {
            $(this).closest('tr').remove();
        });

        // menampilkan modal paket
        $('.btn-modal-paket').on('click', function(e) {
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
                        let rowPaket = `<tr class="rincian-produk-${rowProduk}">
                                            <td style="background: #ebebeb;">${data[i].barang.nama_barang}</td>
                                            <td style="background: #ebebeb;">
                                                <input name="rincian[${rowProduk}][produk_id]" type="hidden" value="${data[i].id_barang}">
                                                <input name="rincian[${rowProduk}][kuantitas]" type="text" class="form-control kuantitas" value="${qty_paket}">
                                            </td>
                                            <td style="background: #ebebeb;">${data.nama_paket}
                                                <input name="rincian[${rowProduk}][catatan]" class="form-control" type="hidden" value="${data.nama_paket}">
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-produk">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`;


                        $tabelProduk.append(rowPaket);
                        rowProduk++;
                    }
                }
            });

        });
    });
</script>
@endsection