@extends('layouts.vuexy')

@section('header')
Create Deliver Order (Buat Kiriman Penjualan)
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

<form action="{{ url('admin/do/store') }}" id="form-save" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1">
                        <label for="so_penjualan_id" class="form-label">Pilih SO*</label>
                        <select class="select2 form-select form-control" name="so_penjualan_id" id="so_penjualan_id">
                            
                            <option value="">-- Pilih SO --</option>
                            @foreach($so as $item)
                            <option value="{{ $item->id }}">{{ $item->so_nomer }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="do_nomer" class="form-label">Nomor Pengiriman*</label>
                        <input type="text" name="do_nomer" id="do_nomer" class="form-control">
                    </div>
                    <div class="mb-1">
                        <label for="do_tanggal" class="form-label">Tanggal Pengiriman*</label>
                        <input type="date" name="do_tanggal" value="{{ date('Y-m-d') }}" id="do_tanggal" class="form-control flatpickr-basic flatpickr-input">
                    </div>
                    <div class="mb-1">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="4" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-header bg-primary text-light">Data Pengiriman</div>
                <div class="card-body">

                        <label for="do_alamat" class="form-label mt-2">Nama Penerima*</label>
                        <input type="text" name="pic_do" id="nama" class="form-control">
                        <input type="text" name="id_pelanggan" hidden id="idpelanggan" class="form-control">

                        <label for="do_alamat" class="form-label">Alamat Pengiriman*</label>
                        <input type="text" id="detil" name="do_alamat" class="form-control">

                        <label for="do_alamat" class="form-label">Detail Penerima</label>
                        <input type="text" readonly id="penerima" class="form-control">

                        <label for="do_alamat" class="form-label">Pengiriman</label>
                        <input type="text" readonly id="pengiriman" class="form-control">

                        <label for="do_alamat" class="form-label">Resi</label>
                        <input type="text" readonly id="resi" class="form-control">

                </div>
            </div>
        </div>
        <div class="col-12 mb-1">
            <div class="card">
                <div class="card-body">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Jumlah Order</th>
                                <th class="text-center">Sudah Dikirim</th>
                                <th class="text-center">Akan Dikirim</th>
                                <th> Diambil Dari </th>
                            </tr>
                        </thead>
                        <tbody id="so_rinci" class="text-center">
                        </tbody>
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
                    <a href="{{ url('admin/do') }}" class="btn btn-outline-danger">Kembali</a>
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
        }).then(function(result) {
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

    $('#so_penjualan_id').on('change', function(el) {
        if ($('#so_penjualan_id').val() != '') {
            $.ajax({
                method: 'GET',
                url: '/admin/do/get_so_rinci/' + $('#so_penjualan_id').val(),
                dataType: 'JSON',
                success: function(data) {
                    if(parseInt(data.count_do) > 0){
                        let count = parseInt(data.count_do) + 1;
                        $('#do_nomer').val('DO-' + data.so.so_nomer+'-'+count)
                    }else{
                        $('#do_nomer').val('DO-' + data.so.so_nomer)
                    }
                    $('#nama').val(data.pelanggan.nama_pelanggan)
                    $('#detil').val(data.pelanggan.detail_alamat)
                    $('#idpelanggan').val(data.pelanggan.id)
                    if(data.so.penerima != undefined){
                        $('#penerima').val(data.so.penerima)
                    }
                    if(data.so.ekspedisi != undefined){
                        $('#pengiriman').val(data.so.ekspedisi)
                    }
                    if(data.so.resi != null){
                        $('#resi').val(data.so.resi)
                    }
                    let html = ''
                    data.data.map((item, index) => {
                        let perlu_kirim = item.qty_barang - item.jumlah_kirim;
                        html += `
                    <tr class="rincian">
                        <td> ${item.barang.nama_barang}<input type="hidden" class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="hidden" class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                        <td><span class="badge badge-warning" >${item.qty_barang}</span></td>
                        <td> <span class="badge badge-success" >${item.jumlah_kirim}</span></td>
                        <td> <input type="number" onKeyup="restrict(this)" id="${item.id}" data-max="${perlu_kirim}" class="form-control" name="kirim[${item.id}]" value="${perlu_kirim}"></td>
                        <td> 
                        <select type="text" text class="form-control" name="gudang[${item.id}]">
                        <?php foreach ($gudang as $val) {
                            echo '<option value="' . $val->id . '">' . $val->nama_gudang . '</option>';
                        } ?>
                        </select></td>
                    </tr>
                    `
                    })
                    $('#so_rinci').html(html)
                }
            })
        }else{
            $('.rincian').remove();
        }
    })

    function restrict(e) {
        let max = parseInt($(e).attr('data-max'));
        if ($(e).val() > max) {
            $('#' + $(e).attr('id')).val(max);
        }
        console.log('hai');
    }
</script>
@endsection