<!DOCTYPE html>
<html lang="en">

<head>
    <title>Delivery Order : {{$so->so_nomer}}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
        td {
            font-size: 8px;
            font-family: Arial, Helvetica, sans-serif;
            border: 1px solid black;
            padding: 2;
        }

        th {
            font-size: 8px;
            font-family: Arial, Helvetica, sans-serif;
            border: 1px solid black;
            padding: 2;
        }
    </style>
</head>

<body>

    <div style="width:100%; padding:10px; border: 1px solid black">
        <table style="width: 100%;">
            <tr>
                <td style="width: 20%;" rowspan="4">
                    <img style="width: 100%;" src="http://miresmahisa.com/vuexy/images/logo/logo.png" alt="">
                </td>
                <td style="text-align: center; font-size: 12; color: white; background-color: #3bb4cc" colspan="2">
                    <strong>SALES ORDER</strong>
                </td>
            </tr>

            <tr>
                <td style="width: 15%;"><strong>No. Sales Order</strong></td>
                <td>{{ $so->so_nomer }}</td>
            </tr>

            <tr>
                <td><strong>Tanggal</strong></td>
                <td>{{ $so->so_tanggal }}</td>
            </tr>

            <tr>
                <td><strong>Sales</strong></td>
                <td>{{ $so->sales->nama_sales }}</td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10px">
            <tr>
                <td style="width: 20%;"><strong>Nama Customer</strong></td>
                <td>{{ $so->pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <td><strong>Alamat Customer</strong></td>
                <td>{{ $so->pelanggan->detail_alamat }}</td>
            </tr>
            <tr>
                <td><strong>No. Telp</strong></td>
                <td>{{ $so->pelanggan->handphone_pelanggan }}</td>
            </tr>
            <tr>
                <td><strong>Ekspedisi</strong></td>
                <td>{{ $so->ekspedisi }}</td>
            </tr>
            <tr>
                <td><strong>No. Resi</strong></td>
                <td>{{ $so->resi }}</td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10px">
            <thead style="text-align: center; color: white; background-color: #3bb4cc">
                <tr>
                    <th>NO</th>
                    <th>KODE PRODUK</th>
                    <th>NAMA PRODUK</th>
                    <th>QTY</th>
                    <th>SATUAN</th>
                </tr>
            </thead>
            <tbody>
                @php $total_qty=0 @endphp
                @foreach($so_rinci as $rinci)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rinci->barang->kode_barang }}</td>
                    <td>{{ $rinci->barang->nama_barang }}</td>
                    <td>{{ $rinci->qty_barang }}</td>
                    <td>{{ $rinci->barang->satuan_barang }}</td>
                    @php $total_qty += $rinci->qty_barang @endphp
                </tr>
                @endforeach
                <tr>
                    <td style="text-align: center;" colspan="3"><strong>TOTAL</strong></td>
                    <td style="text-align: center;" colspan="2"><strong>{{$total_qty}}</strong></td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; margin-top: 10px">
            <tr>
                <td style="height: 50px; width: 15%"></td>
                <td style="width: 15%;"></td>
                <td style="width: 15%;"></td>
                <td style="vertical-align: text-top;" rowspan="2">
                    Note : 
                    <p>{{ $so->keterangan }}</p>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td>ADMIN SALES</td>
                <td>WAREHOUSE</td>
                <td>ACCOUNTING</td>
            </tr>
        </table>

    </div>

</body>

</html>