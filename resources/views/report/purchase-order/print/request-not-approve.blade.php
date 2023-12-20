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
            <h2 style="text-transform: uppercase;color: #800000;margin-top:-10px" >Permintaan Pembelian Belum Disetujui</h2>
            
            <h4 style="text-transform: uppercase" > Tanggal {{ date('d M Y') }} </h4>
            <table style="width: 100%;margin-top:20px" >
                <thead>
                    <tr>
                        <th style="border:none; text-align: start" >Nomor Permintaan</th>
                        <th style="border:none; text-align: start" >Tanggal</th>
                        <th style="border:none; text-align: start" >Keterangan</th>
                        <th style="border:none; text-align: start" >Direktur</th>
                        {{-- <th style="border-bottom: 1px solid #000; width: 40%; padding-bottom: 5px;" >Total Kuantitas Barang</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td style="text-align: start" >{{ $item->nomer_pmtpembelian }}</td>
                            @php
                                $newDate = date_create($item->tanggal);
                                $newDate = date_format($newDate, 'd M Y');
                            @endphp
                            <td style="text-align: start" >{{ $newDate }}</td>
                            <td style="text-align: start" >{{ $item->keterangan ?? '-' }}</td>
                            <td style="text-align: start" >{{ $item->approve_direktur == 0 ? 'Belum disetujui' : 'Sudah disetujui' }}</td>
                            {{-- <td style="text-align: center" >{{ number_format($item['qty'], 0, ',', '.') }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>