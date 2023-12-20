<!DOCTYPE html>
<html>
<head>
	<title>Laporan Tagihan Penjualan | PT MIRES MAHISA GLOBALINDO</title>
	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
	<link rel="stylesheet" type="text/css" href="{{ public_path('vuexy/pdf/css/bootstrap.min.css') }}">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
			color: black;
		}

		table.table-bordered{
			border:1px solid black;
			margin-top:10px;
			border-radius: 6px;
		}
		table.table-bordered > thead > tr > th{
			border:1px solid black;
		}
		table.table-bordered > tbody > tr > td{
			border:1px solid black;
		}

		.text-center-row>th,
		.text-center-row>td {
			text-align: center;
		}

		.center {
		margin-left: auto;
		margin-right: auto;
		}
	</style>
	<center>
	<!-- <img style="height: 100px; margin-left: -20px;" src="http://altamasoft.tech/img/kop-mires.png" alt=""> -->
    <center>
		<table class="center">
			<tr>
				<td><img style="margin-top: 15px;" src="{{ public_path('vuexy/images/logo/logopdf.png') }}" width="100" height="66"></td>
				<td>
				<center>
					<font size="5.5 ex"><b>PT MIRES MAHISA GLOBALINDO</b></font><br>
					<font size="2 ex"><b>JL. Raya Menganti 27 C, Forest Mansion, Cluster Blossom Hill B-08 Kel.Lidah Wetan,</b></font><br>
					<font size="2 ex"><b>Kec. Lakarsantri - Surabaya (60211) - Indonesia (081131588881)</b></font>
				</center>
				</td>
			</tr>
		</table>
	</center>
	<br>
	<hr style="border: 1px solid black; margin-top: -5px;">
    <!-- <h5 style="text-align: center; margin-top: -10px; color: black;">KATEGORI BARANG</h5> -->
    <div style="width:100%; text-align: center; margin-bottom: 10px; margin-top: -20px"><strong style="font-size: 20px;">TAGIHAN PENJUALAN</strong> </div>
    <hr style="border: 1px solid black; margin-top: -3px;">
	</center>

	
 
	<table class="table table-bordered table-sm" style="width:100%">
		<thead>
			<tr class="text-center-row">
				<th style="width:5%">No.</th>
				<th style="width:25%">Nomor SI</th>
				<th style="width:25%">Tanggal SI</th>
				<th style="width:45%">Status Pembayaran</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
            @foreach($si as $si)
			<tr>
				<td align="center" valign="center">{{ $i++ }}.</td>
				<td>{{ $si->nomer_invoice }}</td>
				<td>{{ date('d-m-Y', strtotime($si->tanggal)) }}</td>
                <td>
                    @if($si->is_payment == 0)
                    Belum Dibayar
                    @elseif($si->is_payment == 1)
                    Dibayar Sebagian
                    @elseif($si->is_payment == 2)
                    Sudah lunas
                    @endif
                </td>
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>