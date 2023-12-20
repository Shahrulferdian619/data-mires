@extends('layouts.vuexy')

@section('header')
Create Sales Order (Buat Pesanan Penjualan)
@endsection

@section('content')
    @if($errors->all())
        @include('layouts.validation')
    @endif
    <form action="{{ url('admin/so') }}" method="POST" enctype="multipart/form-data" >
        @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1">
                            <label for="so_nomer" class="form-label">Nomor Penjualan</label>
                            <input type="text" name="so_nomer" id="so_nomer" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_nomer'] : '' }}" class="form-control">
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
                            <input type="date" name="so_tanggal" id="so_tanggal" value="{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['so_tanggal'] : date('Y-m-d') }}" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" value="-">{{ session()->has('so_penjualan') ? session()->get('so_penjualan')['keterangan'] : '' }}</textarea>
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
                                    <option value="{{$item->id}}" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['id_pelanggan'] == $item->id ? 'selected' : '') : '' }} >{{ $item->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1" >
                            <label for="jenis_penjualan" class="form-label">Jenis Penjualan</label>
                            <select class="select2 form-select" name="jenis_penjualan" id="jenis_penjualan">
                                <option value="Langsung" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == 'Langsung' ? 'selected' : '') : '' }} >Langsung</option>
                                <option value="Online Shopee" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == 'Online Shopee' ? 'selected' : '') : '' }} >Online Shopee</option>
                                <option value="Online Tokopedia" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['jenis_penjualan'] == 'Online Tokopedia' ? 'selected' : '') : '' }} >Online Tokopedia</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="is_tax" class="form-label">PPN</label>
                            <select class="select2 form-select" name="is_tax" id="is_tax">
                                <option value="0" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 0 ? 'selected' : '') : '' }} >Tidak</option>
                                <option value="1" {{ session()->has('so_penjualan') ? (session()->get('so_penjualan')['is_tax'] == 1 ? 'selected' : '') : '' }} >Iya</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-3 mb-1">
                                <div >
                                    <label for="id_barang" class="form-label">Pilih Barang</label>
                                    <select class="select2 form-select" name="id_barang" id="id_barang">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{$item->nama_barang}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 mb-1">
                                <div >
                                    <label for="qty_barang" class="form-label">Jumlah Barang</label>
                                    <input type="number" name="qty_barang" id="qty_barang" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-3 mb-1">
                                <div >
                                    <label for="harga_barang" class="form-label">Harga Barang</label>
                                    <input type="number" name="harga_barang" id="harga_barang" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-3 mb-1">
                                <div >
                                    <label for="diskon_barang" class="form-label">Diskon Barang</label>
                                    <input type="number" name="diskon_barang" id="diskon_barang" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mb-1">
                                <div>
                                    <label for="keterangan_barang" class="form-label">Keterangan</label>
                                    <textarea name="keterangan_barang" id="keterangan_barang" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-1">
                                <div>
                                    <button class="btn btn-sm btn-primary" name="add_items" >Tambah Barang</button>
                                </div>
                            </div>
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
                                @if (session()->has('so_penjualan'))
                                    @foreach (session()->get('so_penjualan')['items'] as $key => $value)
                                    <tr>
                                        <td>
                                            <a href="{{ url('admin/so/hapus-cart/' . $key) }}" class="btn btn-sm btn-warning">Hapus</a>
                                        </td>
                                        <td>{{ $value['nama_barang'] }}</td>
                                        <td class="text-right" >{{ $value['qty_barang'] }}</td>
                                        <td class="text-right" >{{ $value['diskon_barang'] }}%</td>
                                        <td class="text-right" >Rp. {{ number_format($value['harga_barang'], 0, ',', '.') }}</td>
                                        @php
                                            $calculateDC = $value['harga_barang'] - ($value['harga_barang'] * ($value['diskon_barang'] / 100));
                                            $grandTotal += $calculateDC * $value['qty_barang'];
                                        @endphp
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan="4" >Total Harga</th>
                                    <th class="text-right" >Rp. {{ number_format($grandTotal + ($grandTotal * (10 / 100))), 0, ',', '.' }}</th>
                                </tr>
                            </tfoot>
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
                        <a href="{{ url('admin/so') }}" class="btn btn-danger">Kembali</a>
                        <button class="btn btn-primary" name="create-so-penjualan">Buat Data</button>
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