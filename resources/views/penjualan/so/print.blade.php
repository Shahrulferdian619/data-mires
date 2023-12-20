<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #table-head tr td{
            border: 1px solid black;
            padding: 2px;
            font-size:12px;
            text-align: center;
        }
        #table3 tr td, #table3 tr th{
            border: 1px solid black;
            font-size:12px;
            padding: 3px;
        }
        #table2 tr td {
            font-size:12px;
        }
        body { font-family: Arial; }
        p { font-family: Courier, monospace; }
        div { font-family: Duru Sans, Verdana, sans-serif; }
        .card{
            font-size:12px;
            border: 1px solid black;
            width: 150px;
            text-align:center;
        }
    </style>
</head>
<body>
    <div class="" style="width:100%; padding:10px; border: 1px solid black">
        <table cellspacing="0" cellpadding="0" id="table-head" style="width:100%; margin-bottom: 10px">
            <tr>
                <td rowspan="4" style="width:10%; padding: 2px; text-align:center">
                    <img style="width:100px" src="http://miresmahisa.com/vuexy/images/logo/logo.png" >
                    <small>PT MIRES MAHISA GLOBALINDO</small>
                </td>
                <td  colspan="2" style="text-align: center">
                    <strong>FORM DELIVERY ORDER</strong>
                </td>
            </tr>
            <tr>
                <td style="width:10%"><small>No DO</small></td>
                <td></td>
            </tr>
            <tr>
                <td><small>Tanggal</small> </td>
                <td></td>
            </tr>
            <tr>
                <td> <small>Halaman</small> </td>
                <td></td>
            </tr>
        </table>
        <table id="table2" style="border:none; width:100%; margin-bottom: 10px">
            <tr>
                <td>No Sales Order</td>
                <td>: {{ $so->so_nomer }}</td>
                <td>Tanggal Pengiriman</td>
                <td style="width:25%">: </td>
            </tr>
            <tr>
                <td>Tanggal Sales Order</td>
                <td>: {{ $so->created_at }}</td>
                <td>Ekspedisi</td>
                <td>: {{ $so->ekspedisi }}</td>
            </tr>
            <tr>
                <td>Nama Customer</td>
                <td>: {{ $so->pelanggan->nama_pelanggan }}</td>
                <td>No Resi Pengiriman</td>
                <td>: {{ $so->resi }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $so->pelanggan->detail_alamat }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <table id="table3" style="width:100%" cellspacing="0" cellpadding="0">
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Satuan</th>
            </tr>
            @php $total = 0; @endphp
            @foreach($so_rinci as $rinci)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rinci->barang->kode_barang }}</td>
                <td>{{ $rinci->barang->nama_barang }}</td>
                <td>{{ $rinci->qty_barang }}</td>
                <td>{{ $rinci->barang->satuan_barang }}</td>
                @php $total += $rinci->qty_barang @endphp
            </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: right;"> <strong>TOTAL</strong> </td>
                <td>{{ $total }}</td>
                <td></td>
            </tr>
        </table>
        <table style="width:100%; padding: 15px;" >
            <tr>
                <td>
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid black"><strong>Adm Sales</strong></div>
                        <br><br><br>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid black"><strong>Gudang</strong></div>
                        <br><br><br>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid black"><strong>Driver / Sales</strong></div>
                        <br><br><br>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-header" style="border-bottom: 1px solid black"><strong>Customer</strong></div>
                        <br><br><br>
                    </div>
                </td>
            </tr>
        </table>
        <div style="font-size:12px;">Keterangan : Lembar Putih: Gudang &nbsp; Merah: Accounting &nbsp; Kuning: Adm Sales &nbsp; Hijau: Customer</div style="font-size:12px;">
    </div>
</body>
</html>