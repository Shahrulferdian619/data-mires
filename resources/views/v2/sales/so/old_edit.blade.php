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
Edit Sales Order (Ubah Pesanan Penjualan)
@endsection

@section('content')
@if(session()->has('fail'))
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session()->get('fail') }}</li>
        </ul>
    </div>
</div>
@endif
@if($errors->all())
@include('layouts.validation')
@endif
<form action="{{ url('admin/so/'.$so->id.'/edit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1">
                        <label for="so_nomer" class="form-label">Nomor Penjualan</label>
                        <input type="text" name="so_nomer" id="so_nomer" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_nomer'] : $so->so_nomer }}" class="form-control">
                    </div>
                    <div class="mb-1">
                        <label for="jenis_penjualan" class="form-label">Jenis Penjualan</label>
                        <select class="select2 form-control" name="jenis_penjualan" id="jenis_penjualan">

                            @foreach ($jenis_penjualan as $item)
                            <option <?php if ($so->jenis_penjualan == $item->id) {
                                        echo 'selected';
                                    } ?> value="{{ $item->id }}" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == $item->id ? 'selected' : '') : '' }}>{{ $item->jenis_penjualan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-1">
                        <label for="no_pesanan" class="form-label">Nomor Pesanan</label>
                        <input type="text" name="no_pesanan" id="no_pesanan" value="{{ session()->has('no_pesanan') ? session()->get('so_penjualan')['so_nomer'] : $so->no_pesanan }}" class="form-control" required>
                    </div>
                    <!-- <div class="mb-1">
                            <label for="id_sales" class="form-label">Sales</label>
                            <select name="id_sales" id="" class="form-control">
                                <option value="0">-- Pilih Sales --</option>
                                <option value="1">Ibnu Khafid</option>
                            </select>
                        </div> -->
                    <div class="mb-1">
                        <label for="so_tanggal" class="form-label">Tanggal Penjualan</label>
                        <input type="date" name="so_tanggal" id="so_tanggal" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_tanggal'] : date($so->so_tanggal) }}" class="form-control flatpickr-basic flatpickr-input">
                    </div>
                    <div class="mb-1">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control" value="-">{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['keterangan'] : $so->keterangan }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1">
                        <label for="id_pelanggan" class="form-label">Pelanggan</label>
                        <select class="select2 form-control" name="id_pelanggan" id="id_pelanggan">
                            @foreach ($customer as $item)
                            <option <?php if ($so->id_pelanggan == $item->id) {
                                        echo 'selected';
                                    } ?> value="{{$item->id}}" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['id_pelanggan'] == $item->id ? 'selected' : '') : '' }}>{{ $item->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="is_tax" class="form-label">PPN</label>
                        <select class="select2 form-control" name="is_tax" id="is_tax">
                            <option <?php if ($so->is_tax == 0) {
                                        echo 'selected';
                                    } ?> value="0" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 0 ? 'selected' : '') : '' }}>Tidak</option>
                            <option <?php if ($so->is_tax == 1) {
                                        echo 'selected';
                                    } ?> value="1" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 1 ? 'selected' : '') : '' }}>Iya</option>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label for="ekspedisi" class="form-label">Ekspedisi</label>
                        <select class="select2 form-control form-select" name="ekspedisi" id="ekspedisi">
                            <option value="">-- Tanpa Ekspedisi --</option>
                            <option <?php if ($so->ekspedisi == 'J&T') { echo 'selected'; } ?> value="J&T">J&T</option>
                            <option <?php if ($so->ekspedisi == 'Sicepat') { echo 'selected'; } ?> value="Sicepat">Sicepat</option>
                            <option <?php if ($so->ekspedisi == 'Sicepat Halu') { echo 'selected'; } ?> value="Sicepat Halu">Sicepat Halu</option>
                            <option <?php if ($so->ekspedisi == 'JNE') { echo 'selected'; } ?> value="JNE">JNE</option>
                            <option <?php if ($so->ekspedisi == 'Gosend') { echo 'selected'; } ?> value="Gosend">Gosend</option>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label for="resi" class="form-label">Nomor Resi</label>
                        <input type="text" name="resi" id="resi" value="{{ session()->has('resi') ? session()->get('so_penjualan')['so_nomer'] : $so->resi }}" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th class="text-align" >#</th>
                                    <th class="text-align" >Nama Barang</th>
                                    <th class="text-align" >Jumlah Barang</th>
                                    <th class="text-align" >Diskon Barang</th>
                                    <th class="text-align" >Harga Barang</th>
                                </tr>
                            </thead>
                            @php
                                $grandTotal = 0;
                            @endphp
                            <tbody>
                                @foreach($so->rinci as $rinci)
                               <tr>
                                   <td>{{ $loop->iteration }}</td>
        <td>{{ $rinci->barang->nama_barang }}</td>
        <td>{{ $rinci->qty_barang }}</td>
        <td>{{ $rinci->diskon_barang }}%</td>
        <td>Rp.{{ number_format($rinci->harga_barang) }}</td>
        @php
        $grandTotal += ($rinci->harga_barang - ($rinci->harga_barang * $rinci->diskon_barang / 100)) * $rinci->qty_barang ;
        @endphp
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="4">Total Harga</th>
                <th class="text-right">Rp. {{ number_format($grandTotal) }}</th>
            </tr>
        </tfoot>
        </table>
    </div>
    </div>
    </div> --}}

    @if($so->status_do == 0)
    <div class="col-12 mb-1">
        <div class="card">
            <div class="card-header">
                <h3>Rincian</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="flag">
                        <thead>
                            <tr>
                                <th width="25%">Barang <span class="text-danger">*</span></th>
                                <th width="10%">QTY <span class="text-danger">*</span></th>
                                <th>Harga <span class="text-danger">*</span></th>
                                <th width="5%">Diskon Persentase</th>
                                <th width="5%">Diskon Nominal</th>
                                <th width="5%">Potongan Admin</th>
                                <th width="5%">Cashback Ongkir</th>
                                <th>Note</th>
                                <th width="10%">#</th>
                            </tr>
                        </thead>
                        <tbody class="flag_row">
                            @php
                            $noUnik = 456;
                            @endphp
                            @foreach($so->rinci as $rinci)
                            <tr>
                                <td>
                                    <input type="hidden" name="penjualan_so_rinci_id[]" value="{{ $rinci->id }}" id="">
                                    <select class="form-control selectNya" id="id_barang" name="id_barang[]" required>
                                        <option value="">-- PRODUK --</option>
                                        @foreach($items as $barang)
                                        <option value="{{ $barang->id }}" {{ $barang->id == $rinci->id_barang ? "selected" : "" }}>{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control qty" name="qty[]" id="qty" value="{{ $rinci->qty_barang }}" placeholder="Masukan Qty..." required>
                                </td>
                                <td>
                                    <input type="text" class="form-control harga" name="harga[]" id="harga_{{ $noUnik }}" value="{{ $rinci->harga_barang }}" placeholder="Masukan Harga..." onKeyup="rupiahJs(this)" required>
                                </td>
                                <td><input type="number" max="100" class="form-control diskon" name="diskon[]" id="diskon" placeholder="%..." required value="{{ $rinci->diskon_barang }}"></td>
                                <td>
                                    <input type="text" class="form-control" name="diskon_nominal[]" id="diskon_nominal_{{ $noUnik }}" onKeyup="rupiahJs(this)" placeholder="Masukkan nilai" value="{{ $rinci->diskon_nominal }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="potongan_admin[]" id="potongan_admin_{{ $noUnik }}" onKeyup="rupiahJs(this)" placeholder="Masukkan nilai" value="{{ $rinci->potongan_admin }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="cashback_ongkir[]" id="cashback_ongkir_{{ $noUnik }}" onKeyup="rupiahJs(this)" placeholder="Masukkan nilai" value="{{ $rinci->cashback_ongkir }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="note[]" id="note" value="{{ $rinci->note }}" placeholder="Masukan Note...">
                                </td>
                                <td>
                                    <button onclick="destroyRinci(this)" data-id="{{ $rinci->id }}" style="width:100%" class="btn btn-outline-danger btn-sm" type="button"><i class="fa fa-trash"></i> Hapus</button>
                                </td>
                            </tr>
                            @php
                            $noUnik++;
                            @endphp
                            @endforeach
                            <tr>
                                <td>
                                    <input type="hidden" name="penjualan_so_rinci_id[]" value="" id="">
                                    <select class="form-control selectNya" id="id_barang" name="id_barang[]">
                                        <option value="">-- PRODUK --</option>
                                        @foreach($items as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control qty" name="qty[]" id="qty" value="" placeholder="Masukan Qty...">

                                </td>
                                <td>
                                    <input type="text" class="form-control harga" name="harga[]" id="harga_999" value="" placeholder="Masukan Harga..." onKeyup="rupiahJs(this)">

                                </td>
                                <td>
                                    <input type="number" max="100" class="form-control diskon" name="diskon[]" id="diskon" placeholder="%..." value="0">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="diskon_nominal[]" id="diskon_nominal_999" onKeyup="rupiahJs(this)" placeholder="Masukkan nilai" value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="potongan_admin[]" id="potongan_admin_999" onKeyup="rupiahJs(this)" placeholder="Masukkan nilai" value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="cashback_ongkir[]" id="cashback_ongkir_999" onKeyup="rupiahJs(this)" placeholder="Masukkan nilai" value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="note[]" id="note" value="-" placeholder="Masukan Note...">
                                </td>
                                <td>
                                    <button type="button" style="width:100%" class="btn btn-outline-primary btn-sm btn-item">
                                        <i class="fa fa-plus"></i>
                                        Tambah
                                    </button>
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
                    <div class="col-md-12">
                        <span><b>* Abaikan input file jika tidak ingin mengubah file.</b></span>
                        @if (!empty($berkas))
                        <br>
                        <br>
                        <p>List file:</p>
                        <ul>
                            <li>Berkas 1 : {{ $berkas->berkas_1 == "" ? "Tidak ada" : $berkas->berkas_1 }}</li>
                            <li>Berkas 2 : {{ $berkas->berkas_2 == "" ? "Tidak ada" : $berkas->berkas_2 }}</li>
                            <li>Berkas 3 : {{ $berkas->berkas_3 == "" ? "Tidak ada" : $berkas->berkas_3 }}</li>
                            <li>Berkas 4 : {{ $berkas->berkas_4 == "" ? "Tidak ada" : $berkas->berkas_4 }}</li>
                            <li>Berkas 5 : {{ $berkas->berkas_5 == "" ? "Tidak ada" : $berkas->berkas_5 }}</li>
                        </ul>
                        @endif

                    </div>
                    <div class="col-md-9 col-12">
                        <label>Berkas</label>
                        <input type="file" name="berkas[]" id="berkas_1" class="form-control">
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
    @endif
    <div class="col-12 mb-1">
        <div class="card">
            <div class="card-body">
                <a href="{{ url('admin/so/'.$so->id) }}" class="btn btn-outline-danger">Kembali</a>
                <button class="btn btn-outline-primary" name="create-so-penjualan">Update Data</button>
            </div>
        </div>
    </div>
    </div>
</form>

<script>
    document.querySelector('#diskon_barang').onkeyup = function(e) {
        // console.log(this.value)
        if (this.value != '') {
            if (this.value > 100) {
                this.value = 100
            }
        }
    }
</script>
@endsection
@section('myjs')
<script type="text/javascript">
    //table
    var flagNya = 1;
    $('.btn-item').on('click', function() {

        var newRow =
            '<tr class="flag_' + flagNya + '">' +
            '<td>' +
            '<input type="hidden" name="penjualan_so_rinci_id[]" value="" id="">' +
            '<select class="selectNya form-control" name="id_barang[]">' +
            '<option value="">-- PRODUK --</option>' +
            @foreach($items as $barang)
        '<option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>' +
        @endforeach
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control qty" name="qty[]" placeholder="Masukan Qty...">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control harga" name="harga[]" id="harga_' + flagNya + '" placeholder="Masukan Harga..."  onKeyup = "rupiahJs(this)">' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control diskon" name="diskon[]" value="0" placeholder="%...">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="diskon_nominal[]" id="diskon_nominal_' + flagNya + '" onKeyup = "rupiahJs(this)" value="" placeholder="Masukkan nilai">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="potongan_admin[]" id="potongan_admin_' + flagNya + '" onKeyup = "rupiahJs(this)" value="" placeholder="Masukkan nilai">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="cashback_ongkir[]" id="cashback_ongkir_' + flagNya + '" onKeyup = "rupiahJs(this)" value="" placeholder="Masukkan nilai">' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="note[]" value="-" placeholder="Masukan Note...">' +
            '</td>' +
            '<td><button type="button" class="btn btn-outline-danger btn-hps-item btn-sm" style="width:100%"  data-flag="flag_' + flagNya + '"><i class="fa fa-trash"></i> Hapus</button></td>' +
            '</tr>';

        $('#flag tbody tr:last').after(newRow);
        $('.selectNya').select2();

        flagNya++;

    });
    $('.flag_row').on('click', '.btn-hps-item', function() {
        $('.' + $(this).data("flag")).remove();
    });

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
                '<input type="file" name="berkas[]" id="berkas_' + flag + '" class="form-control" style="width:98%">' +
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

    function rupiahJs(e) {
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

    //Hapus Rincian
    function destroyRinci(e) {
        var id = $(e).attr('data-id');

        $.ajax({
            type: 'post',
            url: "{{ route('admin.so.destroy.rinci') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (data) => {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Salah satu rincian berhasil dihapus',
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                }).then(function() {
                    location.reload();
                });

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
            },
        });

    }
</script>
@endsection