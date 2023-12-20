<table>
    <thead>
        <tr>
            <th>nomer konsinyasi</th>
            <th>tanggal</th>
            <th>ekspedisi</th>
            <th>resi</th>
            <th>nama pelanggan</th>
            <th>alamat pelanggan</th>
            <th>nama penerima</th>
            <th>alamat penerima</th>
            <th>gudang asal</th>
            <th>gudang tujuan</th>
            <th>kode produk</th>
            <th>nama produk</th>
            <th>kuantitas</th>
            <th>harga produk</th>
            <th>subtotal</th>
            <th>catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($konsinyasi as $item)
            @foreach($item->rinci as $rinci)
            <tr>
                <td>{{ $item->nomer_konsinyasi }}</td>
                <td>{{ $item->tanggal_konsinyasi }}</td>
                <td>{{ $item->ekspedisi }}</td>
                <td>{{ $item->resi }}</td>
                <td>{{ $item->pelanggan->nama_pelanggan }}</td>
                <td>{{ $item->pelanggan->detail_alamat }} {{ $item->pelanggan->provinsi }}</td>
                <td>{{ $item->penerima }}</td>
                <td>{{ $item->alamat_penerima }}</td>
                <td>{{ $item->gudang_asal}}</td>
                <td>{{ $item->gudang_tujuan}}</td>
                <td>{{ $rinci->produk->kode_barang }}</td>
                <td>{{ $rinci->produk->nama_barang }}</td>
                <td>{{ $rinci->kuantitas }}</td>
                <td>{{ $rinci->harga }}</td>
                <td>{{ $rinci->subtotal }}</td>
                <td>{{ $rinci->catatan }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>