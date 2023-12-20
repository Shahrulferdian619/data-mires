@extends('layouts.vuexy')

@section('content')
@if($errors->all())
@include('layouts.validation')
@endif
@if (session()->has('success'))
@include('layouts.success')
@endif
<a href="/admin/ri">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>
<form action="/admin/ri/store" id="form-save" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Pilih nomer PO : </label>
                    <select id="popembelian_id" class="form-control" name="popembelian_id">
                        <option value="0">-- Pilih Nomor PO --</option>
                        @foreach($po as $item)
                        <option value="{{ $item->id }}">{{ $item->nomer_po }}</option>
                        @endforeach
                    </select>
                    <label>Nomer Surat Jalan : </label>
                    <input type="text" class="form-control" id="nomer_ri" name="nomer_ri">

                    <label>Tanggal penerimaan barang : </label>
                    <input type="date" class="form-control" name="tanggal_ri" value="{{ date('Y-m-d') }}">

                    <label>Keterangan : </label>
                    <textarea class="form-control" rows="5" name="keterangan">-</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Supplier : </label>
                    <input type="text" readonly class="form-control" id="supplier_name">
                    <input type="text" hidden class="form-control" id="supplier_id" name="supplier_id">
                    <label>Tanggal Pemesanan : </label>
                    <input type="text" readonly class="form-control" id="tanggal_po">
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
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <td>Nama Barang</td>
                        <td>Jumlah Pesanan</td>
                        <td>Jumlah Kedatangan</td>
                        <td>Gudang</td>
                        <td>Sudah Datang</td>
                    </tr>
                </thead>
                <tbody id="table-ri">
                </tbody>
            </table>
            <button type="submit" id="btnSimpan" class="btn btn-outline-primary mt-2">Simpan</button>
        </div>
    </div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">
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
    //sampai sini
    $(document).ready(function() {
        $('#supplier').on('change', function(el) {
            el.preventDefault()
            get_po_belum_diproses($('#supplier').val())
        })

        $('#popembelian_id').on('change', function(el) {
            $.ajax({
                method: 'GET',
                url: '/admin/po/rincian/' + $('#popembelian_id').val(),
                dataType: 'JSON',
                success: function(data) {
                    let html = ''
                    data.data.map((item, index) => {
                        let sisa_datang = item.jumlah - item.jumlah_datang;
                        html += `
                        <tr>
                            <td> ${item.barang.nama_barang}<input type="text" hidden class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="text" hidden class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                            <td><span class="badge badge-warning">${item.jumlah}</span></td>
                            <td> <input type="number" onKeyup="restrict(this)" id="${item.id}" data-max="${item.jumlah}" class="form-control" name="jumlah[${item.id}]" value="${sisa_datang}"> <input type="text" hidden class="form-control" name="harga[${item.id}]" value="${item.harga}"></td>
                            <td>
                                <select class="form-control" name="gudang[${item.id}]">
                                <?php foreach ($gudang as $val) {
                                    echo '<option value="' . $val->id . '">' . $val->nama_gudang . '</option>';
                                } ?>
                                </select>
                            </td>
                            <td>
                                <span class="badge badge-success">${item.jumlah_datang}</span>
                                <input type="text" hidden class="form-control" name="dsc[${item.id}]" value="${item.dsc}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="note[${item.id}]" placeholder="Note">
                            </td>
                        </tr>
                        `
                    })
                    $('#table-ri').html(html)
                    $('#supplier_name').val(data.supplier)
                    $('#supplier_id').val(data.supplier_id)
                    $('#tanggal_po').val(data.tanggal)
                }
            })
            if($('#popembelian_id').val() != 0){
                let no_po = $( "#popembelian_id option:selected" ).text();
                $('#nomer_ri').val(no_po)
            }else{
                $('#nomer_ri').val('')
                    $('#supplier_name').val('')
                    $('#tanggal_po').val('')
                    $('#supplier_id').val('')
            }
            
        })
    })

    function restrict(e) {
        let max = parseInt($(e).attr('data-max'));
        if ($(e).val() > max) {
            $('#' + $(e).attr('id')).val(max);
        }
    }

    function get_po_belum_diproses(id_supplier) {
        $.ajax({
            method: "GET",
            url: "/admin/po/belum-diproses/" + id_supplier,
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                let i = 0
                let html = ''
                html += "<option value=''>-- --Pilih Nomor PO-- --</option>"
                for (i = 0; i < data.data.length; i++) {
                    html += "<option value=" + data.data[i].id + ">" + data.data[i].nomer_po + "</option>"
                }
                $('#popembelian_id').html(html)
            },
            error: function(e) {
                console.log(e)
            }
        })
    }
</script>
@endsection