@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
    }

    .table th {
        padding: 0.2rem;
        text-align: center;
    }
</style>
@endsection

@section('content')

@if($errors->any())
@include('v2.component.error')
@endif

@if(Session::has('sukses'))
@include('v2.component.sukses')
@endif

<div class="alert alert-warning" role="alert">
    <h4>Informasi</h4>
</div>

<a href="{{ route('kasbank.pembayaran.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('kasbank.pembayaran.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Data Pembayaran kasbank -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pembayaran
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <tr>
                        <td style="width: 300px;">Dibayar dari*</td>
                        <td>
                            <select name="bank_id" class="form-control select2" required>
                                <option value="">PILIH BANK</option>
                                @foreach($data['bank'] as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->nomer_coa }} | {{ $bank->nama_coa }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Nomer*</td>
                        <td>
                            <input type="text" name="nomer" id="" class="form-control" placeholder="Ex: BBK/1001" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal*</td>
                        <td>
                            <input type="date" name="tanggal" id="" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Keterangan</td>
                        <td>
                            <textarea name="keterangan" id="" cols="30" rows="5" class="form-control"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Rincian -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Rincian akun
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>akun*</th>
                            <th>nominal*</th>
                            <th>catatan</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-akun">
                        <tr class="rincian-akun-0">
                            <td>
                                <select name="rincian[0][coa_id]" id="" class="form-control select2" required>
                                    <option value="">PILIH AKUN</option>
                                    @foreach($data['akun'] as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->nomer_coa }} | {{ $akun->nama_coa }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="rincian[0][nominal]" id="nominal-0" value="0" class="form-control rincian-nominal" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[0][catatan]" id="" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success btn-tambah-akun">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="text-align: right;">TOTAL</td>
                            <td>
                                <input type="text" name="nominal" id="total" class="form-control bg-light nominal" readonly>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Data Berkas -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Berkas
            </h5>
        </div>

        <div class="card-body">
            <div class="col-md-6">
                <p class="text-danger">Support format berkas : <b>pdf | jpeg | jpg | png | max:2MB</b></p>
                <table class="table table-borderless" id="rincian-berkas">
                    <tr>
                        <td>Berkas</td>
                        <td>
                            <input type="file" name="berkas[0][nama_berkas]" class="form-control">
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-success btn-tambah-berkas">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('kasbank.pembayaran.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {
        let row_akun = 1;
        let row_berkas = 1;

        $('.select2').select2();

        inputRibuan([
            '#nominal-0',
        ]);

        // menambah rincian baris akun
        $('.btn-tambah-akun').click(function() {
            const html_akun = `<tr>
                                    <td>
                                        <select name="rincian[${row_akun}][coa_id]" id="" class="form-control select2" required>
                                            <option value="">PILIH AKUN</option>
                                            @foreach($data['akun'] as $akun)
                                            <option value="{{ $akun->id }}">{{ $akun->nomer_coa }} | {{ $akun->nama_coa }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="rincian[${row_akun}][nominal]" id="nominal-${row_akun}" value="0" class="form-control rincian-nominal" required>
                                    </td>
                                    <td>
                                        <input type="text" name="rincian[${row_akun}][catatan]" id="" class="form-control">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btn-hapus-akun">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            $('#rincian-akun').append(html_akun);
            $('.select2').select2();
            inputRibuan([
                '#nominal-' + row_akun,
            ]);
            row_akun++;
        });

        // menghapus rincian baris akun
        $(document).on('click', '.btn-hapus-akun', function() {
            $(this).closest('tr').remove();
            hitungTotal();
        });

        // menambah rincian baris berkas
        $('.btn-tambah-berkas').click(function() {
            const html_berkas = `<tr>
                                    <td>Berkas</td>
                                    <td>
                                        <input type="file" name="berkas[${row_berkas}][nama_berkas]" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-hapus-berkas">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            $('#rincian-berkas').append(html_berkas);
            row_berkas++;
        });

        // menghapus baris berkas
        $(document).on('click', '.btn-hapus-berkas', function() {
            $(this).closest('tr').remove();
        });

        // jika ada inputan nominal
        $(document).on('input', '.rincian-nominal', function() {
            hitungTotal();
        });

        // menghitung total nominal keseluruhan
        function hitungTotal() {
            let total_nominal = 0;
            $('#rincian-akun').find('tr').each(function() {
                const $row = $(this);
                const rincian_nominal = $row.find('.rincian-nominal').val();
                total_nominal += convertDouble(rincian_nominal);
            });

            $('.nominal').val(formatRupiah(total_nominal));
        }

        function formatRupiah(nominal) {
            return nominal.toLocaleString('id-ID');
        }

        hitungTotal();

    })
</script>
@endsection