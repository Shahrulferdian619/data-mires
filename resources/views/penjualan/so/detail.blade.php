@extends('layouts.vuexy')

@section('header')
Sales Order
@endsection

@section('content')

<a href="/admin/so">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <label>Nomer Pesanan SO</label>
                <input type="text" class="form-control" readonly value="{{ $so->so_nomer }}">

                <label>Tanggal Pesanan SO</label>
                <input type="date" class="form-control" readonly value="{{ $so->so_tanggal }}">

                <label>Customer</label>
                <input type="text" class="form-control" readonly value="{{ $so->customer->nama_pelanggan }}">

                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" readonly>{{ $so->keterangan }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Discount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $subtotal = 0; ?>
                    @foreach($so->rinci as $rinci)
                    <tr>
                        <td>{{ $rinci->barang->nama_barang }}</td>
                        <td>{{ $rinci->qty_barang }}</td>
                        <td>Rp.{{ number_format($rinci->harga_barang) }}</td>
                        <td>{{ $rinci->diskon_barang }} %</td>
                    </tr>
                    <?php $total = $rinci->qty_barang * ($rinci->harga_barang - ($rinci->harga_barang * $rinci->diskon_barang / 100)); ?>
                    <?php $subtotal += $total; ?>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"> <strong>Grand Total</strong> </td>
                        <td>Rp.{{ number_format($subtotal) }}</td>
                    </tr>
                    @if($so->is_tax == 1)
                    <tr>
                        <td colspan="3" class="text-right"> <strong>PPN 10%</strong> </td>
                        <td>Rp.{{ number_format($subtotal+($subtotal*10/100)) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-body">
        <h3 class="card-title">
            Berkas Pendukung
        </h3>
        @if (!empty($so->berkas))
        <table class="table table-striped">
            <tr>
                <td>Berkas 1</td>
                <td>:</td>
                <td>
                    @if ($so->berkas->berkas_1 != '')
                    <a href="{{ asset('uploads/so/' . $so->berkas->berkas_1) }}" target="_blank" rel="noopener noreferrer">{{ $so->berkas->berkas_1 }}</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Berkas 2</td>
                <td>:</td>
                <td>
                    @if ($so->berkas->berkas_2 != '')
                    <a href="{{ asset('uploads/so/' . $so->berkas->berkas_2) }}" target="_blank" rel="noopener noreferrer">{{ $so->berkas->berkas_2 }}</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Berkas 3</td>
                <td>:</td>
                <td>
                    @if ($so->berkas->berkas_3 != '')
                    <a href="{{ asset('uploads/so/' . $so->berkas->berkas_3) }}" target="_blank" rel="noopener noreferrer">{{ $so->berkas->berkas_3 }}</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Berkas 4</td>
                <td>:</td>
                <td>
                    @if ($so->berkas->berkas_4 != '')
                    <a href="{{ asset('uploads/so/' . $so->berkas->berkas_4) }}" target="_blank" rel="noopener noreferrer">{{ $so->berkas->berkas_4 }}</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Berkas 5</td>
                <td>:</td>
                <td>
                    @if ($so->berkas->berkas_5 != '')
                    <a href="{{ asset('uploads/so/' . $so->berkas->berkas_5) }}" target="_blank" rel="noopener noreferrer">{{ $so->berkas->berkas_5 }}</a>
                    @endif
                </td>
            </tr>
        </table>
        @else
        <p>Tidak ada berkas</p>
        @endif

    </div>
</div>


@endsection