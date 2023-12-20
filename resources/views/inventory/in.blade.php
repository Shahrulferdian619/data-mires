@extends('layouts.vuexy')

@section('header')
Stock In (Stok Masuk)
@endsection

@section('content')

@if($errors->all())
    @include('layouts.validation')
@endif

<a href="{{ url('admin/list-inventory/all') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

    <form id="form-save" action="{{ url('admin/list-inventory/menu/stock-in') }}" method="POST" enctype="multipart-form-data">
        <div class="row match-height">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        @csrf
                        <label>Nomor Transaksi<span class="text-danger">*</span></label>
                        <input required type="text" class="form-control" name="nomer">

                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input required type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>

                        <label>Gudang <span class="text-danger">*</span></label>
                        <select class="form-control" name="gudang_id" id="">
                            <option value="0">-- Pilih Gudang --</option>
                            @foreach($gudang as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Barang Masuk</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="flag">
                                <thead>
                                    <tr>
                                        <th width="25%">Pilih Barang</th>
                                        <th>Qty</th>
                                        <th>Harga Beli</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="flag_row">
                                    <tr>
                                        <td>
                                            <input type="hidden" name="index[0]" value="0" id="">
                                            <select class="selectNya form-control" name="barang_id[0]">
                                                @foreach($barang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input required type="number" class="form-control" name="qty[0]" id="qty" placeholder="Qty...">
                                        </td>
                                        <td>
                                            <input required type="text" class="form-control" name="hargabeli[0]" id="harga-beli" placeholder="Harga Beli...">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-item">
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

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-outline-primary" type="submit">Simpan</button>
                        <a href="{{ url('admin/list-inventory/all') }}" class="btn btn-outline-danger">Batal</a>
                    </div>
                </div>
            </div>
        </div>  
    </form>
@endsection

@section('myjs')
<script type="text/javascript">
    //format rupiah
    var format = document.getElementById('harga-beli');
    format.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatformat() untuk mengubah angka yang di ketik menjadi format angka
        format.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }


    //rincian jurnal voucher
    var flag = 1;
    $('.btn-item').on('click', function() {
        var newRow = 
        '<tr class="flag_'+flag+'">'+
            '<td>'+
                '<input type="hidden" name="index['+flag+']" value="'+flag+'" id="">'+
                '<select class="selectNya form-control" name="barang_id['+flag+']" required>'+
                    @foreach($barang as $item)
                    '<option value="{{ $item->id }}">{{ $item->nama_barang }}</option>'+
                    @endforeach
                '</select>'+
            '</td>'+
            '<td>'+
                '<input required type="number" class="form-control" name="qty['+flag+']" id="qty_'+flag+'" placeholder="Qty...">'+
            '</td>'+
            '<td>'+
                '<input required type="text" class="form-control" name="hargabeli['+flag+']" id="harga-beli_'+flag+'" placeholder="Harga Beli..." onKeyup = "rupiahJs(this)">'+
            '</td>'+
            '<td><button type="button" class="btn btn-outline-danger btn-hps-item btn-sm"  data-flag="flag_'+flag+'"><i class="fa fa-trash"></i> Hapus</button></td>'+
        '</tr>';
        
        $('#flag tbody tr:last').after(newRow);


        flag++;
    });
    $('.flag_row').on('click', '.btn-hps-item',function() {
        $('.'+$(this).data("flag")).remove();
    });

    function rupiahJs(e){
        $('#'+$(e).attr('id')).val(formatRupiah($(e).val(), 'Rp. '));
    }



</script>
@endsection