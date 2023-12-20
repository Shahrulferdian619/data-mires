@extends('v2.layout.vuexy')

@section('title')PR : {{$permintaan->nomer_permintaan_pembelian}} |@endsection

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

<a href="{{ route('pembelian.permintaan-pembelian.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pembelian.permintaan-pembelian.update', $permintaan->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Permintaan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Permintaan Pembelian
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Tipe permintaan</label>
                    <select name="tipe_permintaan" class="form-control" required>
                        <option value="{{ $permintaan->tipe_permintaan }}">@if($permintaan->tipe_permintaan == 1)PRODUK @elseif($permintaan->tipe_permintaan == 2)ASSET @elseif($permintaan->tipe_permintaan == 3)JASA @else LAINNYA @endif</option>
                        <option value="1">PRODUK</option>
                        <option value="2">ASSET</option>
                        <option value="3">JASA</option>
                        <option value="4">LAINNYA</option>
                    </select>
                    <label for="">Nomer permintaan pembelian*</label>
                    <input type="text" name="nomer_permintaan_pembelian" class="form-control" value="{{ $permintaan->nomer_permintaan_pembelian }}" required>

                    <label for="">Tanggal*</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d',strtotime($permintaan->tanggal)) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" cols="30" rows="6" class="form-control" placeholder="kosongkan bila tidak perlu">{{ $permintaan->keterangan }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Rincian -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Rincian Permintaan
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>item*</th>
                            <th>deskripsi item</th>
                            <th style="width: 100px;">qty*</th>
                            <th style="width: 120px;">harga</th>
                            <th style="width: 200px;">catatan</th>
                            <th style="width: 100px;">tgl. minta*</th>
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-outline-success btn-tambah-rincian">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="rincian-produk">
                        @php $row = 0 @endphp
                        @foreach($permintaan->rincianPermintaan as $rinci)
                        <tr class="rincian-{{$row}}">
                            <td>
                                <select name="rincian[{{$row}}][item_id]" class="form-control select-item" required>
                                    <option value="{{ $rinci->item_id }}">{{$rinci->item->nama_barang}}</option>
                                    @foreach($item as $i)
                                    <option value="{{ $i->id }}">{{ $i->kode_barang }} | {{ $i->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="rincian[{{$row}}][deskripsi_item]" type="text" value="{{ $rinci->deskripsi_item }}" class="form-control" placeholder="abaikan bila item produk/asset...">
                            </td>
                            <td>
                                <input name="rincian[{{$row}}][kuantitas]" type="number" value="{{ $rinci->kuantitas }}" class="form-control" min="1" required>
                            </td>
                            <td>
                                <input name="rincian[{{$row}}][harga]" type="number" value="{{ $rinci->harga }}" class="form-control" min="1" placeholder="opsional">
                            </td>
                            <td>
                                <input name="rincian[{{$row}}][catatan]" type="text" value="{{ $rinci->catatan }}" class="form-control" placeholder="opsional">
                            </td>
                            <td>
                                <input name="rincian[{{$row}}][tanggal_minta]" type="date" class="form-control" value="{{ date('Y-m-d',strtotime($rinci->tanggal_minta)) }}" required>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-rincian">
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
                <table class="table table-borderless tabel-berkas">
                    <tr class="berkas-0">
                        <td>Berkas</td>
                        <td>
                            <input type="file" name="berkas[0][nama_berkas]" class="form-control">
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-success btn-tambah-berkas">
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
            <a href="{{ route('pembelian.permintaan-pembelian.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {

        let rowRincian = {{ $countRincian }};
        let rowBerkas = 1;

        const $tabelRincianProduk = $('.rincian-produk');
        const $btnTambahRincian = $('.btn-tambah-rincian');
        const $tabelBerkas = $('.tabel-berkas');
        const $btnTambahBerkas = $('.btn-tambah-berkas');

        $('.select-item').select2();

        // fungsi untuk menambahkan baris rincian
        $btnTambahRincian.on('click', function(e) {
            let htmlRincian = `<tr class="rincian-${rowRincian}">
                                <td>
                                    <select name="rincian[${rowRincian}][item_id]" class="form-control select-item" required>
                                        <option value="">PILIH ITEM</option>
                                        @foreach($item as $i)
                                        <option value="{{ $i->id }}">{{ $i->kode_barang }} | {{ $i->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][deskripsi_item]" type="text" class="form-control" placeholder="abaikan bila item produk/asset...">
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][kuantitas]" type="number" class="form-control" min="1" value="1" required>
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][harga]" type="number" class="form-control" min="1" placeholder="opsional">
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][catatan]" type="text" class="form-control" placeholder="opsional">
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][tanggal_minta]" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </td>
                                <td style="text-align: center">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-rincian">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;

            $tabelRincianProduk.append(htmlRincian); // insert html rincian ke tabel
            $('.select-item').select2();

            rowRincian++;
        });

        // fungsi untuk menghapus baris rincian
        $(document).on('click', '.btn-hapus-rincian', function(e) {
            $(this).closest('tr').remove();
        });

        // fungsi untuk menambahkan baris berkas
        $btnTambahBerkas.on('click', function(e) {
            let htmlBerkas = `<tr class="berkas-${rowBerkas}">
                                <td>Berkas</td>
                                <td>
                                    <input type="file" name="berkas[${rowBerkas}][nama_berkas]" class="form-control">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger btn-hapus-berkas">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;

            $tabelBerkas.append(htmlBerkas);
            rowBerkas++;
        })

        // fungsi untuk menghapus baris berkas
        $(document).on('click', '.btn-hapus-berkas', function(e) {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection