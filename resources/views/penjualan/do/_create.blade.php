@extends('layouts.vuexy')

@section('header')
Create Deliver Order (Buat Kiriman Penjualan)
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

    <form action="{{ url('admin/do/store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="do_nomer" class="form-label">Nomor Pengiriman</label>
                            <input type="text" name="do_nomer" id="do_nomer" class="form-control">
                        </div>
                        <div class="mb-1" >
                            <label for="do_tanggal" class="form-label">Tanggal Pengiriman</label>
                            <input type="date" name="do_tanggal" value="{{ date('Y-m-d') }}" id="do_tanggal" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1" >
                            <label for="do_alamat" class="form-label">Alamat Kirim</label>
                            <input type="text" name="do_alamat" id="do_alamat" class="form-control">
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
                            <label for="do_pic" class="form-label">Nama Penerima</label>
                            <input type="text" name="do_pic" id="do_pic" class="form-control" value="-">
                        </div>
                        <div class="mb-1" >
                            <label for="id_pelanggan" class="form-label">Pilih Customer</label>
                            <select class="select2 form-select form-control" name="id_pelanggan" id="id_pelanggan">
                                <option value="0">-- Pilih Customer --</option>
                                @foreach($pelanggan as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1" >
                            <label for="so_penjualan_id" class="form-label">Pilih SO</label>
                            <select class="select2 form-select form-control" name="so_penjualan_id" id="so_penjualan_id">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th class="text-center" >Nama Barang</th>
                                    <th class="text-center" >Jumlah Order</th>
                                    <th class="text-center" >Sudah Dikirim</th>
                                    <th class="text-center" >Akan Dikirim</th>
                                    <th> Diambil Dari </th>
                                </tr>
                            </thead>
                            <tbody id="so_rinci" class="text-center">
                            </tbody>
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
                        <a href="{{ url('admin/do') }}" class="btn btn-danger">Kembali</a>
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
            url: "/admin/do/get_so/" + id_pelanggan,
            dataType: "JSON",
            success: function(data) {
                console.log(data)
                let i = 0
                let html = ''
                html += "<option value='0'>-- --Pilih Nomor SO-- --</option>"
                for(i=0; i<data.data.length; i++) {
                    html += "<option value="+ data.data[i].id +">" + data.data[i].so_nomer + "</option>"
                }
                $('#so_penjualan_id').html(html)
            },
            error: function(e) {
                console.log(e)
            }
        })
    });
    $('#so_penjualan_id').on('change', function(el) {
        $.ajax({
            method: 'GET',
            url: '/admin/do/get_so_rinci/' + $('#so_penjualan_id').val(),
            dataType: 'JSON',
            success: function(data){
                console.log(data)
                let html = ''
                data.map((item, index) => {
                    console.log(item)
                    let perlu_kirim = item.qty_barang - item.jumlah_kirim;
                    html += `
                    <tr>
                        <td> ${item.barang.nama_barang}<input type="hidden" class="form-control" name="barang_id[${item.id}]" value="${item.barang.id}"><input type="hidden" class="form-control" name="id[${item.id}]" value="${item.id}"></td>
                        <td><span class="badge badge-warning" >${item.qty_barang}</span></td>
                        <td> <span class="badge badge-success" >${item.jumlah_kirim}</span></td>
                        <td> <input type="text" text class="form-control" name="kirim[${item.id}]" value="${perlu_kirim}"></td>
                        <td> 
                        <select type="text" text class="form-control" name="gudang[${item.id}]">
                        <?php foreach($gudang as $val){
                            echo '<option value="'.$val->id.'">'.$val->nama_gudang.'</option>';
                        } ?>
                        </select></td>
                    </tr>
                    `
                })
                $('#so_rinci').html(html)
            }
        })
    })
</script>
@endsection