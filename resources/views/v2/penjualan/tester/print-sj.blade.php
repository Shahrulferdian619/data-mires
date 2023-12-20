<!DOCTYPE html>
<html lang="en">

<head>
    <title>Delivery Order : {{ $nomer_sj }}</title>
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
                <td style="width: 20%;" rowspan="2">
                    <img style="width: 100%;" src="http://miresmahisa.com/vuexy/images/logo/logo.png" alt="">
                </td>
                <td>
                    <h6>PT MIRES MAHISA GLOBALINDO</h6>
                    Raya Menganti 27C : Forest Mansion, Cluster Blossom Hill Blok B-08, Kota Surabaya
                    <br>
                    Jawa Timur 60213 - Indonesia
                </td>
            </tr>
            <td style="text-align: center; font-size: 12; color: white; background-color: #3bb4cc">SURAT JALAN</td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10px">
            <tr>
                <td style="width: 20%;"><strong>Nama Customer</strong></td>
                <td>{{ $tester->pelanggan->nama_pelanggan }}</td>
                <td><strong>No. Surat Jalan</strong></td>
                <td>{{ $nomer_sj }}</td>
            </tr>
            <tr>
                <td><strong>Alamat Customer</strong></td>
                <td>{{ $tester->pelanggan->detil_alamat }}</td>
                <td><strong>Tanggal Surat Jalan</strong></td>
                <td>{{ date('d-m-Y', strtotime($tester->created_at)) }}</td>
            </tr>
            <tr>
                <td><strong>No. Telp</strong></td>
                <td>{{ $tester->pelanggan->no_handphone }}</td>
                <td><strong>No. tester</strong></td>
                <td>{{ $tester->nomer_permintaan_tester }}</td>
            </tr>
            <tr>
                <td><strong>Ekspedisi</strong></td>
                <td>{{ $tester->ekspedisi }}</td>
                <td><strong>Tanggal tester</strong></td>
                <td>{{ date('d-m-Y',strtotime($tester->tanggal)) }}</td>
            </tr>
            <tr>
                <td><strong>No. Resi</strong></td>
                <td>-</td>
                <td></td>
                <td></td>
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
                @foreach($tester_rinci as $rinci)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rinci->produk->kode_barang }}</td>
                    <td>{{ $rinci->produk->nama_barang }}</td>
                    <td>{{ $rinci->kuantitas }}</td>
                    <td>{{ $rinci->produk->satuan_barang }}</td>
                    @php $total_qty += $rinci->kuantitas @endphp
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
                <td style="width: 15%;"></td>
                <td style="vertical-align: text-top;" rowspan="2">
                    Note :
                    <p>{{ $tester->alamat_penerima }}</p>
                </td>
            </tr>
            <tr style="text-align: center;">
                <td>WAREHOUSE</td>
                <td>ACCOUNTING</td>
                <td>EKSPEDISI</td>
                <td>CUSTOMER</td>
            </tr>
        </table>

    </div>

</body>

</html>