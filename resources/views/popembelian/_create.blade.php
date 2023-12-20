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
<form action="{{ url('admin/po/store') }}" method="post" enctype="multipart/form-data">
@csrf
<div class="row">
        <div class="col-md-6">
                <div class="card">
                <div class="card-body">
                    <label>Nomer Pesanan PO : </label>
                    <input type="text" class="form-control" name="nomer_po" value="{{ old('nomer_po') }}">
                    
                    <label>Tanggal Pesanan PO : </label>
                    <input type="date" class="form-control" name="tanggal_po" value="{{ date('Y-m-d') }}">
                    
                    <label>Tujuan Pengriman PO : </label>
                    <input type="text" class="form-control" name="tujuan_pengiriman" value="{{ old('tujuan_pengiriman') }}">

                    <label>Keterangan : </label>
                    <textarea class="form-control" rows="5" name="keterangan" >{{ old('keterangan') }}</textarea>

                </div>
            </div>
        </div>    
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <label>Pilih supplier : </label>
                    <select class="form-control" id="supplier" name="supplier_id">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }} | {{ $supplier->kode_supplier }} </option>
                        @endforeach
                    </select>
                    <div>
                        <label>Pilih nomer permintaan pembelian : </label>
                        <div>
                            <select id="pmtpembelian_id" class="form-control" name="pmtpembelian_id">
                                <option value="0">-- Pilih Permintaan Pembelian --</option>
                                @foreach($pmt as $pmtpembelian)
                                    <option value="{{ $pmtpembelian->id }}">{{ $pmtpembelian->nomer_pmtpembelian }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="">PPN 10%</label>
                            <select onchange="findTotal()" name="is_tax" id="ppn" class="form-control">
                                <option value="0">TIDAK</option>
                                <option value="1">YA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Berkas 1</label>
                            <input type="file" name="berkas_1" id="berkas_1" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 2</label>
                            <input type="file" name="berkas_2" id="berkas_2" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 3</label>
                            <input type="file" name="berkas_3" id="berkas_3" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 4</label>
                            <input type="file" name="berkas_4" id="berkas_4" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 5</label>
                            <input type="file" name="berkas_5" id="berkas_5" class="form-control">
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
                            <td> Harga Barang </td>
                            <td> Jumlah Barang </td>
                            <td> Discount </td>
                        </tr>
                    </thead>
                    <tbody id="table-po" >
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
                            <td colspan="3" class="text-right"><strong>PPN</strong></td>
                            <td><span id="tot_ppn">Rp.0</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
                            <td><span id="grand_total">Rp.0</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="submit" class="btn btn-primary mt-2" >Simpan</button>
        </div>
    </div>
</form>
<br><br>

@endsection

@section('myjs')
<script type="text/javascript">
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
                success: function(data){
                    let dsc = ''
                    for(let i = 0; i <= 100; i++){
                        dsc += `<option value="${i}">${i}%</option>`
                    }
                    let html = ''
                    data.map((item, index) => {
                        html += `
                        <tr>
                            <td> ${item.barang.nama_barang}<input type="text" hidden class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="text" hidden class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                            <td> <input type="text" onkeyup="findTotal()" class="form-control harga" id="${item.id}" name="harga[${item.id}]" value="${item.harga}"></td>
                            <td> <input type="text" onkeyup="findTotal()" class="form-control" id="jumlah_${item.id}" name="jumlah[${item.id}]" value="${item.qty}"></td>
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
            // alert($('#pmtpembelian_id').val())
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

    function findTotal(){
        let arr = document.getElementsByClassName('harga');
        let tot = 0;
        let ppn = document.getElementById('ppn').value;
        let tot_ppn = 0;
        for(let i=0;i<arr.length;i++){
            let id = arr[i].id;
            let qty = document.getElementById('jumlah_'+id).value;
            let dsc = document.getElementById('dsc_'+id).value;
            let harga = arr[i].value;

            let harga_dsc = harga - (harga * (dsc / 100));
            let jumlah_perbarang = harga_dsc * qty;
            tot += jumlah_perbarang;
        }
        let sub_tot = tot;
        if(ppn == 1){
            tot_ppn = tot * 10/100;
            tot = tot + (tot * 10/100)
        }
        document.getElementById('tot_ppn').innerText = 'Rp.'+formatRupiah(tot_ppn.toString());
        document.getElementById('total').innerText = 'Rp.'+formatRupiah(sub_tot.toString());
        document.getElementById('grand_total').innerText = 'Rp.'+formatRupiah(tot.toString());
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