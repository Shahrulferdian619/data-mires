@extends('layouts.vuexy')

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif
<form action="/admin/fakturpembelian" method="POST" enctype="multipart/form-data">
@csrf
@if (session()->has('fakturpembelian'))
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <label>Nomer Faktur :</label>
                <input type="text" class="form-control" name="nomer_faktur" value="{{ old('nomer_faktur') }}">
                
                <label>Tanggal : </label>
                <input type="date" class="form-control" name="tanggal_faktur" value="{{ date('Y-m-d') }}">
                
                <label>Keterangan : </label>
                <textarea class="form-control" rows="5" name="keterangan" ></textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <label>Pilih supplier : </label>
                <select name="supplier_id" class="form-control">
                    <option value="" disabled >--- --- ---</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ session()->get('fakturpembelian')['supplier_id'] == $supplier->id ? 'selected disabled' : 'disabled' }} >{{ $supplier->nama_supplier }}</option>
                    @endforeach
                </select>
                <div>
                    <label for="" class="form-label">Buat tagihan berdasarkan PO/RI</label>
                    <select name="based_on" class="form-control" >
                        <option value="" disabled>--- --- ---</option>
                        <option value="1" {{ session()->get('fakturpembelian')['based_on'] == 1 ? 'selected disabled' : 'disabled' }} >Purchase Order</option>
                        <option value="2" {{ session()->get('fakturpembelian')['based_on'] == 2 ? 'selected disabled' : 'disabled' }} >Recieve Item</option>
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
                                                        <td>Nama Barang</td>
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
                                                            {{ $value['barang_nama'] }} 
                                                            <input type="hidden" name="barang_id[{{$item['rincian_id']}}][]" value="{{ $value['barang_id'] }}"  >
                                                        </td>
                                                        <td> <input type="number" data-ppn={{ $item['ppn'] }} {{ $value['qty'] == 0 || session()->get('fakturpembelian')['based_on'] == 2 ? 'readonly' : '' }} name="qty[{{$item['rincian_id']}}][]" id="qty_{{$value['barang_id']}}" value="{{ $value['qty'] }}" class="form-control qty-{{$item['rincian_id']}}" > </td>
                                                        <td> <input type="number" readonly name="harga[{{$item['rincian_id']}}][]" id="barang_{{$value['barang_id']}}" value="{{ $value['harga'] }}" class="form-control harga-{{$item['rincian_id']}}" > </td>
                                                        <td> <input type="number" data-ppn={{ $item['ppn'] }} {{ $value['qty'] == 0 || session()->get('fakturpembelian')['based_on'] == 2 ? 'readonly' : '' }} name="dsc[{{$item['rincian_id']}}][]" id="dsc_{{$value['dsc']}}" value="{{ $value['dsc'] }}" class="form-control dsc-{{$item['rincian_id']}}" > </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2" class="text-right" >
                                                            <span> 
                                                                <sup>PPN</sup> 
                                                                {{ $item['ppn'] == 0 ? '0%' : '10%' }}
                                                            </span>
                                                            <br>
                                                            @php
                                                                if($item['ppn'] == 1){
                                                                    $total = $total + ($total * (10 / 100));
                                                                }
                                                            @endphp
                                                            <span> 
                                                                <sup>Total</sup> 
                                                                <span id="gtx-{{$item['rincian_id']}}" data-rincian-id="{{$item['rincian_id']}}" data-ppn={{ $item['ppn'] }} class="grand-total-text" >{{ $total }}</span>
                                                            </span>
                                                            <br>
                                                            @if (session()->get('fakturpembelian')['based_on'] != 2)
                                                            <span> 
                                                                <sup>Total Pembayaran Sebelumnya</sup> 
                                                                {{ $item['pembayaran_terakhir'] }}
                                                            </span>
                                                            @endif
                                                        </td>
                                                        <td colspan="2" > 
                                                            <input type="number"
                                                            readonly
                                                            name="price_total[]" 
                                                            id="price-total-{{$item['rincian_id']}}" 
                                                            value="{{ session()->get('fakturpembelian')['based_on'] == 2 ? $total : $total - $item['pembayaran_terakhir'] }}" 
                                                            {{ session()->get('fakturpembelian')['based_on'] == 2 ? 'readonly' : '' }} class="form-control grand-total"  >    
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
                <div class="mt-1" >
                    <a href="{{ url('/') . '/admin/fakturpembelian/hapus-semua-pilihan' }}" class="btn btn-danger">Hapus Semua Pilihan</a>
                    <button type="submit" name="buat_faktur" class="btn btn-primary">Buat Faktur</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let grandTotal = document.querySelector('#grandTotal')
    let grdtClass = document.querySelectorAll('.grand-total-text')

    resultQtyToHarga()

    function resultQtyToHarga(){
        grdtClass.forEach((item, index) => {
            let id = item.getAttribute('data-rincian-id')
            let qtyClass = document.querySelectorAll('.qty-' + id)
            let hargaClass = document.querySelectorAll('.harga-' + id)
            let dscClass = document.querySelectorAll('.dsc-' + id)

            qtyToHarga(qtyClass, hargaClass, dscClass, item.getAttribute('data-ppn'), id)
            qtyClass.forEach((item, index) => {
                item.addEventListener('keyup', function(e){
                    if(item.value != ''){
                        let getDataQty = document.querySelectorAll('.qty-' + id)
                        let getDataHarga = document.querySelectorAll('.harga-' + id)
                        let getDataDsc = document.querySelectorAll('.dsc-' + id)
                        qtyToHarga(getDataQty, getDataHarga, getDataDsc, item.getAttribute('data-ppn'), id)
                    }
                })
            })
            hargaClass.forEach((item, index) => {
                item.addEventListener('keyup', function(e){
                    if(item.value != ''){
                        let getDataQty = document.querySelectorAll('.qty-' + id)
                        let getDataHarga = document.querySelectorAll('.harga-' + id)
                        let getDataDsc = document.querySelectorAll('.dsc-' + id)
                        qtyToHarga(getDataQty, getDataHarga, getDataDsc, item.getAttribute('data-ppn'), id)
                    }
                })
            })
            dscClass.forEach((item, index) => {
                item.addEventListener('keyup', function(e){
                    if(item.value != ''){
                        if(item.value > 100){
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

    function qtyToHarga(dataQtyClass, dataHargaClass, dataDscClass, ppn, rincian_id){
        let total = 0
        dataQtyClass.forEach((item, index) => {
            total += (dataHargaClass[index].value - (dataHargaClass[index].value * (dataDscClass[index].value / 100))) * item.value
        })
        if(ppn == 1){
            total = total + (total * (10/100))
        }
        document.querySelector('#gtx-' + rincian_id).innerHTML = total
        document.querySelector('#price-total-' + rincian_id).value = total
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
                    <select name="based_on" id="based_on" name="based_on" class="form-control" >
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
                    <tbody id="table-ri" ></tbody>
                </table>
                <button type="submit" name="input_session" class="btn btn-primary mt-2" >Simpan</button>
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


    supplier.onchange = function(e){
        if(e.target.value != ''){
            if(basedONArea.classList.contains('d-none')){
                basedONArea.classList.remove('d-none')
            }
            supplierID = e.target.value
            if(basedONValue != ''){
                let urlGetRincian = url + '/admin/fakturpembelian/get-data-rinci/' + supplierID + '/' + basedONValue
                getRinci(urlGetRincian)
            }else{
                bodyRI.innerHTML = ''
            }
        }else{
            basedONArea.classList.add('d-none')
            supplierID = ''
            bodyRI.innerHTML = ''
        }
    }

    basedON.onchange = function(e){
        if(e.target.value != ''){
            basedONValue = e.target.value
            if(supplierID != ''){
                let urlGetRincian = url + '/admin/fakturpembelian/get-data-rinci/' + supplierID + '/' + basedONValue
                getRinci(urlGetRincian)
            }else{
                bodyRI.innerHTML = ''
            }
        }else{
            basedONValue = ''
            bodyRI.innerHTML = ''
        }
    }

    function getRinci(link){
        xhttp = new XMLHttpRequest()
        xhttp.onload = function(){
            let response = JSON.parse(this.responseText)
            let html = ''
            let li = ''
            // console.log(data)
            if(response.based_on == 1){
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
            }else if(response.based_on == 2){
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

    $(document).ready(function() {
        $('.datatable-init').DataTable()
    })

</script>
@endif
</form>

@endsection

@section('myjs')

@endsection