<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Invoice : {{ $invoice->nomer_invoice_penjualan }}</title>
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
            font-family: 'Times New Roman', Times, serif;
        }

        p {
            font-family: 'Times New Roman', Times, serif;
        }

        div {
            font-family: 'Times New Roman', Times, serif;
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
                    <img style="width:90px" src="http://miresmahisa.com/logo/logo_invoice_penjualan.png">
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
                    <p>{{ $invoice->pelanggan->nama_pelanggan }}</p>
                    <p>{{ $invoice->pelanggan->detil_alamat }}</p>
                    <p>{{ $invoice->pelanggan->no_handphone }}</p>
                </td>
                <td>
                    <table class="table" cellspacing="0" cellpadding="0" style="width: 100%;">
                        <tr>
                            <th style="width: 30%">Date</th>
                            <th style="width: 30%;">Invoice</th>
                            <th style="width: 30%;">No. Ref</th>
                        </tr>
                        <tr>
                            <td>{{ date('d-M-Y', strtotime($invoice->tanggal)) }}</td>
                            <td>#{{ $invoice->nomer_invoice_penjualan }}</td>
                            <td>@if($invoice->pesanan == ''){{ $invoice->nomer_ref }}@else {{ $invoice->pesanan->nomer_pesanan_penjualan }}@endif</td>
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
            @foreach($invoice->rincian as $rinci)
            <tr class="rinci">
                <td style="text-align: left;">{{ $rinci->produk->nama_barang }}</td>
                <td style="text-align: left;">{{ $rinci->produk->kode_barang }}</td>
                <td style="text-align: left;">{{ number_format($rinci->harga_produk) }}</td>
                <td>{{ $rinci->diskon_persen }}</td>
                <td>{{ number_format($rinci->diskon_nominal) }}</td>
                <td>{{ $rinci->kuantitas }}</td>
                <td style="text-align: right;">{{ number_format($rinci->subtotal) }}</td>
            </tr>
            @endforeach
            <tr class="rinci">
                <td colspan="6" style="text-align: right;"> <strong>Diskon</strong> </td>
                <td style="text-align: right;">{{ number_format($invoice->diskon_global) }}</td>
            </tr>
            <tr class="rinci">
                <td colspan="6" style="text-align: right;"> <strong>Grand Total</strong> </td>
                <td style="text-align: right;">{{ number_format($invoice->grandtotal_setelah_diskon) }}</td>
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