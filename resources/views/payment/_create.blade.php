@extends('layouts.vuexy')

@section('content')
@if($errors->all() || !empty(session('error')))
    @include('layouts.validation')
@endif

<form action="/admin/pembayaranpembelian" method="POST" enctype="multipart/form-data" >
@csrf
<div class="row">
        <div class="col-md-6">
                <div class="card">
                <div class="card-body">
                    <label>Nomer Pembayaran :</label>
                    <input type="text" class="form-control" name="nomer_payment">
                    
                    <label>Tanggal : </label>
                    <input type="date" class="form-control" name="tanggal_payment" value="{{ date('Y-m-d') }}">
                    
                    <label>Keterangan : </label>
                    <textarea class="form-control" rows="5" name="keterangan" ></textarea>
                </div>
            </div>
        </div>    

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
    
    <div class="card">
        <div class="card-body">
            <table class="datatable-init table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:100px">No. Faktur</th>
                        <th style="width:100px">Tanggal</th>
                        <th style="width:150px">Total</th>
                        <th>Bayar</th>
                        <th style="width:150px">Telah Dibayar</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="table-faktur" ></tbody>
            </table>
            <button type="submit" class="btn btn-primary mt-2" >Simpan</button>
        </div>
    </div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">

    let supplier = document.querySelector('#supplier')
    let url = '<?= url('/'); ?>'
    let bodyFaktur = document.querySelector('#table-faktur')

    supplier.onchange = function(e){
        let urlGetFaktur = url + '/admin/pembayaranpembelian/faktur/' + e.target.value
        getFaktur(urlGetFaktur)
    }

    function getFaktur(link){
        xhttp = new XMLHttpRequest()
        xhttp.onload = function(){
            let data = JSON.parse(this.responseText)
            let html = ''


            data.map((item, index) => {
                console.log(item)
                total = formatRupiah(item.total.toString())
                dibayar = formatRupiah(item.bayar_sebelumnya.toString())
                value = item.total - item.bayar_sebelumnya;
                html += `
                    <tr id="faktur_${item.id}">
                        <td>${item.nomer_fakturpembelian}</td>
                        <td>${item.tanggal}</td>
                        <td>Rp.${total}</td>
                        <td>
                            <input type="text" hidden class="form-control" name="id[${item.id}]" value="${item.id}">
                            <input type="text" class="form-control" name="bayar[${item.id}]" value="${value}">
                            <input type="text" hidden name="total[${item.id}]" value="${value}">\
                        </td>
                        <td>Rp.${dibayar}</td>
                        <td><span onclick="hapusTdFaktur(${item.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></span></td>
                    </tr>
                `
            })
            bodyFaktur.innerHTML = html
        }
        xhttp.open('GET', link)
        xhttp.send()
    }

    $(document).ready(function() {
        $('.datatable-init').DataTable()
    })

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

    function hapusTdFaktur(id){
        $('#faktur_'+id).remove();
    }

</script>
@endsection