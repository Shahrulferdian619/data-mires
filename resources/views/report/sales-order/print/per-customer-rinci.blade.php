<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css"> --}}
    <title>PT Mires Globalindo</title>
    <style>
        @media print{@page {size: landscape}}
        @page { 
            size: landscape;
        }
        *{
            font-family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
            box-sizing: border-box;
            font-size: 10pt;
        }
        .text-center{
            text-align: center;
        }

        th{
            border-bottom: 1px solid #000; 
            width: 20%; 
            padding-bottom: 5px;
            color: #00028c;
        }
        td{
            font-size: 8pt;
        }
        .page_break { page-break-before: always; }
    </style>
</head>
<body class="A4" >
    <section class="sheet padding-10mm">
        <div class="text-center">
            <h3 style="text-transform: uppercase" >PT Mires Mahisa Globalindo</h3>
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Penjualan Per Pelanggan</h2>
            @php
                $grandTotal = 0;
                $from = date_create($from);
                $from = date_format($from, 'd M Y');
                $to = date_create($to);
                $to = date_format($to, 'd M Y');
            @endphp
            <h4 style="text-transform: uppercase;margin-top:-10px;" > Dari {{ $from }} sampai {{ $to }} </h4>
            @foreach ($data as $value)
                <h6 style="text-align: start;margin-bottom: 3px;" > {{ $value['nama_pelanggan'] }} </h6>
                <table style="width: 100%;margin: auto;" >
                    <thead>
                        <tr>
                            <th style="border:none;text-align:left;" >Nomer Invoice</th>
                            <th style="border:none;text-align:left;" >Tanggal</th>
                            <th style="border:none;text-align:left;" >Total Harga Pembelian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($value['data'] as $item)
                            <tr>
                                <td style="text-align: start">{{ $item['nomer_invoice'] }}</td>
                                <td style="text-align: start">{{ $item['tanggal'] }}</td>
                                <td style="text-align: start">Rp. {{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr style="border: none;border-top: 1px solid black" >
                <div class="page-break"></div>
            @endforeach
        </div>
    </section>
</body>
</html>