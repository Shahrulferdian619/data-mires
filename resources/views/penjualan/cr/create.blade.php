@extends('layouts.vuexy')

@section('header')
Create Customer Receipt (Pembayaran Pelanggan)
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@endif
<form action="{{ url('admin/cr') }}" id="form-save" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1">
                        <label>Diterima ke <span class="text-danger">*</span></label>
                        <select class="select2 form-control form-control-lg" name="debit_coa_id" required>
                            <option value="">-- PILIH BANK --</option>
                            @foreach($coaDebit as $row)
                            <option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="cr_nomer" class="form-label">Nomor Pembayaran</label>
                        <input type="text" name="cr_nomer" id="cr_nomer" class="form-control">
                    </div>
                    <div class="mb-1">
                        <label for="cr_tanggal" class="form-label">Tanggal Pembayaran</label>
                        <input type="date" name="cr_tanggal" id="cr_tanggal" value="{{ date('Y-m-d') }}" class="form-control flatpickr-basic flatpickr-input">
                    </div>
                    <div class="mb-1">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-1">
            <div class="card">
                <div class="card-body">
                    <div class="mb-1">
                        <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                        <select onchange="getInvoice(this.value)" class="select2 form-select form-control" name="id_pelanggan" id="id_pelanggan">
                            <option value="0">-- Pilih Customer --</option>
                            @foreach ($customer as $item)
                            <option value="{{$item->id}}">{{ $item->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-1">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No. Invoice</th>
                                <th class="text-center">Jumlah Tagihan</th>
                                <th class="text-center">Pembayaran Sebelumnya</th>
                                <th class="text-center">Sisa Pembayaran</th>
                                <th class="text-center">Note</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Total Pembayaran:</td>
                                <td colspan="3"><input class="form-control" readonly type="text" id="total-payment" required name="payment"></td>
                            </tr>
                        </tfoot>
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
                    <a href="{{ url('admin/cr') }}" class="btn btn-outline-danger">Kembali</a>
                    <button class="btn btn-outline-primary" id="btnSimpan">Buat Data</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    let url = '<?= url('/') ?>'
    let pelanggan = document.querySelector('#id_pelanggan')
    let invoice = document.querySelector('#si_penjualan_id')
    let tableBody = document.querySelector('#table-body')

    function getInvoice(idPelanggan) {
        let newURL = url + '/admin/cr/get-invoice/' + idPelanggan
        getData(newURL, function(result) {
            let html = ''
            result.map((item) => {
                html += `
                        <tr class="flag_${item.nomer_invoice}">
                            <td>${item.nomer_invoice}<input type="hidden" value="${item.id}" name="invoice_id[]"> </td>
                            <td>Rp. ${formatRupiah(item.jumlah_tagihan.toString())}</td>
                            <td>Rp. ${formatRupiah(item.bayar_sebelumnya.toString())}</td>
                            <td><input type="text" class="form-control value-invoice" required name="bayar[]" onKeyup="rupiahJs(this)" id="bayar_${item.nomer_invoice}" value="${formatRupiah(item.sisa.toString())}"></td>
                            <td><input type="text" class="form-control" name="note[]" id="note_${item.nomer_invoice}" ></td>
                            <td><div class="btn btn-sm btn-danger" onclick="btnHapusItem('flag_${item.nomer_invoice}')"><i class="fa fa-trash"></div></td>
                        </tr>
                    `
            })

            tableBody.innerHTML = html

            count_total();
        })

    }

    function count_total(){
        let arr = document.getElementsByClassName('value-invoice');
        let total_payment = document.getElementById('total-payment');
        let total = 0;
        for(let i = 0; i < arr.length; i++) {
            total += parseInt(removeRp(arr[i].value));
        }
        total_payment.value = 'Rp. '+formatRupiah(total.toString());
    }
    function removeRp(input) {
        var val = input.replaceAll('.', '');
        return val;
    }

    function btnHapusItem(flag) {
        $('.'+flag).remove()

        count_total();
    }

    function getData(newURL, callback) {
        let xhttp = new XMLHttpRequest()
        xhttp.onload = function() {
            let data = JSON.parse(this.responseText)
            callback(data)
        }
        xhttp.open('GET', newURL)
        xhttp.send()
    }

    
</script>
@endsection

@section('myjs')
<script>
    function rupiahJs(e) {
        // console.log($('#'+$(e).attr('id')).val());
        $('#' + $(e).attr('id')).val(formatRupiah($(e).val()));


        count_total();
    }


    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');

    }
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
</script>
@endsection