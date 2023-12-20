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
            <h3 style="text-transform: uppercase" >{{ $vendor->nama_supplier }}</h3>
            <h2 style="text-transform: uppercase;color: rgba(2, 218, 2, 0.952);" >Pembelian Per Supplier</h2>
            @php
                $form = date_create($form);
                $form = date_format($form, 'd M Y');
                $to = date_create($to);
                $to = date_format($to, 'd M Y');
                $grandTotal = 0;
            @endphp
            <h4 style="text-transform: uppercase" > Dari {{ $form }} Ke {{ $to }} </h4>
            <table style="width: 100%;margin-top:20px" >
                <thead>
                    <tr>
                        <th style="border-bottom: 1px solid #000; width: 35%; padding-bottom: 5px;text-align: start" >Nomor Order</th>
                        <th style="border-bottom: 1px solid #000; width: 15%; padding-bottom: 5px;" >Tanggal</th>
                        <th style="border-bottom: 1px solid #000; width: 15%; padding-bottom: 5px;" >Termin</th>
                        <th style="border-bottom: 1px solid #000; width: 25%; padding-bottom: 5px;" >Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order as $item)
                        <tr>
                            <td style="text-align: start" >{{ $item->nomer_fakturpembelian }}</td>
                            <td style="text-align: center" >{{ $item->tanggal }}</td>
                            <td style="text-align: center" >{{ $item->termin }} Hari</td>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($item->rinci as $value)
                                @php
                                   $total += $value->qty * $value->harga; 
                                @endphp
                            @endforeach
                            <td style="text-align: end" >Rp. {{ number_format($total, 2, ',', '.') }}</td>
                            @php
                                $grandTotal += $total;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot >
                    <tr>
                        <td colspan="3" style="text-align: end;border-top: 1px solid #000;" >Diketahui Total</td>
                        <td style="text-align: end;border-top: 1px solid #000;" > Rp. {{ number_format($grandTotal, 2, ',', '.') }} </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</body>
</html>