<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Pembelian</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        #table th,
        #table td {
            padding: 1px;
        }

        #table thead th,
        #table tbody td {
            /* border-left: 1px solid black;
            border-right: 1px solid black; */

            border: 1px solid black;
        }

        /* #table thead tr th{
            border: 1px solid black;
        } */
    </style>
</head>

<body>
    <img style="height: 100px; margin-left:40px; margin-top: -40px" src="http://miresmahisa.com/logo/kop-mires.png" alt="">
    <hr style="border: 1px solid black; margin-top: -5px;">
    <div style="width:100%; text-align: center; margin-bottom: 10px; margin-top: -20px"><strong style="font-size: 20px;">PURCHASE ORDER (PO)</strong> </div>
    <hr style="border: 1px solid black; margin-top: -2px;">

    <table style="margin-top: -10px">
        <tr>
            <td>
                <table>
                    <tr>
                        <td><small>PO. No</small> </td>
                        <td><small> : {{ $po->nomer_po }}</small></td>
                    </tr>
                    <tr>
                        <td><small>Date</small> </td>
                        <td><small> : {{ date('d M Y', strtotime($po->tanggal_po)) }}</small></td>
                    </tr>
                </table>
                <div style="height: 120px; width: 300px; border: 1px solid black; padding: 3px; margin-top: 3px;">
                    <strong>TO : {{ $supplier->nama_supplier }}</strong><br>
                    <small>Address : {{ $supplier->detail_alamat }}</small><br>
                    <small>Phone : {{ $supplier->handphone_supplier }}</small><br>
                    <small>PIC Person : {{ $supplier->pic }}</small>
                </div>
            </td>
        </tr>
    </table>
    <small><strong>Please supply the following item :</strong></small>
    <table id="table" class="mt-2" style="font-size: 12px; width:100%; font-weight: bold;">
        <thead>
            <tr>
                <th style="width:20px">No</th>
                <th><small><b>Description of goods</b></small></th>
                <th style="width:60px">Quantity</th>
                <th style="width:40px">Unit</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($po_rinci as $rinci)

            @php
            $subtotal = ($rinci->harga - ($rinci->harga * $rinci->dsc / 100)) * $rinci->jumlah;
            $total += $subtotal;
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                @if( $rinci->description != null)
                <td>{{ $rinci->barang->nama_barang }} - {{ $rinci->description }}</td>
                @else
                <td>{{ $rinci->barang->nama_barang }}</td>
                @endif
                <td>{{ $rinci->jumlah }}</td>
                <td>{{ $rinci->barang->satuan_barang }}</td>
                <td>Rp. {{ number_format($rinci->harga) }}
                    @if($rinci->dsc != 0)
                    x {{ $rinci->dsc }}% = Rp. {{ number_format($rinci->harga - ($rinci->harga * $rinci->dsc / 100)) }}
                    @endif</td>
                <td>Rp. {{ number_format($subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" rowspan="5">
                    <small>Semua dokumen dan penagihan faktur pajak <b>harus menggunakan</b> </small><br>
                    <small><strong>Description of goods yang sama dengan Purchase Order (PO)</strong> </small>
                </td>
                <td style="text-align: right;">DPP</td>
                <td style="border: 1px solid black;">
                    Rp. {{ number_format($total) }}
                </td>
            </tr>
            @if($po->pph != 0)
            <tr>
                <td style="text-align: right;"> PPh (-) </td>
                <td style="border: 1px solid black;">Rp.{{ number_format($total * $po->pph / 100) }}</td>
                @php $total = $total - ($total * $po->pph / 100); @endphp
            </tr>
            @endif
            @if($po->pajak_lain != 0)
            <tr>
                <td style="text-align: right;"> Pajak Lain (+) </td>
                <td style="border: 1px solid black;">Rp.{{ number_format($total * $po->pajak_lain / 100) }}</td>
                @php $total = $total + ($total * $po->pajak_lain / 100); @endphp
            </tr>
            @endif
            <tr>
                <td style="text-align: right;">PPN</td>
                <td style="border: 1px solid black;">
                    @if($po->is_tax == 1)
                    @php $ppn = $total * 11 /100; @endphp
                    Rp. {{ number_format($ppn) }}
                    @php $total = $total + $ppn; @endphp
                    @else
                    Rp. 0
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Total</td>
                <td style="border: 1px solid black;">
                    Rp.{{number_format($total, 2)}}
                </td>
            </tr>
        </tfoot>
    </table>
    <br>
    <table style="font-size: 14px;">
        <tr>
            <td><small>Payment Term </small></td>
            <td> : </td>
            <td><small>30 days after receipt complete invoice</small></td>
        </tr>
        <tr>
            <td><small>Delivery </small></td>
            <td> : </td>
            <td><small>PT. Mires mahisa globalindo</small></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><small> Jl. Raya Menganti 27 C, Forest Mansion, Cluster Blossom Hill, B-08, </small></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><small> Kel. Lidah wetan, Kec. Lakarsantri - Surabaya (60211) - Indonesia</small></td>
        </tr>
        <tr>
            <td><small>Remark </small></td>
            <td> : </td>
            <td><small>{{ $po->keterangan }}</small></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%" class="text-center">
        <tr>
            <td><small>Putih</small> </td>
            <td><small> : </small> </td>
            <td><small>Supplier</small> </td>
            <td><small>Issued By,</small> </td>
            @if($total >= 5000000)
            <td colspan="2"><small>Approved By,</small> </td>
            @else
            <td colspan="1"><small>Approved By,</small> </td>
            @endif
        </tr>
        <tr>
            <td><small>...</small></td>
            <td><small> : </small></td>
            <td><small>File</small></td>
            <td rowspan="2">
                <br><br><br><br><br><br>
            </td>
            @if($total >= 5000000)
            <td rowspan="2">
                <br><br><br><br><br><br>
            </td>
            @endif
            <td rowspan="2">
                <br><br><br><br><br><br>
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
            <td><small>{{ $po->purchasing_po->name }}</small></td>
            @if($total >= 5000000)
            <td><small>{{ $komisaris }}</small></td>
            @endif
            <td><small>Rizky Darmawan</small></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><small><b>Purchasing</b></small></td>
            @if($total >= 5000000)
            <td><small><b>Komisaris</b></small></td>
            @endif
            <td><small><b>Direktur</b></small></td>
        </tr>
    </table>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>