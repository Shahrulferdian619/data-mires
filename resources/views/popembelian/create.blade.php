@extends('layouts.vuexy')

@section('header')
Pesanan Pembelian Baru
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@endif

<a href="/admin/po">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

@if (session()->has('success'))
@include('layouts.success')
@endif
@if (session()->has('error'))
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        {{ session('error') }}
    </div>
</div>
@endif
<form action="{{ url('admin/po/store') }}" id="form-save" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Pilih nomer permintaan pembelian : </label>
                    <select id="pmtpembelian_id" class="form-control selectNya" name="pmtpembelian_id">
                        <option value="0">-- Pilih Permintaan Pembelian --</option>
                        @foreach($pmt as $pmtpembelian)
                        <option value="{{ $pmtpembelian->id }}">{{ $pmtpembelian->nomer_pmtpembelian }}</option>
                        @endforeach
                    </select>
                    <label>Nomer Pesanan PO : </label>
                    <input type="text" class="form-control" name="nomer_po" id="" value="{{ old('nomer_po') }}">

                    <label>Tanggal Pesanan PO : </label>
                    <input type="date" class="form-control" name="tanggal_po" value="{{ date('Y-m-d') }}">

                    <label>Tujuan Pengriman PO : </label>
                    <input type="text" class="form-control" name="tujuan_pengiriman" readonly value="Remarks PT Mires">

                    <label>Keterangan Pembayaran DLL: </label>
                    <textarea class="form-control" rows="5" name="keterangan">{{ old('keterangan') }}</textarea>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Pilih supplier : </label>
                    <select class="form-control selectNya" id="supplier" name="supplier_id">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }} | {{ $supplier->kode_supplier }} </option>
                        @endforeach
                    </select>
                    <div>
                        <div>
                            <label for="">Pph (%)</label>
                            <input type="number" onkeyup="findTotal()" placeholder="Ex: 2,5" class="form-control" id="pph" name="pph">
                        </div>
                        <div>
                            <label for="">PPN 11%</label>
                            <select onchange="findTotal()" name="is_tax" id="ppn" class="form-control">
                                <option value="0">TIDAK</option>
                                <option value="1">YA</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Pajak Lain (%)</label>

                            <input type="number" onkeyup="findTotal()" placeholder="Ex: 10" class="form-control" id="pajak_lain" name="pajak_lain">
                        </div>
                        <div>
                            <label for="">Dengan Receive Item?</label>
                            <select name="ri" class="form-control">
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                    </div>
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
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <td> Nama Barang </td>
                            <td> Jumlah Barang </td>
                            <td> Harga Barang </td>
                            <td> Discount </td>
                        </tr>
                    </thead>
                    <tbody id="table-po">
                        <!-- @if (session()->has('pmtpembelian_rinci'))
                            @foreach (session()->get('pmtpembelian_rinci') as $item)
                                <tr>
                                    <td> <input type="text" class="form-control" name="nama_barang" value="{{ $item->barang->nama_barang }}">  </td>
                                    <td> {{ $item->harga }} </td>
                                    <td> {{ $item->jumlah }} </td>
                                </tr>
                            @endforeach
                        @endif -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total</strong></td>
                            <td><span id="total">Rp.0</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>PPh</strong></td>
                            <td><span id="tot_pph">Rp.0</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Pajak Lain</strong></td>
                            <td><span id="tot_pajak_lain">Rp.0</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>PPN 11%</strong></td>
                            <td><span id="tot_ppn">Rp.0</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
                            <td><span id="grand_total">Rp.0</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button class="btn btn-outline-secondary mt-2" id="btnSubmitAgain" data-target="lagi" type="submit">Simpan & Baru</button>
            <button type="submit" id="btnSimpan" class="btn btn-outline-primary mt-2">Simpan</button>
        </div>
    </div>
</form>
<br><br>

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
            } else if (data == 'LAGI') {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil dibuat',
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                }).then(function() {
                    window.location = "{{ route('admin.pmtpembelian.create') }}";
                });
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

    // RUPIAH
    function rupiahJs(e) {
        $('#' + $(e).attr('id')).val(formatRupiah($(e).val(), 'Rp. '));

        // findTotal();
    }
    // Ubah Remove rp
    function removeRp(input) {
        var removeRp = input.replace('Rp. ', '');
        var val = removeRp.replaceAll('.', '');

        return val;
    }
    // total
    function findTotal() {
        let arr = document.getElementsByClassName('harga');
        let tot = 0;
        let ppn = document.getElementById('ppn').value;
        let pph = document.getElementById('pph').value;
        let pajak_lain = document.getElementById('pajak_lain').value;
        let tot_ppn = 0;
        for (let i = 0; i < arr.length; i++) {
            let id = arr[i].id;
            let qty = document.getElementById('jumlah_' + id).value;
            let dsc = document.getElementById('dsc_' + id).value;
            let harga = arr[i].value;

            let harga_dsc = harga - (harga * (dsc / 100));
            let jumlah_perbarang = harga_dsc * qty;
            tot += jumlah_perbarang;
        }
        let sub_tot = tot;

        let tot_pph = tot * pph / 100;

        tot = tot - tot_pph;
        console.log(tot)

        let tot_pajak_lain = tot * pajak_lain / 100;

        tot = tot + tot_pajak_lain;

        console.log(tot)

        // let bruto = sub_tot *
        if (ppn == 1) {
            tot_ppn = (tot * 11 / 100).toFixed(2);
            tot = tot + parseFloat(tot_ppn);
        }
        document.getElementById('tot_ppn').innerText = 'Rp.' + formatRupiah2(tot_ppn);
        document.getElementById('tot_pph').innerText = 'Rp.' + formatRupiah2(tot_pph);
        document.getElementById('tot_pajak_lain').innerText = 'Rp.' + formatRupiah2(tot_pajak_lain);
        document.getElementById('total').innerText = 'Rp.' + formatRupiah2(sub_tot.toString());
        document.getElementById('grand_total').innerText = 'Rp.' + formatRupiah2(tot.toString());

        // console.log(tot)
        // console.log(parseFloat(tot_ppn))
    }

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
        // $('#supplier').on('change', function(el) {
        //     // el.preventDefault()
        //     get_permintaan_pembelian_belum_diproses($('#supplier').val())
        // })

        $('#pmtpembelian_id').on('change', function(el) {
            el.preventDefault()
            $.ajax({
                method: 'GET',
                url: '/admin/pmtpembelian/rincian/' + $('#pmtpembelian_id').val(),
                dataType: 'JSON',
                success: function(data) {
                    let dsc = ''
                    for (let i = 0; i <= 100; i++) {
                        dsc += `<option value="${i}">${i}%</option>`
                    }
                    let html = ''
                    data.map((item, index) => {
                        if (item.harga == 0) {
                            item.harga = '';
                        }
                        let desc = '';
                        let description = '';
                        if (item.description != null) {
                            desc = '(' + item.description + ')';
                            description = item.description;
                        }
                        html += `
                        <tr>
                            <td> ${item.barang.nama_barang} ${desc}<input type="text" hidden class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="text" hidden class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                            <td> <input type="text" onkeyup="findTotal()" class="form-control" id="jumlah_${item.id}" name="jumlah[${item.id}]" value="${item.qty}"></td>
                            <td> <input type="text" hidden name="desc[${item.id}]" value="${description}"> <input type="number" class="form-control harga" onkeyup="findTotal()" id="${item.id}" name="harga[${item.id}]" value="${item.harga}"></td>
                            <td> <select onchange="findTotal()" name="dsc[${item.id}]" id="dsc_${item.id}" class="form-control">${dsc}</select> </td>
                        </tr>
                        `

                        // html += '<tr>'
                        // html += "<td>" + item.barang.nama_barang + "</td>"
                        // html += "<td>" + item.harga + "</td>"
                        // html += "<td>" + item.jumlah + "</td>"
                        // html += "</tr>"
                    })
                    $('#table-po').html(html)
                }
            })
            if($('#pmtpembelian_id').val() != 0){
                let no_po = $( "#pmtpembelian_id option:selected" ).text();
                $('#nomer_po').val(no_po)
            }else{
                $('#nomer_po').val('')
            }
        })
    })

    // function get_permintaan_pembelian_belum_diproses(supplier_id)
    // {
    //     $.ajax({
    //         method: "GET",
    //         url: "/admin/pmtpembelian/belum-diproses/" + supplier_id,
    //         dataType: "JSON",
    //         success: function(data) {
    //             let i = 0
    //             let html = ''
    //             html += "<option value='--- ---- ---'>Pilih Nomor Permintaan Pembelian</option>"
    //             for(i=0; i<data.data.length; i++) {
    //                 html += "<option value="+ data.data[i].id +">" + data.data[i].nomer_pmtpembelian + "</option>"
    //             }
    //             $('#pmtpembelian_id').html(html)
    //         },
    //         error: function(e) {
    //             console.log(e)
    //         }
    //     })
    // }

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString()
        split = number_string.split('.'),
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

    function formatRupiah2(angka, prefix) {
        var number_string = angka.toString()
        split = number_string.split('.'),
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
</script>
@endsection