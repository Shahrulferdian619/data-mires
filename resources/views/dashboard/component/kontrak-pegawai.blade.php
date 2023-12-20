<div class="card">
    <div class="card-header bg-primary">
        <h4 class="text-light">Data Kontrak Karyawan</h4>
    </div>
    <div class="card-body">
        <br>
        <div class="table-responsive">
            <table class="datatable-init table-employee table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal Masuk</th>
                        <th>Hingga</th>
                        <th>Sisa Masa Kontrak</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $employee as $emp )
                    <tr>
                        <td>{{ $emp->nik }}</td>
                        <td>{{ $emp->nama_karyawan }}</td>
                        <td>{{ $emp->tanggal_masuk_kerja }}</td>
                        <td>{{ $emp->masa_kontrak}}</td>
                        <td>
                            @if(\Carbon\Carbon::now()->startOfDay() >= $emp->masa_kontrak)
                            Lewat : {{ \Carbon\Carbon::parse($emp->masa_kontrak)->diffAsCarbonInterval( \Carbon\Carbon::now()->startOfDay() )}}
                            @else
                            Sisa : {{ \Carbon\Carbon::parse($emp->masa_kontrak)->diffAsCarbonInterval( \Carbon\Carbon::now()->startOfDay() )}}
                            @endif
                        </td>
                        <td>
                            @if(\Carbon\Carbon::now()->startOfDay() >= $emp->masa_kontrak)
                            <span class="badge badge-danger w-100">Habis</span>
                            @else
                            <span class="badge badge-primary w-100">Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>