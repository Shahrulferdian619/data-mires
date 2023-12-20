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
            <h2 style="text-transform: uppercase;color: rgba(2, 218, 2, 0.952);" >Pembelian Per Biaya</h2>
            
            {{-- <h4 style="text-transform: uppercase" > Tanggal {{ date('d M Y') }} </h4> --}}
            <table style="width: 100%;margin-top:20px" >
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid #000; padding-bottom: 5px;text-align: start" >Nomor Order</th>
                        <th style="border-bottom: 1px solid #000; padding-bottom: 5px;" >Supplier</th>
                        <th style="border-bottom: 1px solid #000; padding-bottom: 5px;" >Tanggal</th>
                        <th style="border-bottom: 1px solid #000; padding-bottom: 5px;" >Jumlah Tagihan</th>
                        <th style="border-bottom: 1px solid #000; padding-bottom: 5px;" >Jumlah Yang Dibayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td style="text-align: start" >{{ $item['nomer_payment'] }}</td>
                            <td style="text-align: center" >{{ $item['vendor'] }}</td>
                            @php
                                $newDate = date_create($item['tanggal']);
                                $newDate = date_format($newDate, 'd M Y');
                            @endphp
                            <td style="text-align: center" >{{ $newDate }}</td>
                            <td style="text-align: center" >Rp. {{ number_format($item['jumlah_tagihan'], 0, ',', '.') }}</td>
                            <td style="text-align: center" >Rp. {{ number_format($item['jumlah_bayar'], 0, ',', '.') }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>