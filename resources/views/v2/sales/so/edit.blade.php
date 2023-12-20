@extends('v2.layout.adminlte')

@section('header')
Sales Order
<small>Ubah Data</small>
@endsection

@section('content')
<div class="callout callout-success">
    <h4>PERHATIAN !!!</h4>
    <p>Mengubah data Sales Order yang sudah diproses, akan menghapus transaksi Penerimaan Penjualan dan Invoice</p>
    <p>Harap koordinasi dengan divisi terkait setelah melakukan perubahan data. Terima kasih !</p>
</div>
<form action="{{route('so.update_v2')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-body">
                    <label for="">No. Sales Order</label>
                    <input type="hidden" name="so_id" value="{{$sales_order->id}}">
                    <input type="text" name="so_nomer" class="form-control" value="{{ $sales_order->so_nomer }}" readonly>

                    <label for="">Tanggal</label>
                    <input type="date" name="so_tanggal" class="form-control" value="{{ $sales_order->so_tanggal }}">

                    <label for="">Jenis Penjualan</label>
                    <select name="jenis_penjualan" class="form-control select2" style="width: 100%;">
                        <option selected value="{{ $sales_order->jenis_penjualan }}">{{ $sales_order->jenisPenjualan->jenis_penjualan }}</option>
                        @foreach($data_jenis_penjualan as $jenis_penjualan)
                        <option value="{{ $jenis_penjualan->id }}">{{ $jenis_penjualan->jenis_penjualan }}</option>
                        @endforeach
                    </select>

                    <label for="">No. Pesanan</label>
                    <input type="text" name="no_pesanan" class="form-control" value="{{$sales_order->no_pesanan}}">

                    <label for="">PPn 11%</label>
                    <select name="is_tax" id="" class="form-control">
                        <option selected value="{{$sales_order->is_tax}}">TIDAK</option>
                        <option value="1">YA</option>
                        <option value="0">TIDAK</option>
                    </select>

                    <label for="">Ekspedisi</label>
                    <select name="ekspedisi" class="form-control select2" style="width: 100%;">
                        <option value="{{$sales_order->ekspedisi}}">{{$sales_order->ekspedisi}}</option>
                        @foreach($data_ekspedisi as $ekspedisi)
                        <option value="{{$ekspedisi->nama_ekspedisi}}">{{$ekspedisi->nama_ekspedisi}}</option>
                        @endforeach
                    </select>

                    <label for="">No. Resi</label>
                    <input type="text" name="resi" class="form-control" value="{{$sales_order->resi}}">

                    <label for="">Keterangan tambahan</label>
                    <textarea name="keterangan" cols="30" rows="5" class="form-control">{{$sales_order->keterangan}}</textarea>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-warning">
                <div class="box-body">
                    <label for="">Pelanggan / Customer</label>
                    <select name="id_pelanggan" class="form-control select2" style="width: 100%;">
                        <option selected value="{{ $sales_order->id_pelanggan }}">{{ $sales_order->pelanggan->kode_pelanggan }} | {{ $sales_order->pelanggan->nama_pelanggan }}</option>
                        @foreach($data_pelanggan as $pelanggan)
                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->kode_pelanggan }} | {{$pelanggan->nama_pelanggan}}</option>
                        @endforeach
                    </select>

                    <label for="">Sales / Marketing</label>
                    <select name="id_sales" class="form-control select2">
                        <option selected value="{{ $sales_order->id_sales }}">{{ $sales_order->sales->nama_sales }}</option>
                        @foreach($data_sales as $sales)
                        <option value="{{ $sales->id }}">{{ $sales->nama_sales }}</option>
                        @endforeach
                    </select>

                    <label for="">Nama Penerima</label>
                    <input type="text" class="form-control" name="penerima" value="{{$sales_order->penerima}}">

                    <label for="">Alamat Penerima</label>
                    <textarea name="alamat_pengiriman" id="" cols="30" rows="5" class="form-control">{{$sales_order->alamat_pengiriman}}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-warning">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th style="width: 10%;">Harga</th>
                            <th style="width: 5%;">Qty</th>
                            <th style="width: 5%">Dsc %</th>
                            <th style="width: 10%">Dsc Rp</th>
                            <th style="width: 10%">By Adm, Lyn, PPn</th>
                            <th style="width: 10%">Cb Ongkir</th>
                            <th style="width: 10%;">Subtotal</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales_order->rinci as $rincian)
                        <tr>
                            <td>
                                <input type="hidden" name="id_barang[]" value="{{$rincian->id_barang}}">
                                <textarea name="" cols="30" rows="2" class="form-control" readonly>{{$rincian->barang->nama_barang}}"</textarea>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="harga_barang[]" value="{{$rincian->harga_barang}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="qty_barang[]" value="{{$rincian->qty_barang}}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="diskon_barang[]" value="{{$rincian->diskon_barang}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="diskon_nominal[]" value="{{$rincian->diskon_nominal}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="potongan_admin[]" value="{{$rincian->potongan_admin}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="cashback_ongkir[]" value="{{$rincian->cashback_ongkir}}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="subtotal[]" value="{{ ($rincian->harga_barang - ($rincian->harga_barang * $rincian->diskon_barang / 100)) * $rincian->qty_barang }}" readonly>
                            </td>
                            <td>
                                <textarea name="note[]" cols="30" rows="2" class="form-control">{{$rincian->note}}</textarea>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box-footer with-border">
            <div class="pull-right">
                <input type="submit" value="Simpan" class="btn btn-sm btn-primary">
                <a href="/admin/so" class="btn btn-sm btn-warning">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection

@section('custom_js')
<script>
    $(function() {

        $('.select2').select2()

    })
</script>
@endsection