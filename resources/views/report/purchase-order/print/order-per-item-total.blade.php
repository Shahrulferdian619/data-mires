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
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Pembelian Per Barang - Harga</h2>
            @php
                $grandTotal = 0;
            @endphp
            @php
                $form = date_create($form);
                $form = date_format($form, 'd M Y');
                $to = date_create($to);
                $to = date_format($to, 'd M Y');
            @endphp
            <h4 style="text-transform: uppercase;margin-top:-10px;" > Dari {{ $form }} Ke {{ $to }} </h4>
            <table style="width: 100%;margin-top:20px" >
                @foreach ($data as $item)
                    <tr>
                        <th colspan="7" style="border:none; text-align: start" >{{ $item['supplier_name'] }}</th  >
                    </tr>
                    @foreach ($item['order'] as $order)
                    @php
                        $grandTotal = 0;
                    @endphp
                        <tr>
                            <td colspan="6" style="text-align: start;font-weight: bold;" >{{ $order['faktur_number'] }}</td>
                            <td style="text-align: right;font-weight: bold;" >{{ $order['date'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;font-weight: bold;text-transform:uppercase;font-size:8px;" >no</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >kode barang</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >nama barang</td>
                            <td style="text-align: center;font-weight: bold;text-transform:uppercase;font-size:8px;" >kuantitas barang</td>
                            <td style="text-align: center;font-weight: bold;text-transform:uppercase;font-size:8px;" >diskon barang</td>
                            <td></td>
                            <td style="text-align: right;font-weight: bold;text-transform:uppercase;font-size:8px;" >harga barang</td>
                        </tr>
                        <tr>
                            <td colspan="7" ></td>
                        </tr>
                        @foreach ($order['detail'] as $key => $detail)
                            <tr>
                                <td style="text-align: center;" > {{ $key + 1 }} </td>
                                <td style="text-align: left;" > {{ $detail['item_code'] }} </td>
                                <td style="text-align: left;" > {{ $detail['item_name'] }} </td>
                                <td style="text-align: center;" > {{ $detail['item_qty'] }} </td>
                                <td style="text-align: center;" > {{ $detail['item_dsc'] }} </td>
                                <td>Rp.</td>
                                <td style="text-align: right;" > {{ number_format($detail['item_price'], 0, ',', '.') }} </td>
                                @php
                                    $grandTotal += ($detail['item_price'] - ($detail['item_price'] * $detail['item_dsc'] / 100)) * $detail['item_qty'];
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" ></td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right;font-weight: bold;text-transform:uppercase;" >Total:</td>
                            <td style="font-weight: bold;" >Rp.</td>
                            <td style="text-align: right;font-weight: bold;text-transform:uppercase;" > {{ number_format($grandTotal, 0, ',', '.') }} </td>
                        </tr>
                        <tr>
                            <td colspan="7" ></td>
                        </tr>
                        <tr>
                            <td colspan="7" ></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" >
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" ></td>
                    </tr>
                    <tr>
                        <td colspan="7" ></td>
                    </tr>
                @endforeach
                {{-- <thead>
                    <tr>
                        <th style="border:none; text-align: start" >Kode Barang</th>
                        <th style="border:none; text-align: start" >Nama Barang</th>
                        <th style="border:none; text-align: start" >Total Pembelian Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td style="text-align: start" >{{ $item['kode_barang'] }}</td>
                            <td style="text-align: start" >{{ $item['nama_barang'] }}</td>
                            <td style="text-align: start" >Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody> --}}
            </table>
        </div>
    </section>
</body>
</html>