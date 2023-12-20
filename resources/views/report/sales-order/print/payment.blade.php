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
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Laporan Pembayaran</h2>
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
                        <th colspan="5" style="border:none; text-align: left" >{{ $item['customer_name'] }}</th  >
                    </tr>
                    @foreach ($item['customer_receipts'] as $customer_receipt)
                        <tr>
                            <td >Nomer Pembayaran</td>
                            <td colspan="4" > : {{ $customer_receipt['invoice_number'] }}</td>
                        </tr>
                        <tr>
                            <td >Status Pembayaran</td>
                            <td colspan="4" > : 
                                @if ($customer_receipt['status_payment'] == 0)
                                    Belum Lunas
                                @elseif($customer_receipt['status_payment'] == 1)
                                    Dibayar Sebagian
                                @elseif($customer_receipt['status_payment'] == 2)
                                    Sudah Lunas
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" ><hr style="border:none;border-top:1px solid gray;height: 1px;shadow:none;" ></td>
                        </tr>
                        <tr>
                            <td >Detail Pembayaran</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >no</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >nomer pembayaran</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >tanggal pembayaran</td>
                            <td></td>
                            <td style="text-align: right;font-weight: bold;text-transform:uppercase;font-size:8px;" >nominal pembayaran</td>
                        </tr>
                        <tr>
                            <td colspan="5" ></td>
                        </tr>
                        @foreach ($customer_receipt['detail_payment'] as $key => $detail)
                            <tr>
                                <td style="text-align: left;" > {{ $key + 1 }} </td>
                                <td style="text-align: left;" > {{ $detail['customer_receipt_number'] }} </td>
                                <td style="text-align: left;" > {{ $detail['date'] }} </td>
                                <td>Rp.</td>
                                <td style="text-align: right;" > {{ number_format($detail['payment'], 0, ',', '.') }} </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" ><hr style="border:none;border-top:1px solid gray;height: 1px;shadow:none;" ></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
</body>
</html>