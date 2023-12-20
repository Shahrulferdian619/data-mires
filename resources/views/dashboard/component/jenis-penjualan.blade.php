<div class="card">
    <div class="card-body">
        <b>Jenis Pesanan Penjualan (Sales Order)/year</b>
        <br><br>
        <div class="table-responsive">
            <table class=" table table-condensed table-striped table-bordered">
                @foreach($jenis_penjualan as $jenis)
                <tr>
                    <th style="width: 50%">{{ $jenis->jenis_penjualan }}</th>
                    <td>{{ $jenis->count }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>