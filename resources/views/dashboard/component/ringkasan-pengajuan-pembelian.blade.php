<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table-dashboard table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Pengajuan </th>
                        <th>Jumlah</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> Permintaan Pembelian Belum Disetujui Direktur </td>
                        <td> {{ $pmtpembelian->count() }} </td>
                        <td>
                            <a href="/admin/pmtpembelian" class="badge badge-light-secondary">
                                <i data-feather="eye"></i>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td> Pesanan PO Belum Disetujui </td>
                        <td> {{ $popembelian }} </td>
                        <td>
                            <a href="/admin/po" class="badge badge-light-secondary">
                                <i data-feather="eye"></i>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td> Purchase Invoice Belum Disetujui </td>
                        <td> {{ $fakturpembelian }} </td>
                        <td>
                            <a href="/admin/fakturpembelian" class="badge badge-light-secondary">
                                <i data-feather="eye"></i>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td> Jumlah PO Berjalan </td>
                        <td> {{ $po_running }} </td>
                        <td>
                            <a href="/admin/po" class="badge badge-light-secondary">
                                <i data-feather="eye"></i>
                                Lihat
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>