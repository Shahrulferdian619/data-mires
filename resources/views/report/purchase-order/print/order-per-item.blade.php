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
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Pembelian Per Barang</h2>
            @php
                $form = date_create($form);
                $form = date_format($form, 'd M Y');
                $to = date_create($to);
                $to = date_format($to, 'd M Y');
                $grandTotal = 0;
            @endphp
            <h4 style="text-transform: uppercase;margin-top:-10px;" > Dari {{ $form }} Ke {{ $to }} </h4>
            <table style="width: 100%;margin: auto;" >
                <thead>
                    <tr>
                        <th style="border:none;text-align:left;" >Kode Barang</th>
                        <th style="border:none;text-align:left;" >Nama Barang</th>
                        @if ($column == 'qty' || $column == 'all')
                            <th style="border:none;text-align:center;" >Kuantitas Barang</th>
                        @endif
                        @if ($column == 'price' || $column == 'all')
                            <th style="border:none;text-align:left;width:25px" ></th>
                            <th style="border:none;text-align:center;" >Harga Barang</th>
                        @endif
                        {{-- <th style="border-bottom: 1px solid #000; width: 40%; padding-bottom: 5px;" >Total Kuantitas Barang</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td style="text-align: left" >{{ $item['item_code'] }}</td>
                            <td style="text-align: left" >{{ $item['item_name'] }}</td>
                            @if ($column == 'qty' || $column == 'all')
                                <td style="text-align: center" >{{ number_format($item['item_quantity'], 0, ',', '.') }}</td>
                            @endif
                            @if ($column == 'price' || $column == 'all')
                                <td style="border:none;text-align:left;">Rp.</td>
                                <td style="text-align: right" >{{ number_format($item['item_price'], 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>