@extends('layouts.vuexy')

@section('header')
Edit Deliver Order (Ubah Kiriman Penjualan)
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

    <form action="{{ url('admin/do/'.$do->id.'/edit') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="do_nomer" class="form-label">Nomor Pengiriman</label>
                            <input type="text" name="do_nomer" id="do_nomer" value="{{ $do->do_nomer }}" class="form-control">
                        </div>
                        <div class="mb-1" >
                            <label for="do_tanggal" class="form-label">Tanggal Pengiriman</label>
                            <input type="date" name="do_tanggal" value="{{ date($do->do_tanggal) }}" id="do_tanggal" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1" >
                            <label for="do_alamat" class="form-label">Alamat Kirim</label>
                            <input type="text" name="do_alamat" id="do_alamat" value="{{ $do->alamat_do }}" class="form-control">
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" value="{{ $do->keterangan }}" class="form-control">-</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="do_pic" class="form-label">Nama Penerima</label>
                            <input type="text" name="do_pic" id="do_pic" value="{{ $do->pic_do }}" class="form-control" value="-">
                        </div>
                        <div class="mb-1" >
                            <label for="id_pelanggan" class="form-label">Pilih Customer</label>
                            <input type="text" disabled class="form-control" value="{{ $do->pelanggan->nama_pelanggan }}" name="id_pelanggan" id="">
                        </div>
                        <div class="mb-1" >
                            <label for="so_penjualan_id" class="form-label">Pilih SO</label>
                            <input type="text" class="form-control" disabled value="{{ $do->so->so_nomer }}" name="" id="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-stripped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="text-center" >Nama Barang</th>
                                    <th class="text-center" >Jumlah Kiriman</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($do->rinci as $rinci)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rinci->barang->nama_barang }}</td>
                                    <td>{{ $rinci->qty }}</td>
                                    <td>{{ $rinci->note }}</td>
                                </tr>
                                @endforeach
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
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('admin/do') }}" class="btn btn-outline-danger">Kembali</a>
                        <button type="submit" class="btn btn-outline-primary">Update Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
                            '<button type="button" class="btn btn-outline-danger btn-sm btn-hapus-berkas" data-id="flag_'+flag+'"><i class="fa fa-trash"></i> Hapus</button>'+
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

</script>
@endsection