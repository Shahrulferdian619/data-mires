<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #table-head tr td{
            border: none;
            font-size:12px;
            text-align: center;
        }
        #table3 tr td, #table3 tr th{
            border: 1px solid black;
            font-size:12px;
            padding: 3px;
        }
        #table2 tr td {
            font-size:12px;
        }
        body { font-family: Arial;}
        p { font-family: Courier, monospace; }
        div { font-family: Duru Sans, Verdana, sans-serif; }
        .card{
            font-size:12px;
            border-bottom: 1px solid black;
            width: 200px;
            text-align:center;
        }
    </style>
</head>
<body>
    <div class="" style="border: 1px solid black; padding: 10px">
<table cellspacing="0" cellpadding="0" id="table-head" style="width:100%">
            <tr>
                <td rowspan="4" style="width:60%; padding: 2px; text-align:center">
                    <img style="width: 400px; " src="{{ asset('img/kop-mires.png') }}" alt="">
                </td>
                <td style="text-align: center">
                    <h1 style="margin-top: 15px;">SURAT JALAN</h1>
                </td>
            </tr>
        </table>
        <hr style="">
        <table id="table2" style="border:none; width:100%; margin-bottom: 10px">
            <tr>
                <td style="width:10%">Kepada</td>
                <td style="width:40%">: {{ $do->pic_do }}</td>
                <td style="width:10%">No. SO</td>
                <td style="width:40%">: {{ $do->do_nomer }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $do->do_tanggal }}</td>
                <td>Alamat</td>
                <td>: {{ $do->alamat_do }}</td>
            </tr>
            <tr>
                <td>No Telp/Fax</td>
                <td>: </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </table>
        
        <table id="table3" style="width:100%" cellspacing="0" cellpadding="0">
            <tr>
                <th style="width: 20px">No</th>
                <th>Kode Produk</th>
                <th>Deskripsi Produk</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th style="width:30%">Keterangan</th>
            </tr>
            @foreach($do->rinci as $rinci)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rinci->barang->kode_barang }}</td>
                <td>{{ $rinci->barang->nama_barang }}</td>
                <td style="text-align:center">{{ $rinci->qty }}</td>
                <td>{{ $rinci->barang->satuan_barang }}</td>
                <td></td>
            </tr>
            @endforeach
        </table>
        <small style="font-size:10px">Barang Telah Diterima Dalam Keadaan Baik Dan Sesuai</small>
        <table style="width:100%; padding: 15px;" >
            <tr>
                <td>
                    <div class="card">
                        <div class="card-header"><strong>Penerima</strong></div>
                        <br><br><br><br>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-header"><strong>Pengirim</strong></div>
                        <br><br><br><br>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-header"><strong>Gudang</strong></div>
                        <br><br><br><br>
                    </div>
                </td>
            </tr>
        </table>
    </div>   
</body>
<script>
    window.print()
</script>
</html>