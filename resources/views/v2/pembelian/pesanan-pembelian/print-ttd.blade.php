<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Pembelian : {{ $po->nomer_pesanan_pembelian }}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        #table th,
        #table td {
            padding: 1px;
        }
        #table thead th,
        #table tbody td {
            border: 1px solid black;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <img style="height: 100px; margin-left:40px; margin-top: -40px" src="http://miresmahisa.com/logo/kop-mires.png" alt="">
    <hr style="border: 1px solid black; margin-top: -5px;">
    <div style="width:100%; text-align: center; margin-bottom: 10px; margin-top: -20px"><strong style="font-size: 15px;">PURCHASE ORDER (PO)</strong> </div>
    <hr style="border: 1px solid black; margin-top: -2px;">

    <table style="margin-top: -10px">
        <tr>
            <td>
                <table>
                    <tr>
                        <td style="font-size: 11px;">No.</td>
                        <td style="font-size: 11px;">: {{ $po->nomer_pesanan_pembelian }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px;">Date</td>
                        <td style="font-size: 11px;">: {{ date('d M Y', strtotime($po->tanggal)) }}</td>
                    </tr>
                </table>
                <div style="height: 150px; width: 300px; border: 1px solid black; padding: 3px; margin-top: 3px;">
                    <strong style="font-size: 12px;">TO : {{ $po->supplier->nama }}</strong><br>
                    <small style="font-size: 11px;">{{ $po->supplier->detil_alamat }} {{ $po->supplier->kota }} {{ $po->provinsi }}</small><br>
                    <small style="font-size: 11px;">{{ $po->supplier->no_telp }}</small><br>
                    <small style="font-size: 11px;">{{ $po->supplier->nama_pic }}</small>
                </div>
            </td>
        </tr>
    </table>
    <small style="font-size: 11px;"><strong>Please supply the following item :</strong></small>
    <table id="table" class="mt-2" style="font-size: 12px; width:100%; font-weight: bold;">
        <thead>
            <tr>
                <th style="width:20px">No</th>
                <th>Description of goods</th>
                <th style="width:60px">Qty</th>
                <th style="width:40px">Satuan</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($po->rincianItem as $rincian)
            <tr>
                <td style="font-size: 11px;">{{ $loop->iteration }}</td>
                <td style="font-size: 11px;">{{ $rincian->item->nama_barang }} {{ $rincian->deskripsi_item }}</td>
                <td style="font-size: 11px;">{{ $rincian->kuantitas }}</td>
                <td style="font-size: 11px;">{{ $rincian->item->satuan_barang }}</td>
                <td style="text-align: right; font-size: 11px">{{ number_format($rincian->harga,2) }}</td>
                <td style="text-align: right; font-size: 11px">{{ number_format($rincian->subtotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            @if($po->biaya_kirim != 0)
            <tr>
                <td colspan="5" style="text-align: right; font-size: 11px">Biaya kirim</td>
                <td style="border: 1px solid black; text-align:right; font-size: 11px">{{ number_format($po->biaya_kirim, 2) }}</td>
            </tr>
            @endif
            @if($po->ppn == 0)
            <tr>
                <td colspan="5" style="text-align: right; font-size: 11px">Total</td>
                <td style="border: 1px solid black; text-align:right; font-size: 11px">{{ number_format($po->grandtotal, 2) }}</td>
            </tr>
            @else
            <tr>
                <td colspan="5" style="text-align: right; font-size: 11px">DPP</td>
                <td style="border: 1px solid black; text-align:right" font-size: 11px>{{ number_format($po->grandtotal - $po->nilai_ppn, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-size: 11px">PPn 11%</td>
                <td style="border: 1px solid black; text-align:right" font-size: 11px>{{ number_format($po->nilai_ppn, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-size: 11px">Total</td>
                <td style="border: 1px solid black; text-align:right" font-size: 11px>{{ number_format($po->grandtotal, 2) }}</td>
            </tr>
            @endif
        </tfoot>
    </table>
    <div style="height: 60px; width: 450px; border: 1px solid black; padding: 3px; margin-top: 3px;">
        <small style="font-size: 11px;">Semua dokumen dan penagihan faktur pajak <b>harus menggunakan</b></small><br>
        <small style="font-size: 11px;"><b>Description of goods yang sama dengan Purchase Order (PO)</b></small>
    </div>
    <br>
    <table style="font-size: 14px;">
        <tr>
            <td style="font-size: 11px;"><small>Payment Term </small></td>
            <td style="font-size: 11px;"><small>:</small></td>
            <td style="font-size: 11px;"><small>30 days after receipt complete invoice</small></td>
        </tr>
        <tr>
            <td style="font-size: 11px;"><small>Delivery </small></td>
            <td style="font-size: 11px;"><small>:</small></td>
            <td style="font-size: 11px;"><small>PT. Mires mahisa globalindo</small></td>
        </tr>
        <tr>
            <td style="font-size: 11px;"></td>
            <td style="font-size: 11px;"></td>
            <td style="font-size: 11px;"><small> Jl. Raya Menganti 27 C, Forest Mansion, Cluster Blossom Hill, B-08, </small></td>
        </tr>
        <tr>
            <td style="font-size: 11px;"></td>
            <td style="font-size: 11px;"></td>
            <td style="font-size: 11px;"><small> Kel. Lidah wetan, Kec. Lakarsantri - Surabaya (60211) - Indonesia</small></td>
        </tr>
        <tr>
            <td style="font-size: 11px;"><small>Remark </small></td>
            <td style="font-size: 11px;"><small>:</small></td>
            <td style="font-size: 11px;"><small>{{ $po->keterangan }} {{ $po->supplier->nomer_rekening }}</small></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%" class="text-center">
        <tr>
            <td style="font-size: 11px;"><small>Putih</small> </td>
            <td style="font-size: 11px;"><small> : </small> </td>
            <td style="font-size: 11px;"><small>Supplier</small> </td>
            <td style="font-size: 11px;"><small>Issued By,</small> </td>
            @if($po->grandtotal >= 5000000)
            <td style="font-size: 11px;" colspan="2"><small>Approved By,</small> </td>
            @else
            <td style="font-size: 11px;" colspan="1"><small>Approved By,</small> </td>
            @endif
        </tr>
        <tr>
            <td style="font-size: 11px;"><small>...</small></td>
            <td style="font-size: 11px;"><small> : </small></td>
            <td style="font-size: 11px;"><small>File</small></td>
            <td rowspan="2" style="background-image: url('http://miresmahisa.com/uploads/signature/{{ $po->dibuatOleh->signature }}');
                                background-size: cover;
                                background-position: center;
                                background-repeat: no-repeat;">
                <br><br><br><br><br><br><br>
            </td>
            @if($po->grandtotal >= 5000000)
            <td rowspan="2" style="background-image: url('http://miresmahisa.com/uploads/signature/{{ $po->approveKomisaris->signature }}');
                                background-size: cover;
                                background-position: center;
                                background-repeat: no-repeat;">
                <br><br><br><br><br><br><br>
            </td>
            @endif
            <td rowspan="2" style="background-image: url('http://miresmahisa.com/uploads/signature/{{ $po->approveDirektur->signature }}');
                                background-size: cover;
                                background-position: center;
                                background-repeat: no-repeat;">
                <br><br><br><br><br><br><br>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-size: 11px;"><small>{{ $po->dibuatOleh->name }}</small></td>
            @if($po->grandtotal >= 5000000)
            <td style="font-size: 11px;"><small>{{ $po->approveKomisaris->name }}</small></td>
            @endif
            <td style="font-size: 11px;"><small>{{ $po->approveDirektur->name }}</small></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="font-size: 11px;"><small><b>Purchasing</b></small></td>
            @if($po->grandtotal >= 5000000)
            <td style="font-size: 11px;"><small><b>Komisaris</b></small></td>
            @endif
            <td style="font-size: 11px;"><small><b>Direktur</b></small></td>
        </tr>
    </table>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>