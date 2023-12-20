<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #table{
            width: 100%;
            border-collapse:collapse;
        }
        #table tr td,
        #table tr th{
            border: 2px solid black;
            font-size:12px;
            text-align: center;
            padding: 2px;
        }

        body { font-family: Arial; }
        p { font-family: Courier, monospace; }
        div { font-family: Duru Sans, Verdana, sans-serif; }
        .card{
            font-size:12px;
            border: 1px solid black;
            width: 150px;
            text-align:center;
        }
    </style>
</head>
<body>
    <table id="table" cellpadding="0" cellspacing="0">
        <tr>
            <td rowspan="2">Nomor Perkiraan</td>
            <td rowspan="2">Nama Yang <br> Bertransaksi</td>
            <td rowspan="2"><strong>(BKK)Bukti Kas Keluaran</strong> </td>
            <td style="text-align:right">Nomor</td>
            <td style="text-align:left">BKK MMG 2203003</td>
        </tr>
        <tr>
            <td style="text-align:right">Tanggal</td>
            <td style="text-align:left">2 Mar 2022</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2">Uraian</td>
            <td>JUMLAH</td>
        </tr>
        @php $no = 0; @endphp
        @foreach($bukuBankRinci as $row)
        <tr>
            <td>{{ $no = $no + 1 }}</td>
            <td></td>
            <td colspan="2">{{ $row->memo }}</td>
            <td>{{ number_format($row->nominal) }}</td>
        </tr>
        @endforeach
        @php $sisa_row = 13 - $no @endphp
        @for($i = 0; $i < $sisa_row; $i++)
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td colspan="2"</td>
            <td></td>
        </tr>
        @endfor

    </table>
</body>
</html>