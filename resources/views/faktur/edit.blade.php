@extends('layouts.vuexy')

@section('header')
    Edit faktur pembelian
@endsection

@section('content')
    @if($errors->all())
        @include('layouts.validation')
    @endif

    @if ($fakturpembelian->approve_direktur == 1 || $fakturpembelian->approve_komisaris == 1)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        Tidak bisa merubah data, karna pengajuan telah di setujui
                        <a href="{{ url('admin/fakturpembelian/' .$fakturpembelian->id ) }}">kembali</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <a href="{{ url('admin/fakturpembelian/' . $fakturpembelian->id) }}">
            <i class="fa fa-arrow-left"></i> Kembali ke daftar
        </a>
        <hr>
        <form action="{{ url('admin/fakturpembelian/' . $fakturpembelian->id) }}" method="post" enctype="multipart/form-data" >
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label >Dibayar dari <span class="text-danger">*</span></label>
                                <select class="select2 form-control form-control-lg" name="kredit_coa_id" required>
                                    <option value="">-- PILIH BANK --</option>
                                    @foreach($coaKredit as $row)
                                        <option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nomer_fakturpembelian">Nomer Faktur Pembelian</label>
                                <input type="text" readonly name="nomer_fakturpembelian" id="nomer_fakturpembelian" class="form-control" value="{{ $fakturpembelian->nomer_fakturpembelian }}" >
                                @csrf
                                @method('patch')
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal Faktur</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $fakturpembelian->tanggal }}">
                            </div>
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <input type="text" name="supplier_id" id="supplier_id" class="form-control" value="{{ $fakturpembelian->supplier->nama_supplier }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control" readonly >{{ $fakturpembelian->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="termin">Jatuh Tempo</label>
                                <select name="termin" id="termin" class="form-control">
                                    <option value="14" {{ $fakturpembelian->termin == 14 ? 'selected' : '' }} >14 Hari</option>
                                    <option value="30" {{ $fakturpembelian->termin == 30 ? 'selected' : '' }} >30 Hari</option>
                                    <option value="60" {{ $fakturpembelian->termin == 60 ? 'selected' : '' }} >60 Hari</option>
                                    <option value="90" {{ $fakturpembelian->termin == 90 ? 'selected' : '' }} >90 Hari</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($fakturpembelian->rinci as $rinci)
                                    <tr>
                                        <td>{{ $rinci->barang->nama_barang }}</td>
                                        <td> <input type="number" name="qty" readonly id="qty-{{ $rinci->id }}" class="form-control-sm form-control" value="{{ $rinci->qty }}"> </td>
                                        <td>
                                            <input type="hidden" name="rinci_id[]" value="{{ $rinci->id }}" > 
                                            <input type="text" name="harga[]" data-id="{{ $rinci->id }}" value="{{ $rinci->harga }}" class="form-control form-control-sm input-harga">
                                        </td>
                                        <td> <input type="text" readonly name="dsc" id="dsc-{{ $rinci->id }}" value="{{ $rinci->dsc }}%" class="form-control form-control-sm"> </td>
                                        <td id="total-{{$rinci->id}}" >Rp.{{  number_format($subtotal = $rinci->qty * ($rinci->harga - $rinci->harga * $rinci->dsc / 100), 0, ',', '.') }}</td>
                                        @php $total += $subtotal @endphp
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                                        <td id="total" >Rp.{{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($fakturpembelian->relation->first()->po->is_tax == 1)
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>PPN</strong></td>
                                            <td id="ppn" >Rp.{{ number_format($total * 10/100, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                            <td id="grand-total" >Rp.{{ number_format($total + ($total * 10/100), 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <button class="btn btn-outline-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection
@section('myjs')
<script type="text/javascript">

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

    let is_tax = '<?php echo $fakturpembelian->relation->first()->po->is_tax?>'
    let inputHarga = document.querySelectorAll('.input-harga');

    inputHarga.forEach((item, index) => {
        // ambil value awal
        let newHarga = item.value
        // ambil atribut
        let attr = item.getAttribute('data-id')

        // ubah ke rupiah
        item.value = 'Rp. ' + formatRupiah(newHarga.toString())

        // kalkulasi saat mengubah harga
        item.addEventListener('keyup', function(e){
            // hilangkan rupiah
            let rmRP = removeRp(item.value)

            // discount 
            let dsc = document.querySelector('#dsc-' + attr)
            dsc = dsc.value.split('%')
            dsc = parseInt(dsc[0])

            // qty
            let qty = document.querySelector('#qty-' + attr)
            qty = qty.value

            // kalkulasi total
            let total = (rmRP - ((rmRP * dsc) / 100)) * qty

            item.value = 'Rp. ' + formatRupiah(rmRP.toString())
            document.querySelector('#total-' + attr).innerHTML = 'Rp. ' + formatRupiah(total.toString())
            // console.log(dsc)
            calculationGrandTotal()
        })
    })

    function calculationGrandTotal(){
        let grandTotal = 0;
        let ppn = 0;
        inputHarga.forEach((item, index) => {
            let valueHarga = item.value
            let attr = item.getAttribute('data-id')
            let rmValueHarga = removeRp(valueHarga)

            // discount 
            let dsc = document.querySelector('#dsc-' + attr)
            dsc = dsc.value.split('%')
            dsc = parseInt(dsc[0])

            // qty
            let qty = document.querySelector('#qty-' + attr)
            qty = qty.value

            // kalkulasi total
            let total = (rmValueHarga - ((rmValueHarga * dsc) / 100)) * qty
            grandTotal += total
        })

        if(is_tax == 1){
            grandTotal = grandTotal + (grandTotal * 10 / 100)
            ppn = grandTotal * 10/100
            document.querySelector('#grand-total').innerHTML = formatRupiah(grandTotal.toString())
            document.querySelector('#ppn').innerHTML = formatRupiah(ppn.toString())
        }
        document.querySelector('#total').innerHTML = formatRupiah(grandTotal.toString())
    }

    function removeRp(input){
        var removeRp = input.replace('Rp. ', '');
        var val = removeRp.replaceAll('.', '');

        return val;
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