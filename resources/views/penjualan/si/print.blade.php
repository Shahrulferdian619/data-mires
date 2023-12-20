<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #table-head tr td {
            padding: 5px;
            font-size: 12px;
            text-align: center;
        }

        #table3 tr td,
        #table3 tr th {
            border: 1px solid black;
            font-size: 11px;
            padding: 2px;
        }
        #table3 .rinci td {
            text-align: center;
        }

        #table2 tr td {
            font-size: 10px;
        }

        .table tr th,
        .table tr td {
            border: 1px solid black;
            padding: 2px;
            font-size: 9px;
            text-align: center;
        }

        .table tr th {
            background-color: black;
            color: white;
        }

        body {
            font-family: Arial;
        }

        p {
            font-family: Courier, monospace;
        }

        div {
            font-family: Duru Sans, Verdana, sans-serif;
        }

        .card {
            font-size: 12px;
            border: 1px solid black;
            width: 150px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="" style="width:100%; padding:10px; border: 1px solid black">
        <table cellspacing="0" cellpadding="0" id="table-head" style="width:100%; margin-bottom: 10px">
            <tr>
                <td class="head" style="background-color: black; color: white; font-size: 15px" colspan="2"><strong>INVOICE</strong></td>
            </tr>
            <tr>
                <td width="30%">
                    <img style="width:90px" src="http://altamasoft.tech/img/logo.png">
                </td>
                <td style="text-align: left">
                    <strong>PT Mires Mahisa Globalindo</strong> <br>
                    JL. Raya menganti 27 C, FOREST MANSION, Clusster Blossom Hill B-08 <br>
                    Lidah Wetan, Lakarsantri, Surabaya 60211 - Indonesia <br>
                    031 7513 0559
                </td>
            </tr>
        </table>

        <table id="table2" style="border:none; width:100%; margin-bottom: 5px">
            <tr>
                <td><strong>Bill To: </strong></td>
            </tr>
            <tr>
                <td style="width: 60%">
                    <p>{{ $si->pelanggan->nama_pelanggan }}</p>
                    <p>{{ $si->pelanggan->detail_alamat }}</p>
                    <p>{{ $si->pelanggan->handphone_pelanggan }}</p>
                </td>
                <td>
                    <table class="table" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <th style="width: 30%">Date</th>
                            <th style="width: 30%;">Invoice</th>
                            <th style="width: 30%;">No. SO</th>
                        </tr>
                        <tr>
                            <td>{{ date('d-M-Y', strtotime($si->tanggal)) }}</td>
                            <td>#{{ $si->nomer_invoice }}</td>
                            <td>{{ $si->so->so_nomer }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table id="table3" style="width:100%; margin-bottom: 10px" cellspacing="0" cellpadding="0">
            <tr>
                <th>Description</th>
                <th>Code</th>
                <th>Price</th>
                <th>Dsc (%)</th>
                <th>Dsc (Rp)</th>
                <th>Qty</th>
                <th>Total <small>(Termasuk Potongan)</small> </th>
            </tr>
            <?php $grandtotal = 0; ?>
            @foreach($si_rinci as $rinci)
            <tr class="rinci">
                <td style="text-align: left;">{{ $rinci->barang->nama_barang }}</td>
                <td style="text-align: left;">{{ $rinci->barang->kode_barang }}</td>
                <td style="text-align: left;">{{ number_format($rinci->harga) }}</td>
                <td>{{ $rinci->dsc }}</td>
                <td>{{ number_format($rinci->diskon_nominal) }}</td>
                <td>{{ $rinci->qty }}</td>
                <?php $total = $rinci->qty * $rinci->harga; ?>
                <?php $subtotal = $total - ($total * $rinci->dsc / 100) - $rinci->diskon_nominal - $rinci->potongan_admin + $rinci->cashback_ongkir ?>
                <td>{{ number_format($subtotal) }}</td>
                <?php $grandtotal += $subtotal; ?>
            </tr>
            @endforeach
            <tr class="rinci">
                <td colspan="6" style="text-align: right;"> <strong>Grand Total</strong> </td>
                <td>{{ number_format($grandtotal) }}</td>
            </tr>
        </table>

        <table style="width: 100%; font-size: 12px">
            <tr>
                <td>
                    <strong>Term Payment : 30 Hari</strong> <br>
                    <strong>Transfer Via :</strong>
                    <p>BCA: <br> 8292-4377-99</p>
                    <p>a/n PT. MIRES MAHISA GLOBALINDO</p>
                </td>
                <td style="text-align: center">
                    <strong>Hormat Kami,</strong>
                    <br><br><br><br>
                    <p>PT. MIRES MAHISA <br> GLOBALINDO</p>
                </td>
            </tr>
        </table>

        <div style="font-size:12px;">Putih : Customer &nbsp; &nbsp; Merah : Finance &nbsp; &nbsp; Kuning : Admin &nbsp; &nbsp; Hijau : Admin</div>
    </div>
</body>

</html>