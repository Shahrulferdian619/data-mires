@extends('layouts.vuexy')

@section('header')
Edit Sales Order (Ubah Pesanan Penjualan)
@endsection

@section('content')
    @if($errors->all())
        @include('layouts.validation')
    @endif
    <form action="{{ url('admin/so/'.$so->id.'/edit') }}" method="POST" enctype="multipart/form-data" >
        @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1">
                            <label for="so_nomer" class="form-label">Nomor Penjualan</label>
                            <input type="text" name="so_nomer" id="so_nomer" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_nomer'] : $so->so_nomer }}" class="form-control">
                        </div>
                        <!-- <div class="mb-1">
                            <label for="id_sales" class="form-label">Sales</label>
                            <select name="id_sales" id="" class="form-control">
                                <option value="0">-- Pilih Sales --</option>
                                <option value="1">Ibnu Khafid</option>
                            </select>
                        </div> -->
                        <div class="mb-1">
                            <label for="so_tanggal" class="form-label">Tanggal Penjualan</label>
                            <input type="date" name="so_tanggal" id="so_tanggal" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_tanggal'] : date($so->so_tanggal) }}" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" value="-">{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['keterangan'] : $so->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="id_pelanggan" class="form-label">Pelanggan</label>
                            <select class="select2 form-select" name="id_pelanggan" id="id_pelanggan">
                                @foreach ($customer as $item)
                                    <option <?php if($so->id_pelanggan == $item->id){ echo 'selected'; } ?> value="{{$item->id}}" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['id_pelanggan'] == $item->id ? 'selected' : '') : '' }} >{{ $item->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1" >
                            <label for="jenis_penjualan" class="form-label">Jenis Penjualan</label>
                            <select class="select2 form-select" name="jenis_penjualan" id="jenis_penjualan">
                                <option <?php if($so->jenis_penjualan == 'Langsung'){ echo 'selected'; } ?> value="Langsung" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == 'Langsung' ? 'selected' : '') : '' }} >Langsung</option>
                                <option <?php if($so->jenis_penjualan == 'Online Shopee'){ echo 'selected'; } ?> value="Online Shopee" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == 'Online Shopee' ? 'selected' : '') : '' }} >Online Shopee</option>
                                <option <?php if($so->jenis_penjualan == 'Online Tokopedia'){ echo 'selected'; } ?> value="Online Tokopedia" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == 'Online Tokopedia' ? 'selected' : '') : '' }} >Online Tokopedia</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="is_tax" class="form-label">PPN</label>
                            <select class="select2 form-select" name="is_tax" id="is_tax">
                                <option <?php if($so->is_tax == 0){ echo 'selected'; } ?> value="0" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 0 ? 'selected' : '') : '' }} >Tidak</option>
                                <option <?php if($so->is_tax == 1){ echo 'selected'; } ?> value="1" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 1 ? 'selected' : '') : '' }} >Iya</option>
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
                                    <th class="text-align" >#</th>
                                    <th class="text-align" >Nama Barang</th>
                                    <th class="text-align" >Jumlah Barang</th>
                                    <th class="text-align" >Diskon Barang</th>
                                    <th class="text-align" >Harga Barang</th>
                                </tr>
                            </thead>
                            @php
                                $grandTotal = 0;
                            @endphp
                            <tbody>
                                @foreach($so->rinci as $rinci)
                               <tr>
                                   <td>{{ $loop->iteration }}</td>
                                   <td>{{ $rinci->barang->nama_barang }}</td>
                                   <td>{{ $rinci->qty_barang }}</td>
                                   <td>{{ $rinci->diskon_barang }}%</td>
                                   <td>Rp.{{ number_format($rinci->harga_barang) }}</td>
                                   @php 
                                    $grandTotal += ($rinci->harga_barang - ($rinci->harga_barang * $rinci->diskon_barang / 100)) * $rinci->qty_barang ;
                                   @endphp
                               </tr>
                               @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan="4" >Total Harga</th>
                                    <th class="text-right" >Rp. {{ number_format($grandTotal) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless" >
                    <tr>
                        <td >Berkas 1</td>
                        <td>: 
                            @if ($so->berkas->berkas_1 != '')
                                <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas_1) }} "> {{ $so->berkas->berkas_1 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_1" id="berkas_1" /></td>
                    </tr>
                    <tr>
                        <td >Berkas 2</td>
                        <td>: 
                            @if ($so->berkas->berkas_2 != '')
                                <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas_2) }} "> {{ $so->berkas->berkas_2 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_2" id="berkas_2" /></td>
                    </tr>
                    <tr>
                        <td >Berkas 3</td>
                        <td>: 
                            @if ($so->berkas->berkas_3 != '')
                                <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas_3) }} "> {{ $so->berkas->berkas_3 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_3" id="berkas_3" /></td>
                    </tr>
                    <tr>
                        <td >Berkas 4</td>
                        <td>: 
                            @if ($so->berkas->berkas_4 != '')
                                <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas_4) }} "> {{ $so->berkas->berkas_4 }} </a>
                            @endif
                        </td>
                        <td><input class="form-control" type="file" name="berkas_4" id="berkas_4" /></td>
                    </tr>
                    <tr>
                        <td >Berkas 5</td>
                        <td>: 
                            @if ($so->berkas->berkas_5 != '')
                                <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas_5) }} "> {{ $so->berkas->berkas_5 }} </a>
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
                        <a href="{{ url('admin/so/'.$so->id) }}" class="btn btn-danger">Kembali</a>
                        <button class="btn btn-primary" name="create-so-penjualan">Update Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.querySelector('#diskon_barang').onkeyup = function(e){
            // console.log(this.value)
            if(this.value != ''){
                if(this.value > 100){
                    this.value = 100
                }
            }
        }
    </script>
@endsection