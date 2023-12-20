<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <title>PT Mires Globalindo</title>
    <style>
        *{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .text-center{
            text-align: center;
        }
    </style>
</head>
<body class="A4" >
    <section class="sheet padding-10mm">
        <div class="text-center">
            <h3 style="text-transform: uppercase" >PT Mires Mahisa Globalindo</h3>
            <h2 style="text-transform: uppercase;color: rgba(2, 218, 2, 0.952);" >Pembelian Per Barang - Harga</h2>
            @php
                $grandTotal = 0;
            @endphp
            <h4 style="text-transform: uppercase" > Tanggal {{ date('d M Y') }} </h4>
            <table style="width: 100%;margin-top:20px" >
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid #000; width: 20%; padding-bottom: 5px;text-align: start" >Kode Barang</th>
                        <th style="border-bottom: 1px solid #000; width: 30%; padding-bottom: 5px;" >Nama Barang</th>
                        <th style="border-bottom: 1px solid #000; width: 50%; padding-bottom: 5px;" >Total Pembelian Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td style="text-align: start" >{{ $item['kode_barang'] }}</td>
                            <td style="text-align: center" >{{ $item['nama_barang'] }}</td>
                            <td style="text-align: end" >Rp. {{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>