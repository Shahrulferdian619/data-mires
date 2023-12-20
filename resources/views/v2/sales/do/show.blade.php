@extends('layouts.vuexy')

@section('header')
Rincian Penjualan Order
@endsection

@section('content')

<a href="{{route('do.index')}}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

@if (session()->has('fail'))
@include('layouts.fail')
@endif

@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Success !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session()->get('success') }}</li>
        </ul>
    </div>
</div>
@endif

<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td colspan="2">
                            @if($so->status_do == 0)
                            <div class="alert alert-warning" role="alert">
                                <div class="alert-body">
                                    Belum Dikirim
                                </div>
                            </div>
                            @elseif($so->status_do == 2)
                            <div class="alert alert-success" role="alert">
                                <div class="alert-body">
                                    Sudah Dikirim
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Nomer Penjualan</td>
                        <td>: {{ $so->so_nomer }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Penjualan</td>
                        <td>: {{ $so->jenis_penjualan }}</td>
                    </tr>
                    @if(!empty($so->no_pesanan))
                    <tr>
                        <td>No Pesanan</td>
                        <td>: {{ $so->no_pesanan }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Tanggal Penjualan</td>
                        <td>: {{ $so->so_tanggal }}</td>
                    </tr>
                    <tr>
                        <td>Nama Customer/Pemesan</td>
                        <td>: {{ $so->pelanggan->nama_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td>Nama Penerima</td>
                        <td>: {{ $so->penerima }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Pengiriman</td>
                        <td>: {{ $so->alamat_pengiriman }}</td>
                    </tr>

                    @if(!empty($so->ekspedisi))
                    <tr>
                        <td>Ekspedisi</td>
                        <td>: {{ $so->ekspedisi }}</td>
                    </tr>
                    @endif

                    @if(!empty($so->resi))
                    <tr>
                        <td>No Resi</td>
                        <td>: {{ $so->resi }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Keterangan Penjualan</td>
                        <td>: {{ $so->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th class="text-align">Nama Barang</th>
                                <th class="text-align">Jumlah</th>
                                <th class="text-align">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($so->rinci as $key => $value)
                            <tr>
                                <td>{{ $value->barang->nama_barang }}</td>
                                <td>{{ $value->qty_barang }}</td>
                                <td>{{ $value->note }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (!empty($so->berkas))
<div class="row mt-1">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 30%">Berkas 1</td>
                        <td>:
                            @if ($so->berkas->berkas_1 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_1) }} "> {{ $so->berkas->berkas_1 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 2</td>
                        <td>:
                            @if ($so->berkas->berkas_2 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_2) }} "> {{ $so->berkas->berkas_2 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 3</td>
                        <td>:
                            @if ($so->berkas->berkas_3 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_3) }} "> {{ $so->berkas->berkas_3 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 4</td>
                        <td>:
                            @if ($so->berkas->berkas_4 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_4) }} "> {{ $so->berkas->berkas_4 }} </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Berkas 5</td>
                        <td>:
                            @if ($so->berkas->berkas_5 != '')
                            <a href=" {{ asset('uploads/so_penjualan/' . $so->berkas->berkas_5) }} "> {{ $so->berkas->berkas_5 }} </a>
                            @endif
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>
@endif


<div class="">
    <div class="card">
        <div class="card-body">
            @if($so->status_do != 2)
            <button class="btn btn-outline-primary" onclick="approve()">Kirim Barang!</button>
            @else
            <a href="/admin/do/{{$so->id}}/surat-jalan" target="_blank" class="btn btn-outline-success">
                <i class="fa fa-print"></i>
                Print SJ Lama (akan dihilangkan bila format baru sudah ok)
            </a>

            <a href="{{ route('do.print_sj', $so->id) }}" target="_blank" class="btn btn-outline-success">
                <i class="fa fa-print"></i>
                Print SJ Baru
            </a>
            @endif
        </div>
    </div>
</div>


<div class="modal fade" id="approveKeterangan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="text-center m-1">Anda Yakin Akan Mengirimkan Barang?</h3>
                <form action="{{ url('admin/do/store') }}" id="form-save" method="post" enctype="multipart/form-data">
                    @csrf

                    <label>Ambil Dari Gudang</label>
                    <select name="gudang" class="form-control">
                        @foreach ($gudang as $val) {
                        <option value="{{ $val->id }}">{{ $val->nama_gudang }}</option>
                        @endforeach
                    </select>
                    <br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-align">Nama Barang</th>
                                <th class="text-align">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($so->rinci as $key => $item)
                            <tr>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td>{{ $item->qty_barang }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <small>- Periksa Kembali Barang Yang Akan Dikirimkan!</small> <br>
                    <small>- Pastikan Barang Yang Dikirimkan Sudah Lengkap & Benar!</small>
            </div>
            <div class="modal-footer mb-2">
                <input type="hidden" value="{{ $so->id }}" name="id_so">
                <button type="submit" class="btn btn-primary">Ya!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function approve() {
        $('#approveKeterangan').modal('show');
    }
</script>
@endsection