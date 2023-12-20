@extends('layouts.vuexy')

@section('header')
Create Sales Invoice (Buat Tagihan Penjualan)
@endsection

@section('content')
@if($errors->all())
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            @if(!empty(session('error')))
                <li>{{ session('error') }}</li>
            @endif
        </ul>
    </div>
</div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session()->get('error') }}</li>
        </ul>
    </div>
</div>
@endif

    <form action="{{ url('admin/si/store') }}" id="form-save" method="post">
    @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="si_nomer" class="form-label">Nomor Invoice</label>
                            <input type="text" name="si_nomer" id="si_nomer" value="{{ $nomer_si }}" class="form-control">
                        </div>
                        <div class="mb-1" >
                            <label for="si_tanggal" class="form-label">Tanggal Tagihan Penjualan</label>
                            <input type="date" name="si_tanggal" value="{{ date('Y-m-d') }}" id="si_tanggal" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control">-</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                            <select class="select2 form-select form-control" name="id_pelanggan" id="id_pelanggan">
                                <option value="0">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $pelanggan)
                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1" >
                            <label for="id_so" class="form-label">Pilih Sales Order</label>
                            <select class="select2 form-select form-control" name="id_so" id="id_so">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th class="text-center" >Nama Barang</th>
                                    <th class="text-center" >Jumlah</th>
                                    <th class="text-center" >Harga</th>
                                    <th class="text-center" >Diskon</th>
                                    <th class="text-center" >Diskon Nominal/QTY</th>
                                    <th class="text-center" >Potongan Admin</th>
                                    <th class="text-center" >Cashback Ongkir</th>
                                </tr>
                            </thead>
                            <tbody id="table-so-rinci">
                                
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th class="text-right" colspan="3" >Total Harga</th>
                                    <th class="text-right" >0</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('admin/si') }}" class="btn btn-outline-danger">Kembali</a>
                        <button type="submit" id="btnSimpan" class="btn btn-outline-primary">Buat Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('myjs')
<script>
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
     //berkas
     var flag = 2;
    $('.btn-tambah-berkas').on('click', function() {
        var count = $("input:file").length;
        var berkas = count + 1;
        if(count >= 5){
            alert('Maaf! Berkas tidak boleh lebih dari 5');
        }else{
            $( ".tambah-berkas" ).append(
                '<div class="flag_'+flag+'">' +
                    '<div class="row">'+
                        '<div class="col-md-9 col-12" style="padding-left: 4%">'+
                            '<label>Berkas</label>'+
                            '<input type="file" name="berkas[]" id="berkas_'+flag+'" class="form-control" style="width:98%">'+
                        '</div>'+
                        '<div class="col-md-3 col-12" style="padding-top: 4%">'+
                            '<button type="button" class="btn btn-danger btn-sm btn-hapus-berkas" data-id="flag_'+flag+'"><i class="fa fa-trash"></i> Hapus</button>'+
                        '</div>'+
                    '</div>'+
                '</div>'   
            );
            flag++;
        }

    });
    $('.tambah-berkas').on('click','.btn-hapus-berkas',function() {
        // $(this).parent().remove();
        $('.'+$(this).data("id")).remove();
    });
    //sampai sini
    $('#id_pelanggan').change(function(){
        let id_pelanggan = $('#id_pelanggan').val();
        $.ajax({
            method: "GET",
            url: "/admin/si/get_so/" + id_pelanggan,
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                let i = 0
                let html = ''
                html += "<option value='0'>-- --Pilih Nomor SO-- --</option>"
                for(i=0; i<data.data.length; i++) {
                    html += "<option value="+ data.data[i].id +">" + data.data[i].so_nomer + "</option>"
                }
                $('#id_so').html(html)
            },
            error: function(e) {
                console.log(e)
            }
        })
    });

    $('#id_so').on('change', function(el) {
            $.ajax({
                method: 'GET',
                url: '/admin/si/get_so_rinci/' + $('#id_so').val(),
                dataType: 'JSON',
                success: function(data){
                    console.log(data)
                    let html = ''
                    data.map((item, index) => {
                        console.log(item)
                        html += `
                        <tr>
                            <td> ${item.barang.nama_barang}<input type="hidden" class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="hidden" class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                            <td><input type="text" readonly class="form-control" name="jumlah[${item.id}]" value="${item.qty_barang}"></td>
                            <td> <input type="text" readonly text class="form-control harga" name="harga[${item.id}]" value="${item.harga_barang}"></td>
                            <td> <input type="text" readonly text class="form-control" name="diskon[${item.id}]" value="${item.diskon_barang}"></td>
                            <td> <input type="text" readonly text class="form-control harga" name="diskon_nominal[${item.id}]" value="${item.diskon_nominal}"></td>
                            <td> <input type="text" readonly text class="form-control harga" name="potongan_admin[${item.id}]" value="${item.potongan_admin}"></td>
                            <td> <input type="text" readonly text class="form-control harga" name="cashback_ongkir[${item.id}]" value="${item.cashback_ongkir}"></td>
                        </tr>
                        `
                    })
                    $('#table-so-rinci').html(html)
                    replaceRP()
                }
            })
    })

    function replaceRP(){
        let classHarga = document.querySelectorAll('.harga')
        classHarga.forEach((item, index) => {
            let newHarga = item.value
            item.value = 'Rp. ' + formatRupiah(newHarga.toString())
        })
    }

    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split           = number_string.split(','),
        sisa             = split[0].length % 3,
        rupiah             = split[0].substr(0, sisa),
        ribuan             = split[0].substr(sisa).match(/\d{3}/gi);
    
        // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
    
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection