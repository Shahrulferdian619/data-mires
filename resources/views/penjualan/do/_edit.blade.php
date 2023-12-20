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
            </div><div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless" >
                    <tr>
                        <td>Berkas 1</td>
                        <td>: 
                            @if ($do->berkas->berkas_1 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas_1) }} "> {{ $do->berkas->berkas_1 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_1" id="berkas_1" /></td>
                    </tr>
                    <tr>
                        <td>Berkas 2</td>
                        <td>: 
                            @if ($do->berkas->berkas_2 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas_2) }} "> {{ $do->berkas->berkas_2 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_2" id="berkas_2" /></td>
                    </tr>
                    <tr>
                        <td>Berkas 3</td>
                        <td>: 
                            @if ($do->berkas->berkas_3 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas_3) }} "> {{ $do->berkas->berkas_3 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_3" id="berkas_3" /></td>
                    </tr>
                    <tr>
                        <td>Berkas 4</td>
                        <td>: 
                            @if ($do->berkas->berkas_4 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas_4) }} "> {{ $do->berkas->berkas_4 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_4" id="berkas_4" /></td>
                    </tr>
                    <tr>
                        <td>Berkas 5</td>
                        <td>: 
                            @if ($do->berkas->berkas_5 != '')
                                <a href=" {{ asset('uploads/do_penjualan/' . $do->berkas_5) }} "> {{ $do->berkas->berkas_5 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_5" id="berkas_5" /></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('admin/do') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection