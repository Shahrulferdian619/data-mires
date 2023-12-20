@extends('layouts.vuexy')

@section('header')
Create Sales Order (Buat Tagihan Penjualan)
@endsection

@section('content')
@if($errors->all())
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            @if(!empty(session('error')))
                <li>{{ session('error') }}</li>
            @endif
        </ul>
    </div>
</div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session()->get('error') }}</li>
        </ul>
    </div>
</div>
@endif

    <form action="{{ url('admin/si/store') }}" method="post">
    @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="si_nomer" class="form-label">Nomor Invoice</label>
                            <input type="text" name="si_nomer" id="si_nomer" class="form-control">
                        </div>
                        <div class="mb-1" >
                            <label for="si_tanggal" class="form-label">Tanggal Tagihan Penjualan</label>
                            <input type="date" name="si_tanggal" value="{{ date('Y-m-d') }}" id="si_tanggal" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control">-</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                            <select class="select2 form-select form-control" name="id_pelanggan" id="id_pelanggan">
                                <option value="0">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $pelanggan)
                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1" >
                            <label for="id_so" class="form-label">Pilih Sales Order</label>
                            <select class="select2 form-select form-control" name="id_so" id="id_so">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th class="text-center" >Nama Barang</th>
                                    <th class="text-center" >Jumlah</th>
                                    <th class="text-center" >Harga</th>
                                    <th class="text-center" >Diskon</th>
                                </tr>
                            </thead>
                            <tbody id="table-so-rinci">
                                
                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th class="text-right" colspan="3" >Total Harga</th>
                                    <th class="text-right" >0</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_1" class="form-label">Berkas 1</label>
                                    <input class="form-control" type="file" name="berkas_1" id="berkas_1" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_2" class="form-label">Berkas 2</label>
                                    <input class="form-control" type="file" name="berkas_2" id="berkas_2" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_3" class="form-label">Berkas 3</label>
                                    <input class="form-control" type="file" name="berkas_3" id="berkas_3" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_4" class="form-label">Berkas 4</label>
                                    <input class="form-control" type="file" name="berkas_4" id="berkas_4" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_5" class="form-label">Berkas 5</label>
                                    <input class="form-control" type="file" name="berkas_5" id="berkas_5" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('admin/si') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary">Buat Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('myjs')
<script>
    $('#id_pelanggan').change(function(){
        let id_pelanggan = $('#id_pelanggan').val();
        $.ajax({
            method: "GET",
            url: "/admin/si/get_so/" + id_pelanggan,
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                let i = 0
                let html = ''
                html += "<option value='0'>-- --Pilih Nomor SO-- --</option>"
                for(i=0; i<data.data.length; i++) {
                    html += "<option value="+ data.data[i].id +">" + data.data[i].so_nomer + "</option>"
                }
                $('#id_so').html(html)
            },
            error: function(e) {
                console.log(e)
            }
        })
    });

    $('#id_so').on('change', function(el) {
            $.ajax({
                method: 'GET',
                url: '/admin/si/get_so_rinci/' + $('#id_so').val(),
                dataType: 'JSON',
                success: function(data){
                    console.log(data)
                    let html = ''
                    data.map((item, index) => {
                        console.log(item)
                        html += `
                        <tr>
                            <td> ${item.barang.nama_barang}<input type="hidden" class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="hidden" class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                            <td><input type="text" readonly class="form-control" name="jumlah[${item.id}]" value="${item.qty_barang}"></td>
                            <td> <input type="text" readonly text class="form-control" name="harga[${item.id}]" value="${item.harga_barang}"></td>
                            <td> <input type="text" readonly text class="form-control" name="diskon[${item.id}]" value="${item.diskon_barang}"></td>
                        </tr>
                        `
                    })
                    $('#table-so-rinci').html(html)
                }
            })
    })
</script>
@endsection