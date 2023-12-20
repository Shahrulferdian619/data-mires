<table>
    <thead>
        <tr>
            <th>gudang</th>
            <th>kode produk</th>
            <th>nama produk</th>
            <th>kuantitas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($persediaan as $item)
        <tr>
            <td>{{ $item->nama_gudang }}</td>
            <td>{{ $item->kode_produk }}</td>
            <td>{{ $item->nama_produk }}</td>
            <td>{{ $item->kuantitas }}</td>
        </tr>
        @endforeach
    </tbody>
</table>