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
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Laporan Penjualan Per Pelanggan</h2>
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
                        <th colspan="14" style="border:none; text-align: left" >{{ $item['customer_name'] }}</th  >
                    </tr>
                    @foreach ($item['sales'] as $sales)
                        @php
                            $grandTotal = 0;
                        @endphp
                        <tr>
                            <td colspan="14" ></td>
                        </tr>
                        <tr>
                            <td colspan="10" style="text-align: left;font-weight: bold;" >{{ $sales['invoice_number'] }}</td>
                            <td colspan="4" style="text-align: right;font-weight: bold;" >{{ $sales['date'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="14" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >no</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >nama barang</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >jumlah barang</td>
                            <td></td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >harga</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >diskon persentase</td>
                            <td></td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >diskon nominal</td>
                            <td></td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >diskon admin</td>
                            <td></td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:6px;" >cashback ongkir</td>
                            <td></td>
                            <td style="text-align: right;font-weight: bold;text-transform:uppercase;font-size:6px;" >subtotal harga</td>
                        </tr>
                        <tr>
                            <td colspan="14" ></td>
                        </tr>
                        @foreach ($sales['details'] as $key => $detail)

                            @php
                                $subtotal = 0;
                                $subtotal += ($detail['item_price'] - ($detail['item_price'] * $detail['item_discount'] / 100));
                                $subtotal = $subtotal - $detail['item_discount_nominal'] - $detail['item_discount_admin'] + $detail['item_cashback'];
                                $grandTotal += $subtotal;
                            @endphp

                            <tr>
                                <td style="text-align: left;font-size:6px;" > {{ $key + 1 }} </td>
                                <td style="text-align: left;font-size:6px;" > {{ $detail['item_name'] }} </td>
                                <td style="text-align: center;font-size:6px;" > {{ number_format($detail['item_quantity'], 0, ',', '.') }} </td>
                                <td style="text-align: left;font-size:6px;" >Rp.</td>
                                <td style="text-align: right;font-size:6px;" > {{ number_format($detail['item_price'], 0, ',', '.') }} </td>
                                <td style="text-align: right;font-size:6px;" > {{ number_format($detail['item_discount'], 0, ',', '.') }}% </td>
                                <td style="text-align: left;font-size:6px;" >Rp.</td>
                                <td style="text-align: right;font-size:6px;" > {{ number_format($detail['item_discount_nominal'], 0, ',', '.') }} </td>
                                <td style="text-align: left;font-size:6px;" >Rp.</td>
                                <td style="text-align: right;font-size:6px;" > {{ number_format($detail['item_discount_admin'], 0, ',', '.') }} </td>
                                <td style="text-align: left;font-size:6px;" >Rp.</td>
                                <td style="text-align: right;font-size:6px;" > {{ number_format($detail['item_cashback'], 0, ',', '.') }} </td>
                                <td style="text-align: left;font-size:6px;" >Rp.</td>
                                <td style="text-align: right;font-size:6px;" > {{ number_format($subtotal, 0, ',', '.') }} </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="10" ></td>
                            <td colspan="4" ><hr style="border:none;border-top:1px solid gray;height: 1px;shadow:none;" ></td>
                        </tr>
                        <tr>
                            <td colspan="10" ></td>
                            <td colspan="2" style="text-align: right;font-size:6px;font-weight: bold;text-transform:uppercase;" > Total : </td>
                            <td style="text-align: left;font-size:6px;font-weight: bold;text-transform:uppercase;" >Rp.</td>
                            <td style="text-align: right;font-size:6px;font-weight: bold;text-transform:uppercase;" > {{ number_format($grandTotal, 0, ',', '.') }} </td>
                        </tr>
                        <tr>
                            <td colspan="10" ></td>
                            <td colspan="2" style="text-align: right;font-size:6px;font-weight: bold;text-transform:uppercase;" > Status Pembayaran : </td>
                            <td colspan="2" style="text-align: right;font-size:6px;font-weight: bold;text-transform:uppercase;" >
                                @if ($sales['status_payment'] == 0)
                                    Belum dibayar
                                @elseif($sales['status_payment'] == 1)
                                    Dibayar sebagian
                                @elseif($sales['status_payment'] == 2)
                                    Sudah lunas
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="14" ><hr style="border:none;border-top:1px solid gray;height: 1px;shadow:none;" ></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="14" ></td>
                    </tr>
                    <tr>
                        <td colspan="14" ></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
</body>
</html>