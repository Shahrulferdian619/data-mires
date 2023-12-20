<!DOCTYPE html>
<html>
<head>
	<title>Laporan Karyawan | PT MIRES MAHISA GLOBALINDO</title>
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

		.text-center-row > th,
		.text-center-row > td {
			text-align: center;
		}

        .table thead tr th {
        /* background-color: #DDEFEF;
        border: solid 1px #DDEEEE;
        color: #336B6B; */
        /*padding: 30px;*/
        text-align: center;
        vertical-align: middle;
        }

		.center {
		margin-left: auto;
		margin-right: auto;
        margin-top: -20px;
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
    <div style="width:100%; text-align: center; margin-bottom: 10px; margin-top: -20px"><strong style="font-size: 20px;">KARYAWAN</strong> </div>
    <hr style="border: 1px solid black; margin-top: -3px;">
	</center>

	
 
	<table class="table table-bordered table-sm" style="width:100%">
		<thead>
			<tr class="text-center-row">
				<th style="width:3%">No.</th>
				<th style="width:7%">NIK</th>
				<th style="width:10%">Nama Karyawan</th>
				<th style="width:10%">Tanggal Lahir Karyawan</th>
				<th style="width:8%;">No. HP Karyawan</th>
				<th style="width:10%">Email Karyawan</th>
				<th style="width:10%">Alamat Karyawan</th>
				<th style="width:8%">Jabatan Karyawan</th>
				<th style="width:8%">Divisi Karyawan</th>
				<th style="width:8%">Tanggal Masuk Kerja</th>
				<th style="width:8%">Tanggal Keluar Kerja</th>
				<th style="width:5%">Gaji Karyawan</th>
				<th style="width:5%">Keterangan Tambahan</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach( $employee as $emp )
			<tr>
				<td align="center" valign="center">{{ $i++ }}.</td>
                <td>{{ $emp->nik }}</td>
				<td>{{ $emp->nama_karyawan }}</td>
				<td>{{ date('d-m-Y', strtotime($emp->tanggal_lahir_karyawan)) }}</td>
                <td>{{ $emp->nomer_hp_karyawan }}</td>
                <td>{{ $emp->email_karyawan }}</td>
                <td>{{ $emp->alamat_karyawan }}</td>
                <td>{{ $emp->jabatan_karyawan }}</td>
                <td>{{ $emp->divisi_karyawan }}</td>
				<td>{{ date('d-m-Y', strtotime($emp->tanggal_masuk_kerja)) }}</td>
				<td>{{ date('d-m-Y', strtotime($emp->tanggal_keluar_kerja)) }}</td>
                <td>@currency($emp->gaji_karyawan)</td>
                <td>{{ $emp->keterangan_tambahan }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>