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
    </style>
</head>
<body class="A4" >
    <section class="sheet padding-10mm">
        <div class="text-center">
            <h3 style="text-transform: uppercase" >PT Mires Mahisa Globalindo</h3>
            <h2 style="color: #800000; margin-top: -10px" >Buku Besar - Rinci</h2>
            @php
                $debit = 0;
                $kredit = 0;
            @endphp
            <h4 style="margin-top: -10px">Dari {{ date('d M Y', strtotime($start)) }} ke {{ date('d M Y', strtotime($end)) }} </h4>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: start">Tanggal</th>
                        <th>Sumber</th>
                        <th>No. Sumber</th>
                        <th>Keterangan</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($akun as $row)
                        <tr>
                            <td style="font-weight: bold;">{{ $row->nomer_coa }}</td>
                            <td style="font-weight: bold; padding-left: 15px">{{ $row->nama_coa }}</td>
                            <td style="font-weight: bold; text-align: right">{{ $row->tipeCoa->tipecoa }}</td>
                            <td style="font-weight: bold; text-align: center">{{ rupiahReport($row->saldo_awal) }} Dr</td>
                            <td colspan="3"></td>
                        </tr>
                        @foreach ($rinci as $item)
                            @if($item->coa_id == $row->id)
                                <tr>
                                    <td style="text-align: start; padding-left:5px">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                                    <td>{{ $item->sumber == "PMT" ? "Payment" : ($item->sumber == "DPT" ? "Deposit" : "Jurnal Umum") }}</td>
                                    <td>{{ $item->nomer }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td  style="text-align: right">{{ rupiahReport($item->debit) }}</td>
                                    <td  style="text-align: right">{{ rupiahReport($item->kredit) }}</td>
                                    <td  style="text-align: right">{{ $item->debit != 0 ? "(Dr)" : "(Cr)" }} {{ rupiahReport(str_replace('-','', $item->balance)) }}</td>
                                </tr>
                                @php
                                    $debit += $item->debit;
                                    $kredit += $item->kredit;
                                @endphp
                            @endif
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="border-top: 1px solid black; text-align:right; color: #00028c; font-weight: bold;">{{ rupiahReport($debit) }}</td>
                            <td style="border-top: 1px solid black; text-align:right; color: #00028c; font-weight: bold;">{{ rupiahReport($kredit) }}</td>
                        </tr>
                        @php
                            $debit = 0;
                            $kredit = 0;
                        @endphp
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>