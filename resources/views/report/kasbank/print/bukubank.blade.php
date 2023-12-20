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
    @php
        $i = 0; //counter
        $len = count($coa);
    @endphp
    @foreach ($coa as $c)
        <div class="text-center">
            <h3 style="text-transform: uppercase" >PT Mires Mahisa Globalindo</h3>
            <h2 style="color: #800000; margin-top: -10px" >Buku Bank</h2>
            @php
                $totalKredit = 0;
                $totalDebit = 0;
                $saldo = 0;
            @endphp
            <h4 style="margin-top: -10px">Dari {{ date('d M Y', strtotime($start)) }} ke {{ date('d M Y', strtotime($end)) }} </h4>
            
            <table style="width: 100%; margin: auto;">
                <thead>
                    <tr>
                        <th style="border: none; text-align: left;" >No. Akun</th>
                        <th style="border: none; text-align: left; padding-left:25px">: {{ $c->nomer_coa }}</th>
                    </tr>
                    <tr>
                        <th style="border: none; text-align: left;">Nama Akun</th>
                        <th style="border: none; text-align: left; padding-left:25px">: {{ $c->nama_coa }}</th>
                    </tr>
                    <tr>
                        <th style="width: 13%">Tanggal</th>
                        <th>No. Sumber</th>
                        <th style="width: 25%">Keterangan</th>
                        <th>Penambahan</th>
                        <th>Pengurangan</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Saldo Awal</td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right; padding-right:5px; {{ $c->saldo_awal < 0 ? "color:red" : "" }}">{{ rupiahReport($c->saldo_awal) }}</td>
                        @php
                            $saldo += $c->saldo_awal;
                        @endphp
                    </tr>
                    @foreach ($rinci as $item)
                        @if($item->coa_id == $c->coa_id)
                            <tr>
                                <td>{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                                <td>{{ $item->nomer }}</td>
                                <td>{{ $item->memo }}</td>
                                <td style="text-align: right; padding-right:5px; {{ $item->debit < 0 ? "color:red" : "" }}">{{ rupiahReport($item->debit) }}</td>
                                <td style="text-align: right; padding-right:5px; {{ $item->kredit < 0 ? "color:red" : "" }}">{{ rupiahReport($item->kredit) }}</td>
                                @php
                                    $saldo += $item->debit - $item->kredit;
                                    $totalDebit += $item->debit;
                                    $totalKredit += $item->kredit;
                                @endphp
                                <td style="text-align: right; padding-right:5px; {{ $saldo < 0 ? "color:red" : "" }}">
                                    {{ rupiahReport($saldo) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="border-top: 1px solid black; text-align:right; {{ $totalDebit < 0 ? "color:red;" : "color: #00028c;" }} font-weight: bold; padding-right:5px">{{ rupiahReport($totalDebit) }}</td>
                            <td style="border-top: 1px solid black; text-align:right; {{ $totalKredit < 0 ? "color:red;" : "color: #00028c;" }} font-weight: bold; padding-right:5px">{{ rupiahReport($totalKredit) }}</td>
                        </tr>
                    @php
                        $saldo = 0;
                        $totalDebit = 0;
                        $totalKredit = 0;
                    @endphp
                </tbody>
            </table>
            
        </div>
        @if($i < $len - 1)
            @php $i++; @endphp
            <div class="page_break"></div> 
        @endif
    @endforeach
   

</body>
</html>