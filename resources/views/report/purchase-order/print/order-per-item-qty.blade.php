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
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Pembelian Per Barang - Kuantitas</h2>
            @php
                $grandTotal = 0;
            @endphp
            @php
                $form = date_create($form);
                $form = date_format($form, 'd M Y');
                $to = date_create($to);
                $to = date_format($to, 'd M Y');
                $grandTotal = 0;
            @endphp
            <h4 style="text-transform: uppercase;margin-top:-10px;" > Dari {{ $form }} Ke {{ $to }} </h4>
            <table style="width: 100%;margin-top:20px" >
                <thead>
                    <tr>
                        <th style="border:none;text-align: start" >Kode Barang</th>
                        <th style="border:none;text-align: start" >Nama Barang</th>
                        <th style="border:none;text-align: start" >Total Kuantitas Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td style="text-align: start" >{{ $item['kode_barang'] }}</td>
                            <td style="text-align: start" >{{ $item['nama_barang'] }}</td>
                            <td style="text-align: start" >{{ number_format($item['qty'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>