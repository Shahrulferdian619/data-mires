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
                        <th colspan="4" style="border:none; text-align: left" >{{ $item['supplier_name'] }}</th  >
                    </tr>
                    @foreach ($item['payments'] as $payment)
                    @php
                        $grandTotal = 0;
                    @endphp
                        <tr>
                            <td colspan="3" style="text-align: left;font-weight: bold;" >{{ $payment['payment_number'] }}</td>
                            <td style="text-align: right;font-weight: bold;" >{{ $payment['date'] }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Tagihan : </td>
                            <td colspan="3" style="text-align: right" >Rp. {{ number_format($payment['total_invoice'], 0, ',', '.') }} </td>
                        </tr>
                        <tr>
                            <td colspan="4" ><hr style="border:none;border-top:1px solid gray;height: 1px;shadow:none;" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;font-weight: bold;" >Detail Pembayaran</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >no</td>
                            <td style="text-align: left;font-weight: bold;text-transform:uppercase;font-size:8px;" >pembayaran</td>
                            <td></td>
                            <td style="text-align: right;font-weight: bold;text-transform:uppercase;font-size:8px;" >nominal pembayaran</td>
                        </tr>
                        <tr>
                            <td colspan="4" ></td>
                        </tr>
                        @foreach ($payment['payment_details'] as $key => $detail)
                            <tr>
                                <td style="text-align: left;" > {{ $key + 1 }} </td>
                                <td style="text-align: left;" > Pembayaran ke - {{ $key + 1 }} </td>
                                <td>Rp.</td>
                                <td style="text-align: right;" > {{ number_format($detail, 0, ',', '.') }} </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" ><hr style="border:none;border-top:1px solid gray;height: 1px;shadow:none;" ></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" ></td>
                    </tr>
                    <tr>
                        <td colspan="4" ></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
</body>
</html>