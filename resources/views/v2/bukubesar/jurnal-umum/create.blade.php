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

<a href="{{ route('bukubesar.jurnal-umum.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('bukubesar.jurnal-umum.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Data Jurnal Umum -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Jurnal Umum
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <tr>
                        <td>Nomer*</td>
                        <td>
                            <input type="text" name="nomer" id="" class="form-control" required>
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
                            <th>debit*</th>
                            <th>kredit*</th>
                            <th>catatan</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-akun">
                        <tr class="rincian-akun-0">
                            <td>
                                <select name="rincian[0][coa_id]" class="form-control select2" required>
                                    <option value="">PILIH AKUN</option>
                                    @foreach($data['akun'] as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->nomer_coa }} | {{ $akun->nama_coa }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="rincian[0][debit]" id="format-debit-0" value="0" class="form-control debit" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[0][kredit]" id="format-kredit-0" value="0" class="form-control kredit" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[0][catatan]" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success btn-tambah-akun">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br>

                <table class="table table-bordered">
                    <tr>
                        <td>DEBIT</td>
                        <td>
                            <input type="text" name="total" class="form-control total bg-light" readonly>
                        </td>
                        <td>KREDIT</td>
                        <td>
                            <input type="text" name="total_kredit" class="form-control total-kredit bg-light" readonly>
                        </td>
                    </tr>
                </table>
                <p class="text-danger" id="warning-balance" hidden>Debit & kredit tidak sama...</p>
                <p class="text-danger" id="warning-nol" hidden>Salah satu debit / kredit harus bernilai 0...</p>
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
            <a href="{{ route('bukubesar.jurnal-umum.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {


        let row_akun = 0;
        let row_berkas = 1;

        // formatting ribuan dengan memanggil fungsi
        inputRibuan([
            '#format-debit-' + row_akun,
            '#format-kredit-' + row_akun,
        ]);

        $('.select2').select2();

        // menambah rincian baris akun
        $('.btn-tambah-akun').click(function() {
            row_akun++;
            const html_akun = `<tr class="rincian-akun-${row_akun}">
                                    <td>
                                        <select name="rincian[${row_akun}][coa_id]" class="form-control select2" required>
                                            <option value="">PILIH AKUN</option>
                                            @foreach($data['akun'] as $akun)
                                            <option value="{{ $akun->id }}">{{ $akun->nomer_coa }} | {{ $akun->nama_coa }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="rincian[${row_akun}][debit]" id="format-debit-${row_akun}" value="0" class="form-control debit" required>
                                    </td>
                                    <td>
                                        <input type="text" name="rincian[${row_akun}][kredit]" id="format-kredit-${row_akun}" value="0" class="form-control kredit" required>
                                    </td>
                                    <td>
                                        <input type="text" name="rincian[${row_akun}][catatan]" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-akun">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            $('#rincian-akun').append(html_akun);
            $('.select2').select2();
            inputRibuan([
                '#format-debit-' + row_akun,
                '#format-kredit-' + row_akun,
            ]);

            hitungDebitKredit();
        });

        // menghapus rincian baris akun
        $(document).on('click', '.btn-hapus-akun', function() {
            $(this).closest('tr').remove();
            hitungDebitKredit();
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

        // jika ada event input debit & kredit
        $(document).on('input', '.debit, .kredit', function() {
            hitungDebitKredit();
        });

        // menghitung total debit
        function hitungDebitKredit() {
            let total = 0;
            let total_kredit = 0;

            $('#rincian-akun').find('tr').each(function() {
                const $row = $(this);
                const debit = $row.find('.debit').val();
                total += convertDouble(debit);
            });


            $('#rincian-akun').find('tr').each(function() {
                const $row = $(this);
                const kredit = $row.find('.kredit').val();
                total_kredit += convertDouble(kredit);
            });

            // fill total debit
            $('.total').val(formatRibuan(total));
            // fill total kredit
            $('.total-kredit').val(formatRibuan(total_kredit));

            validasiDebitKredit();
            validasiDebitKreditNol();
        }

        // validasi debit & kredit sama
        function validasiDebitKredit() {
            const total = $('.total').val();
            const total_kredit = $('.total-kredit').val();

            console.log(total);

            if (total === total_kredit) {
                $('.btn-simpan').attr('disabled', false);
                $('#warning-balance').attr('hidden', true);
            } else {
                $('.btn-simpan').attr('disabled', true);
                $('#warning-balance').attr('hidden', false);
            }
        }

        // validasi debit & kredit salah satu harus bernilai 0
        function validasiDebitKreditNol() {
            $('#rincian-akun').find('tr').each(function() {
                const $row = $(this);
                const debit = $row.find('.debit').val();
                const kredit = $row.find('.kredit').val();

                if (debit != '0,00' && kredit != '0,00') {
                    $('.btn-simpan').attr('disabled', true);
                    $('#warning-nol').attr('hidden', false);
                } else {
                    $('.btn-simpan').attr('disabled', false);
                    $('#warning-nol').attr('hidden', true);
                }
            })
        }
    });
</script>
@endsection