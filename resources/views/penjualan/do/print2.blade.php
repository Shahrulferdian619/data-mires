<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <img style="height: 100px; margin-left: 30px; margin-top: -20px;" src="http://altamasoft.tech/img/kop-mires.png" alt="">
    <hr style="border: 1px solid black; margin-top: -5px;">
    <h5 style="text-align: center; margin-top: -10px;">SURAT JALAN</h5>
    <hr style="border: 1px solid black; margin-top: -3px;">

    <table style="margin-left: auto; margin-right: auto;">
        <tr>
            <td>
                No DO : {{ $do->do_nomer }} <br>
                Tanggal : {{ $do->do_tanggal }} <br>
                <div style="height: 120px; width: 300px; border: 1px solid black; padding: 3px; margin-top: 3px;">
                    <strong>PT. MIRES MAHISA GLOBALINDO</strong><br>
                    <small>Jl. Raya Menganti 27 C Forest Mansion Cluster Blossom Hill B-08, Lidah Wetan Lakarsantri Surabaya, 60211 Indonesia</small>
                </div>
            </td>
            <td>
                <br>
                Penerima :<br>
                <div style="height: 120px; width: 300px; border: 1px solid black; padding: 3px; margin-top: 3px;">
                    <strong>{{ $do->pelanggan->nama_pelanggan }}</strong> <small>({{ $do->pic_do }})</small> <br>
                    <small>{{ $do->pelanggan->detail_alamat }}</small><br>
                    <small>{{ $do->pelanggan->handphone_pelanggan }}</small>
                </div>
            </td>
        </tr>
    </table>

    <br>
    <table class="table table-bordered mt-2" style="font-size: 12px; font-weight: bold;">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
           @foreach($do->rinci as $rinci)
           <tr>
               <td>{{ $loop->iteration }}</td>
               <td>{{ $rinci->barang->nama_barang }}</td>
               <td>{{ $rinci->qty }}</td>
           </tr>
           @endforeach 
        </tbody>
    </table>
    <br>
    <table class="table">
        <tr>
            <td>
                <div style="height: 100px; width: 400px; border: 1px solid black; padding: 5px; margin-top: 3px;">
                    <small>Note :</small><br>
                    <br><br>
                </div>
            </td>
            <td class="text-center ">
                <small>Disetujui,</small> 
                <br>
                <br>
                <br>
                <br>
                __________
            </td>
        </tr>
    </table>









<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>