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
            <h2 style="color: #800000; margin-top: -10px" >Arus Kas per Akun</h2>
            @php
                $totalKredit = 0;
                $totalDebit = 0;
                $grandTotal = 0;
            @endphp
            <h4 style="margin-top: -10px">Dari {{ date('d M Y', strtotime($start)) }} ke {{ date('d M Y', strtotime($end)) }} </h4>
            <table style="width: 80%; margin: auto">
                <thead>
                    <tr>
                        <th>Tipe Sumber</th>
                        <th style="width: 10%">Tanggal</th>
                        <th>Jumlah</th>
                        <th style="width: 25%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coa as $c)
                        <tr>
                            <td colspan="4" style="font-weight: bold;">{{ $c->nama_coa }}</td>
                        </tr>
                        @foreach ($coaRinci as $cr)
                            @if($c->coa_id == $cr->coa_id_bank)
                                <tr>
                                    <td colspan="4" style="font-weight: bold; padding-left:10px">{{ $cr->nama_coa }}</td>
                                </tr>
                                @foreach ($rinci as $item)
                                    @if ($item->coa_id_rinci == $cr->coa_id_rinci && $c->coa_id == $item->coa_id_bank)
                                        <tr>
                                            <td style="padding-left:20px">{{ $item->sumber == "PMT" ? "Pembayaran" : ($item->sumber == "DPT" ? "Penerimaan" : "Bukti Jurnal") }}</td>
                                            <td style="text-align: start; padding-left:5px">{{ date('d M Y', strtotime($item->tanggal)) }}</td>

                                            @if($item->sumber == "JV")
                                                @if(!is_null($item->debit))
                                                    @php
                                                        $totalKredit += $item->debit;
                                                    @endphp
                                                    <td style="color: red; text-align: right; padding-right:5px">{{ rupiahReport('-'.$item->debit) }}</td>
                                                @else
                                                    @php
                                                        $totalDebit += $item->kredit;
                                                    @endphp
                                                    <td style="text-align: right; padding-right:5px">{{ rupiahReport($item->kredit) }}</td>
                                                @endif
                                            @else
                                                @if(!is_null($item->debit))
                                                    @php
                                                        $totalDebit += $item->debit;
                                                    @endphp
                                                    <td style="text-align: right; padding-right:5px">{{ rupiahReport($item->debit) }}</td>
                                                @else
                                                    @php
                                                        $totalKredit += $item->kredit;
                                                    @endphp
                                                    <td style="color: red; text-align: right; padding-right:5px">{{ rupiahReport('-'.$item->kredit) }}</td>
                                                @endif
                                            @endif
                                           
                                            <td>{{ $item->memo }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    @if($totalDebit != 0)
                                        <td style="border-top: 1px solid black; text-align:right; color: #00028c; font-weight: bold; padding-right:5px">{{ rupiahReport($totalDebit) }}</td>
                                    @else
                                        <td style="border-top: 1px solid black; text-align:right; color: red; font-weight: bold; padding-right:5px">{{ rupiahReport('-'.$totalKredit) }}</td>      
                                    @endif  
                                </tr>
                                @php
                                    $grandTotal += $totalDebit - $totalKredit;
                                    $totalKredit = 0;
                                    $totalDebit = 0;
                                @endphp
                            @endif
                        @endforeach
                        <tr>
                            <td style="color: #00028c; font-weight: bold">Total dari {{ $c->nama_coa }}</td>
                            <td></td>
                            @if($grandTotal >= 0)
                                <td style="border-top: 1px solid black; text-align:right; color: #00028c; font-weight: bold; padding-right:5px">{{ rupiahReport($grandTotal) }}</td>
                            @else
                                <td style="border-top: 1px solid black; text-align:right; color: red; font-weight: bold; padding-right:5px">{{ rupiahReport($grandTotal) }}</td>
                            @endif
                            
                        </tr>
                        @php
                            $grandTotal = 0;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>