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
    
        <form id="form-save" action="{{ url('admin/packet') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1">
                            <label for="nama_paket" class="form-label">Nama Paket</label>
                            <input type="text" name="nama_paket" id="nama_paket" value="" class="form-control" required>
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" value="-"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-header">
                        <h3>Rincian</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="flag">
                                <thead>
                                    <tr>
                                        <th width="25%">Barang <span class="text-danger">*</span></th>
                                        <th width="10%">QTY <span class="text-danger">*</span></th>
                                        <th>Harga Pas (Setelah Dihitung) <span class="text-danger">*</span></th>
                                        <th width="10%">#</th>
                                    </tr>
                                </thead>
                                <tbody class="flag_row">
                                    <tr>
                                        <td>
                                            <select class="form-control selectNya" id="id_barang" name="id_barang[]" required>
                                                <option value="">-- PRODUK --</option>
                                                @foreach($barang as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control qty" name="qty[]" id="qty" value="" placeholder="Masukan Qty..." onKeyup = "total(this)" required>
                                           
                                        </td>
                                        <td>
                                            <input type="number" class="form-control harga" name="harga[]" value="" placeholder="Masukan Harga..." onKeyup = "total(this)" required>
                                        </td>

                                        <td>
                                            <button type="button" style="width:100%" class="btn btn-outline-primary btn-sm btn-item">
                                                <i class="fa fa-plus"></i>
                                                Tambah
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right"><b>Total</b></td>
                                        <td colspan="2" ><span id="total"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('admin/so') }}" class="btn btn-outline-danger">Kembali</a>
                        <button class="btn btn-outline-primary" id="btnSimpan" type="submit">Buat Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">
    
    
    //table
    var flagNya = 1;
    $('.btn-item').on('click', function() {

        var newRow = 
        '<tr class="flag_'+flagNya+'">'+
            '<td>'+
                '<select class="selectNya form-control" name="id_barang[]" required>'+
                    '<option value="">-- PRODUK --</option>'+
                    @foreach($barang as $item)
                        '<option value="{{ $item->id }}">{{ $item->nama_barang }}</option>'+
                    @endforeach
                '</select>'+
            '</td>'+
            '<td>'+
                '<input type="number" class="form-control qty" name="qty[]" placeholder="Masukan Qty..." onKeyup = "total(this)" required>'+
            '</td>'+
            '<td>'+
                '<input type="number" class="form-control harga" name="harga[]" placeholder="Masukan Harga..." required  onKeyup = "total(this)">'+
            '</td>'+
            '<td><button type="button" class="btn btn-outline-danger btn-hps-item btn-sm" style="width:100%"  data-flag="flag_'+flagNya+'"><i class="fa fa-trash"></i> Hapus</button></td>'+
        '</tr>';
        
        $('#flag tbody tr:last').after(newRow);
        $('.selectNya').select2();

        flagNya++;

    });
    $('.flag_row').on('click', '.btn-hps-item',function() {
        $('.'+$(this).data("flag")).remove();
    });

    $('#btnSimpan').on('click', function(e) {   
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
        }).then(function (result) {
            if (result.value) {
                $('#form-save').submit();
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

    function total(e){
        // console.log($('#'+$(e).attr('id')).val());
        let inp_harga = $("input[name='harga[]']")
        let inp_qty = $("input[name='qty[]']")
        
	    // var i;
	    var sum = 0;
	    for(i = 0; i < inp_harga.length; i++) {
            let harga = parseInt(inp_harga[i].value);
            let qty = parseInt(inp_qty[i].value);
            
	        sum +=  qty * harga;
            
	    // console.log(harga[i].value);
	    }
	    $('#total').html(formatRupiah(sum.toString(), 'Rp. '));
    }

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
</script>
@endsection