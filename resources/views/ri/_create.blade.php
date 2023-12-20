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
<form action="/admin/ri/store" method="post" enctype="multipart/form-data">
<div class="row">
        <div class="col-md-6">
                <div class="card">
                <div class="card-body">
                    <label>Nomer penerimaan barang : </label>
                    <input type="text" class="form-control" name="nomer_ri">
                    
                    <label>Tanggal penerimaan barang : </label>
                    <input type="date" class="form-control" name="tanggal_ri" value="{{ date('Y-m-d') }}">
                    
                    <label>Keterangan : </label>
                    <textarea class="form-control" rows="5" name="keterangan" >-</textarea>
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
                    
                    <div>
                        <label>Pilih nomer PO : </label>
                        <div id="po">
                            <select id="popembelian_id" class="form-control" name="popembelian_id" >
                                
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
                <tbody id="table-ri" >
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mt-2" >Simpan</button>
        </div>
    </div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">
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
                success: function(data){
                    console.log(data)
                    let html = ''
                    data.map((item, index) => {
                        console.log(item)
                        let sisa_datang = item.jumlah - item.jumlah_datang;
                        html += `
                        <tr>
                            <td> ${item.barang.nama_barang}<input type="text" hidden class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="text" hidden class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                            <td><span class="badge badge-warning">${item.jumlah}</span></td>
                            <td> <input type="text" class="form-control" name="jumlah[${item.id}]" value="${sisa_datang}"> <input type="text" hidden class="form-control" name="harga[${item.id}]" value="${item.harga}"></td>
                            <td>
                                <select class="form-control" name="gudang[${item.id}]">
                                <?php foreach($gudang as $val){
                                    echo '<option value="'.$val->id.'">'.$val->nama_gudang.'</option>';
                                } ?>
                                </select>
                            </td>
                            <td>
                                <span class="badge badge-success">${item.jumlah_datang}</span>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="note[${item.id}]" placeholder="Note">
                            </td>
                        </tr>
                        `
                    })
                    $('#table-ri').html(html)
                }
            })
        })
    })

    function get_po_belum_diproses(id_supplier)
    {
        $.ajax({
            method: "GET",
            url: "/admin/po/belum-diproses/" + id_supplier,
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                let i = 0
                let html = ''
                html += "<option value=''>-- --Pilih Nomor PO-- --</option>"
                for(i=0; i<data.data.length; i++) {
                    html += "<option value="+ data.data[i].id +">" + data.data[i].nomer_po + "</option>"
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