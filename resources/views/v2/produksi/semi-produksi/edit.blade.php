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

<a href="{{ route('produksi.semi-index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{ route('produksi.semi-update', $data->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Permintaan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Semi Produksi
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Nomer Produksi*</label>
                    <input type="text" name="nomer_produksi" class="form-control" required value="{{ old('nomer_produksi', $data->nomer_produksi) }}">

                    <label for="">Tanggal Produksi*</label>
                    <input type="date" name="tanggal_produksi" class="form-control" value="{{ old('tanggal_produksi', $data->tanggal_produksi) ?: date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" cols="30" rows="4" class="form-control" placeholder="kosongkan bila tidak perlu">{{ old('keterangan', $data->keterangan) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Rincian -->
    {{-- @dump(old()) --}}
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Rincian Item
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 200px;">item*</th>
                            <th style="width: 100px;">qty*</th>
                            <th>catatan</th>
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-outline-success btn-tambah-rincian">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="rincian-produk">
                        @if (old('rincian'))
                            @foreach (old('rincian') as $val)
                                <tr class="rincian-{{$loop->index}}">
                                    <td>
                                        <select name="rincian[{{$loop->index}}][barang_id]" class="form-control select-item">
                                            @foreach ($barang as $bar)
                                                <option value="{{ $bar->id }}" 
                                                    @if ($val['barang_id'] == $bar->id)
                                                        selected
                                                    @endif
                                                >{{ $bar->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input name="rincian[{{$loop->index}}][kuantitas]" type="number" value="{{ $val['kuantitas'] }}" class="form-control" min="1" value="1">
                                    </td>
                                    <td>
                                        <input name="rincian[{{$loop->index}}][catatan]" type="text" value="{{ $val['catatan'] }}" class="form-control" placeholder="opsional">
                                    </td>
                                    <td style="text-align: center">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-rincian">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>                                
                            @endforeach
                        @else
                            @foreach ($data->semiProduksiRinci as $key => $item)
                                <tr class="rincian-{{$key}}">
                                    <td>
                                        <select name="rincian[{{$key}}][barang_id]" class="form-control select-item">
                                            <option value="{{ $item->barang_id }}">{{ $item->barang->nama_barang }}</option>
                                            @foreach ($barang as $bar)
                                                <option value="{{ $bar->id }}">{{ $bar->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input name="rincian[{{$key}}][kuantitas]" type="number" class="form-control" min="1" value="{{ $item->kuantitas }}">
                                    </td>
                                    <td>
                                        <input name="rincian[{{$key}}][catatan]" type="text" class="form-control" placeholder="opsional" value="{{$item->catatan}}">
                                    </td>
                                    <td style="text-align: center">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-rincian">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    {{-- @dump($data) --}}

    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Dijadikan Produk
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 200px;">produk*</th>
                            <th style="width: 100px;">qty*</th>
                            <th>catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="barang_id" class="form-control select-item">
                                    <option value="{{ $data->barang_id }}" selected>{{ $data->barang->nama_barang }}</option>
                                    @foreach ($barang as $bar)
                                        <option value="{{ $bar->id }}" @if (old('barang_id') == $bar->id)
                                            selected
                                        @endif>{{ $bar->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="kuantitas" type="number" class="form-control" min="1" value="{{old('kuantitas', $data->kuantitas) ?: 1}}">
                            </td>
                            <td>
                                <input name="catatan" type="text" value="{{old('catatan', $data->catatan)}}" class="form-control" placeholder="opsional">
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

        let rowRincian = $('.rincian-produk')[0].rows.length;

        const $tabelRincianProduk = $('.rincian-produk');
        // const table = $('.btn-hapus-rincian');
        // console.log(table);
        const $btnTambahRincian = $('.btn-tambah-rincian');

        $('.select-item').select2();

        // fungsi untuk menambahkan baris rincian
        $btnTambahRincian.on('click', function(e) {
            let htmlRincian = `<tr class="rincian-${rowRincian}">
                                <td>
                                    <select name="rincian[${rowRincian}][barang_id]" class="form-control select-item">
                                        <option value="">PILIH ITEM</option>
                                        @foreach ($barang as $bar)
                                            <option value="{{ $bar->id }}">{{ $bar->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][kuantitas]" type="number" class="form-control" min="1" value="1">
                                </td>
                                <td>
                                    <input name="rincian[${rowRincian}][catatan]" type="text" class="form-control" placeholder="opsional">
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
            deleteFilter()
        });

        function deleteFilter()
        {
            if ($('.rincian-produk')[0].rows.length == 1) {
                $('.btn-hapus-rincian')[0].disabled = true
            }else{
                $('.btn-hapus-rincian')[0].disabled = false
            }
        }

        deleteFilter()

        // fungsi untuk menghapus baris rincian
        $(document).on('click', '.btn-hapus-rincian', function(e) {
            $(this).closest('tr').remove();
            deleteFilter()
        });
    });
</script>
@endsection