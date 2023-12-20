@extends('layouts.vuexy')

@section('header')
Pembayaran
@endsection

@section('content')
@if($errors->all() || !empty(session('error')))
    @include('layouts.validation')
@endif

<form action="/admin/pembayaranpembelian" id="form-save" method="POST" enctype="multipart/form-data" >
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
            <button type="submit" id="btnSimpan" class="btn btn-outline-primary mt-2" >Simpan</button>
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
                // total = formatRupiah(item.total.toString())
                total = item.total.toLocaleString()
                dibayar = formatRupiah(item.bayar_sebelumnya.toString())
                value = item.total_kekurangan;
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
                        <td><span onclick="hapusTdFaktur(${item.id})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></span></td>
                    </tr>
                `
            })
            bodyFaktur.innerHTML = html
            replaceRP()
        }
        xhttp.open('GET', link)
        xhttp.send()
    }

    $(document).ready(function() {
        $('.datatable-init').DataTable()
    })

    function replaceRP(){
        let bayar = document.querySelectorAll('.bayar')
        bayar.forEach((item, index) => {
            let newBayar = item.value
            item.value = 'Rp. ' + formatRupiah(newBayar.toString())

            item.addEventListener('keyup',function(){
                let rmRP = removeRp(item.value)
                item.value = 'Rp. ' + formatRupiah(rmRP.toString())
            })

        })
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

    function hapusTdFaktur(id){
        $('#faktur_'+id).remove();
    }

</script>
@endsection