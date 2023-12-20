<table>
    <thead>
        <tr>
            <th>nomer pengiriman penjualan</th>
            <th>tanggal proses kirim</th>
            <th>nomer pesanan penjualan</th>
            <th>tanggal pesanan penjualan</th>
            <th>marketplace</th>
            <th>nomer pesanan</th>
            <th>ekspedisi</th>
            <th>resi</th>
            <th>nama pelanggan</th>
            <th>alamat pelanggan</th>
            <th>provinsi</th>
            <th>nama penerima</th>
            <th>alamat penerima</th>
            <th>kode produk</th>
            <th>nama produk</th>
            <th>kuantitas</th>
            <th>catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengiriman_penjualan as $item)
            @foreach($item->rincian as $rinci)
            <tr>
                <td>{{ $item->nomer_pengiriman_penjualan }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->pesanan->nomer_pesanan_penjualan }}</td>
                <td>{{ $item->pesanan->tanggal }}</td>
                <td>{{ $item->jenis_penjualan }}</td>
                <td>{{ $item->nomer_pesanan }}</td>
                <td>{{ $item->ekspedisi }}</td>
                <td>{{ $item->resi }}</td>
                <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                <td>{{ $item->pelanggan->detil_alamat }}</td>
                <td>{{ $item->pelanggan->provinsi }}</td>
                <td>{{ $item->penerima }}</td>
                <td>{{ $item->alamat_penerima }}</td>
                <td>{{ $rinci->produk->kode_barang }}</td>
                <td>{{ $rinci->produk->nama_barang }}</td>
                <td>{{ $rinci->kuantitas }}</td>
                <td>{{ $rinci->catatan }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>