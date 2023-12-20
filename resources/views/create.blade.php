<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>

	@foreach($errors->all() as $error)
	<ul>
		<li>{{ $error }}</li>
	</ul>
	@endforeach

	<button onclick="clickMe()">Click me!</button>

		<input type="text" id="nomer_pmtpembelian">
		<input type="date" id="tanggal">
		<input type="text" id="supplier_id">
		<button id="save">Submit</button>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		//save
		$('#save').click(function(el) {
			el.preventDefault()
			$.ajax({
				url: "/admin/pmtpembelian",
				method: "POST",
				data: {
					"_token": "{{ csrf_token() }}",
					nomer_pmtpembelian: $('#nomer_pmtpembelian').val(),
					tanggal: $('#tanggal').val(),
					supplier_id: $('#supplier_id').val()
				},
				success: function(res) {
					console.log(res.msg)
				},
				error: function(e) {
					console.log(e)
				}
			})
		})
	})

	function clickMe() {
		alert('Clicked');
	}
</script>
</html>