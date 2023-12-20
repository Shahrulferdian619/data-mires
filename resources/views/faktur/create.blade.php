@extends('layouts.vuexy')

@section('content')
@if($errors->all())
@include('layouts.validation')
@endif
<form action="/admin/fakturpembelian" id="form-save" method="POST" enctype="multipart/form-data">
    @csrf
    @if (session()->has('fakturpembelian'))
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- <label>Dibayar dari <span class="text-danger">*</span></label>
                    <select class="form-control" name="kredit_coa_id" required>
                        <option value="">-- PILIH BANK --</option>
                        @foreach($coaKredit as $row)
                        <option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>
                        @endforeach
                    </select> -->
                    <label>Nomer Faktur :</label>
                    <input type="text" class="form-control" name="nomer_faktur" value="{{ old('nomer_faktur') }}">

                    <label>Tanggal : </label>
                    <input type="date" class="form-control" name="tanggal_faktur" value="{{ date('Y-m-d') }}">

                    <label>Keterangan : </label>
                    <textarea class="form-control" rows="5" name="keterangan"></textarea>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Pilih supplier : </label>
                    <select name="supplier_id" class="form-control">
                        <option value="" disabled>--- --- ---</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ session()->get('fakturpembelian')['supplier_id'] == $supplier->id ? 'selected disabled' : 'disabled' }}>{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                    <div>
                        <label for="" class="form-label">Buat tagihan berdasarkan PO/RI</label>
                        <select name="based_on" class="form-control">
                            <option value="" disabled>--- --- ---</option>
                            <option value="1" {{ session()->get('fakturpembelian')['based_on'] == 1 ? 'selected disabled' : 'disabled' }}>Purchase Order</option>
                            <option value="2" {{ session()->get('fakturpembelian')['based_on'] == 2 ? 'selected disabled' : 'disabled' }}>Recieve Item</option>
                        </select>
                    </div>
                    <div class="">
                        <label for="" class="form-label">Jatuh Tempo</label>
                        <select name="termin" id="" class="form-control">
                            <option value="14">14 Hari</option>
                            <option value="30">30 Hari</option>
                            <option value="60">60 Hari</option>
                            <option value="90">90 Hari</option>
                        </select>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td> Nomer Rincian </td>
                                    <td> Rincian </td>
                                    <td> Tanggal </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (session()->get('fakturpembelian')['data'] as $index => $item)
                                
                                <tr>
                                    <td>
                                        {{ $item['nomer_rincian'] }}
                                        <a href="{{ url('/' ) . '/admin/fakturpembelian/hapus-pilihan/' . session()->get('fakturpembelian')['based_on'] . '/' . $index }}" class="btn-sm btn btn-danger d-block">Hapus Pilihan</a>
                                        <input type="hidden" name="rincian_id[]" value="{{$item['rincian_id']}}">
                                    </td>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td>Item</td>
                                                        <td>Jumlah Barang</td>
                                                        <td>Harga Barang</td>
                                                        <td>Diskon</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $total = 0;
                                                    @endphp
                                                    @foreach ($item['rincian'] as $key => $value)
                                                    @php
                                                    $total += $value['harga'] * $value['qty'];
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $value['barang_nama'] }} @if($value['description'] != null) <small>({{ $value['description'] }})</small> @endif
                                                            <input type="hidden" name="barang_id[{{$item['rincian_id']}}][]" value="{{ $value['barang_id'] }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" readonly data-ppn={{ $item['ppn'] }} {{ $value['qty'] == 0 || session()->get('fakturpembelian')['based_on'] == 2 ? 'readonly' : '' }} name="qty[{{$item['rincian_id']}}][]" id="qty_{{$value['barang_id']}}" value="{{ $value['qty'] }}" class="form-control qty-{{$item['rincian_id']}}">
                                                        </td>
                                                        <td>
                                                            <input type="text" readonly name="harga[{{$item['rincian_id']}}][]" id="barang_{{$value['barang_id']}}" value="{{ $value['harga'] }}" class="form-control harga-{{$item['rincian_id']}}">
                                                        </td>
                                                        <td>
                                                            
                                                            <input type="number" readonly data-ppn={{ $item['ppn'] }} {{ $value['qty'] == 0 || session()->get('fakturpembelian')['based_on'] == 2 ? 'readonly' : '' }} name="dsc[{{$item['rincian_id']}}][]" id="dsc_{{$value['dsc']}}" value="{{ $value['dsc'] }}" class="form-control dsc-{{$item['rincian_id']}}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>


                                                        <td colspan="2" class="text-right">
                                                            
                                                            <span>
                                                                <sup>Total DPP</sup>
                                                                <span id="gtx-{{$item['rincian_id']}}" data-rincian-id="{{$item['rincian_id']}}" data-ppn={{ $item['ppn'] }} class="grand-total-text">{{ number_format($total) }}</span>
                                                            </span> <br>
                                                            @if($item['pph'] != 0)
                                                            <span>
                                                                <sup>PPh (-)</sup>
                                                                {{ number_format($total * $item['pph'] / 100) }}
                                                                @php $total = $total - ($total * $item['pph'] / 100); @endphp
                                                            </span> <br>

                                                            @endif
                                                            @if($item['pajak_lain'] != 0)
                                                            <span>
                                                                <sup>Pajak Lain (+)</sup>
                                                                {{ number_format($total * $item['pajak_lain'] / 100) }}
                                                                @php $total = $total + ($total * $item['pajak_lain'] / 100); @endphp
                                                            </span> <br>

                                                            @endif
                                                            @php

                                                            if($item['ppn'] == 1){
                                                            $ppn = round($total * 11 / 100,2);
                                                            $total = $total + $ppn;
                                                            }
                                                            @endphp
                                                            <span>
                                                                <sup>PPN</sup>
                                                                {{ $item['ppn'] == 0 ? '0' : number_format($ppn) }}
                                                            </span>
                                                            <br>
                                                            <span>
                                                                <sup>Grand Total</sup>
                                                                {{ number_format($total, 2) }}
                                                            </span>
                                                        </td>
                                                        <td colspan="2">
                                                            @if (session()->get('fakturpembelian')['based_on'] != 2)
                                                                <label>Faktur Sebelumnya : Rp.{{ number_format($item['pembayaran_terakhir']) }} </label>
                                                            
                                                            @endif
                                                            @if(session()->get('fakturpembelian')['based_on'] == 2)
                                                            <input hidden type="text" name="ri_id[]" value="{{ $item['ri_id'] }}">
                                                            @endif
                                                            <input type="text" hidden name="price_total[]" id="price-total-{{$item['rincian_id']}}" value="{{ $total }}" {{ session()->get('fakturpembelian')['based_on'] == 2 ? 'readonly' : '' }} class="form-control grand-total">
                                                            <input type="text" readonly class="form-control" value="{{ 'Rp.'.number_format($total, 2) }}">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td> {{ $item['tanggal_rincian'] }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-1">
                        <a href="{{ url('/') . '/admin/fakturpembelian/hapus-semua-pilihan' }}" class="btn btn-outline-danger">Hapus Semua Pilihan</a>
                        <button type="submit" name="buat_faktur" id="btnSimpan" class="btn btn-outline-primary">Buat Faktur</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let grandTotal = document.querySelector('#grandTotal')
        let grdtClass = document.querySelectorAll('.grand-total-text')

        resultQtyToHarga()

        function resultQtyToHarga() {
            grdtClass.forEach((item, index) => {
                let id = item.getAttribute('data-rincian-id')
                let qtyClass = document.querySelectorAll('.qty-' + id)
                let hargaClass = document.querySelectorAll('.harga-' + id)
                let dscClass = document.querySelectorAll('.dsc-' + id)

                // console.log(hargaClass);

                qtyToHarga(qtyClass, hargaClass, dscClass, item.getAttribute('data-ppn'), id)

                qtyClass.forEach((item, index) => {
                    item.addEventListener('keyup', function(e) {
                        if (item.value != '') {
                            let getDataQty = document.querySelectorAll('.qty-' + id)
                            let getDataHarga = document.querySelectorAll('.harga-' + id)
                            let getDataDsc = document.querySelectorAll('.dsc-' + id)
                            qtyToHarga(getDataQty, getDataHarga, getDataDsc, item.getAttribute('data-ppn'), id)
                        }
                    })
                })
                hargaClass.forEach((item, index) => {
                    item.addEventListener('keyup', function(e) {
                        if (item.value != '') {
                            let getDataQty = document.querySelectorAll('.qty-' + id)
                            let getDataHarga = document.querySelectorAll('.harga-' + id)
                            let getDataDsc = document.querySelectorAll('.dsc-' + id)
                            qtyToHarga(getDataQty, getDataHarga, getDataDsc, item.getAttribute('data-ppn'), id)
                        }
                    })
                })
                dscClass.forEach((item, index) => {
                    item.addEventListener('keyup', function(e) {
                        if (item.value != '') {
                            if (item.value > 100) {
                                item.value = 100
                            }
                            let getDataQty = document.querySelectorAll('.qty-' + id)
                            let getDataHarga = document.querySelectorAll('.harga-' + id)
                            let getDataDsc = document.querySelectorAll('.dsc-' + id)
                            console.log(item.getAttribute('data-ppn'));
                            qtyToHarga(getDataQty, getDataHarga, getDataDsc, item.getAttribute('data-ppn'), id)
                        }
                    })
                })
            })
        }

        // qtyToHarga(qtyClass, hargaClass, dscClass)
        // changeQtyToHarga()

        function qtyToHarga(dataQtyClass, dataHargaClass, dataDscClass, ppn, rincian_id) {
            // console.log(dataHargaClass)
            let total = 0
            dataQtyClass.forEach((item, index) => {
                let harga = replaceRupiah(dataHargaClass[index])
                total += (harga - (harga * (dataDscClass[index].value / 100))) * item.value
            })
            if (ppn == 1) {
                total = total + (total * (10 / 100))
            }
            document.querySelector('#gtx-' + rincian_id).innerHTML = 'Rp. ' + formatRupiah2(total.toString())
            document.querySelector('#price-total-' + rincian_id).value = 'Rp. ' + formatRupiah2(total.toString())
        }

        function replaceRupiah(data) {
            let valueHarga = data.value

            if (valueHarga.search('Rp.') == 0) {
                valueHarga = removeRp(valueHarga)
                data.value = 'Rp. ' + formatRupiah2(data.value.toString())
            } else {
                data.value = 'Rp. ' + formatRupiah2(data.value.toString())
            }

            return valueHarga
        }


        function removeRp(input) {
            var removeRp = input.replace('Rp. ', '');
            var val = removeRp.replaceAll('.', '');

            return val;
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
    </script>


    @else
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Pilih supplier : </label>
                    <select class="form-control" id="supplier" name="supplier_id">
                        <option value="">--- --- ---</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                    <div id="based_on_area" class="d-none">
                        <label for="" class="form-label">Buat tagihan berdasarkan PO/RI</label>
                        <select name="based_on" id="based_on" name="based_on" class="form-control">
                            <option value="">--- --- ---</option>
                            <option value="1">Purchase Order</option>
                            <option value="2">Recieve Item</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="datatable-init table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <td> </td>
                                <td> Nomor </td>
                                <td> Tanggal </td>
                            </tr>
                        </thead>
                        <tbody id="table-ri"></tbody>
                    </table>
                    <button type="submit" name="input_session" class="btn btn-outline-primary mt-2">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let supplier = document.querySelector('#supplier')
        let basedON = document.querySelector('#based_on')
        let basedONArea = document.querySelector('#based_on_area')
        let url = '<?= url('/'); ?>'
        let bodyRI = document.querySelector('#table-ri')
        let supplierID = ''
        let basedONValue = ''


        supplier.onchange = function(e) {
            if (e.target.value != '') {
                if (basedONArea.classList.contains('d-none')) {
                    basedONArea.classList.remove('d-none')
                }
                supplierID = e.target.value
                if (basedONValue != '') {
                    let urlGetRincian = url + '/admin/fakturpembelian/get-data-rinci/' + supplierID + '/' + basedONValue
                    getRinci(urlGetRincian)
                } else {
                    bodyRI.innerHTML = ''
                }
            } else {
                basedONArea.classList.add('d-none')
                supplierID = ''
                bodyRI.innerHTML = ''
            }
        }

        basedON.onchange = function(e) {
            if (e.target.value != '') {
                basedONValue = e.target.value
                if (supplierID != '') {
                    let urlGetRincian = url + '/admin/fakturpembelian/get-data-rinci/' + supplierID + '/' + basedONValue
                    getRinci(urlGetRincian)
                } else {
                    bodyRI.innerHTML = ''
                }
            } else {
                basedONValue = ''
                bodyRI.innerHTML = ''
            }
        }

        function getRinci(link) {
            xhttp = new XMLHttpRequest()
            xhttp.onload = function() {
                let response = JSON.parse(this.responseText)
                let html = ''
                let li = ''
                console.log(response)
                if (response.based_on == 1) {
                    response.data.map((item, index) => {
                        html += `
                        <tr>
                            <td>
                                <input type="checkbox" name="rincian_id[]" id="rincian_${item.id}" value="${item.id}" />
                            </td>
                            <td>
                                ${item.nomer_po}
                            </td>
                            <td>${item.tanggal_po}</td>
                        </tr>
                    `
                    })
                } else if (response.based_on == 2) {
                    response.data.map((item, index) => {
                        html += `
                        <tr>
                            <td>
                                <input type="checkbox" name="rincian_id[]" id="rincian_${item.id}" value="${item.id}" />
                            </td>
                            <td>
                                ${item.nomer_ri}
                            </td>
                            <td>${item.tanggal_ri}</td>
                        </tr>
                    `
                    })
                }
                bodyRI.innerHTML = html
            }
            xhttp.open('GET', link)
            xhttp.send()
        }
    </script>
    @endif
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

    $(document).ready(function() {
        $('.datatable-init').DataTable()
    })
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