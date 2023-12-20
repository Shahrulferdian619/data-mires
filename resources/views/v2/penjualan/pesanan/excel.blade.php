<table>
    <thead>
        <tr>
            <th>nomer pesanan penjualan</th>
            <th>tanggal</th>
            <th>marketplace</th>
            <th>nomer pesanan</th>
            <th>ekspedisi</th>
            <th>resi</th>
            <th>sales</th>
            <th>nama pelanggan</th>
            <th>alamat pelanggan</th>
            <th>nama penerima</th>
            <th>alamat penerima</th>
            <th>kode produk</th>
            <th>nama produk</th>
            <th>kuantitas</th>
            <th>harga produk</th>
            <th>diskon persen</th>
            <th>diskon nominal</th>
            <th>potongan</th>
            <th>cashback</th>
            <th>subtotal</th>
            <th>catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pesanan_penjualan as $item)
            @foreach($item->rincian as $rinci)
            <tr>
                <td>{{ $item->nomer_pesanan_penjualan }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->jenis_penjualan }}</td>
                <td>{{ $item->nomer_pesanan }}</td>
                <td>{{ $item->ekspedisi }}</td>
                <td>{{ $item->resi }}</td>
                <td>{{ $item->sales->nama_sales }}</td>
                <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                <td>{{ $item->pelanggan->detail_alamat }} {{ $item->pelanggan->provinsi }}</td>
                <td>{{ $item->penerima }}</td>
                <td>{{ $item->alamat_penerima }}</td>
                <td>{{ $rinci->produk->kode_barang }}</td>
                <td>{{ $rinci->produk->nama_barang }}</td>
                <td>{{ $rinci->kuantitas }}</td>
                <td>{{ $rinci->harga_produk }}</td>
                <td>{{ $rinci->diskon_persen }}</td>
                <td>{{ $rinci->diskon_nominal }}</td>
                <td>{{ $rinci->potongan_admin }}</td>
                <td>{{ $rinci->cashback }}</td>
                <td>{{ $rinci->subtotal }}</td>
                <td>{{ $rinci->catatan }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>