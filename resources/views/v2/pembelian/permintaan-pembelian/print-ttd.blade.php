<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PR : {{ $permintaan->nomer_permintaan_pembelian }}</title>
    <style>
        #table th,
        #table td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <img style="height: 100px; margin-left: 30px;" src="http://www.miresmahisa.com/logo/kop-mires.png" alt="">
    <hr style="border: 1px solid black; margin-top: -5px;">
    <div style="width:100%; text-align: center; margin-bottom: 10px; margin-top: -20px"><strong style="font-size: 20px;">PURCHASE REQUEST</strong> </div>
    <hr style="border: 1px solid black; margin-top: -2px;">

    Nomer : {{ $permintaan->nomer_permintaan_pembelian }} <br>
    Tanggal : {{ date('d-m-Y', strtotime($permintaan->tanggal)) }} <br>

    <br>
    <small>Please Supply Following Item :</small>
    <table id="table" class="mt-2" style="font-size: 12px; font-weight: bold; width : 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Deskripsi item</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
                <th>Tgl. minta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan->rincianPermintaan as $rinci)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rinci->item->nama_barang }}</td>
                <td>{{ $rinci->deskripsi_item }}</td>
                <td>{{ $rinci->kuantitas }}</td>
                <td>{{ $rinci->item->satuan_barang }}</td>
                <td>{{ date('d-m-Y', strtotime($rinci->tanggal_minta)) }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <br>
    <table style="width: 100%;">
        <tr>
            <td style="width:50%">
                <div style="height: 100px; width:100%; border: 1px solid black; padding: 5px; margin-top: 3px;">
                    <small>Note :</small><br>
                    <small>{{ $permintaan->keterangan }}</small>
                    <br>
                </div>
            </td>
            <td class="text-center" style="background-image: url('uploads/signature/{{ $ttd_user }}');
                                background-size: 150px 150px;
                                background-repeat: no-repeat;
                                background-position:center;">
                <small>Issued By,</small>
                <br>
                <br>
                <br>
                <br>
                <small>{{ $permintaan->dibuatOleh->name }}</small>
                <br>
                <small><strong>Purchasing</strong></small>
            </td>
            <td class="text-center" style="background-image: url('uploads/signature/{{ $ttd_direktur }}');
                                background-size: 150px 150px;
                                background-repeat: no-repeat;
                                background-position:center;">
                <small>Approved By,</small>
                <br>
                <br>
                <br>
                <br>
                <small>{{ $permintaan->approveDirektur->name }}</small>
                <br>
                <small><strong>Direktur</strong></small>
            </td>
        </tr>
    </table>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>