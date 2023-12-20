<!DOCTYPE html>
<html lang="en">

<head>
    <title>BKK : {{ $pembayaran->nomer }}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <style>
        .table td {
            font-size: 9px;
        }

        .table th {
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div style="width:100%; padding:10px; border: 1px solid black">
        <h5 class="text-center">PT. Mires Mahisa Globalindo</h5>

        <table class="table table-bordered border-dark table-sm">
            <tr>
                <td>Bukti Kas Keluar : {{ $pembayaran->bank->nama_coa }}</td>
                <td>Nomer : {{ $pembayaran->nomer }}</td>
                <td>Tanggal : {{ date('d/m/Y',strtotime($pembayaran->tanggal)) }}</td>
            </tr>
        </table>

        <table class="table table-bordered border-dark mt-4 table-sm">
            <thead>
                <tr>
                    <th>Akun</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @php $total_nominal = 0 @endphp
                @foreach($pembayaran->rincianAkun as $rincian)
                <tr>
                    <td>{{ $rincian->coa->nama_coa }}</td>
                    <td>{{ $rincian->catatan }}</td>
                    <td style="text-align: right;">{{ number_format($rincian->nominal,2) }}</td>
                </tr>
                @php $total_nominal += $rincian->nominal @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right;">Jumlah</td>
                    <td style="text-align: right;">{{ number_format($total_nominal,2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="col-6">
            <table class="table table-bordered border-dark mt-4 table-sm">
                <tr>
                    <td>
                        Akunting
                    </td>
                    <td>
                        Kasir
                    </td>
                </tr>
                <tr>
                    <td>
                        <br><br><br><br>
                    </td>
                    <td>
                        <br><br><br><br>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>