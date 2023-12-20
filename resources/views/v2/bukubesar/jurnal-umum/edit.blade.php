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

<form action="{{route('bukubesar.jurnal-umum.update', $data['jurnal_umum']->id )}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
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
                            <input type="text" name="nomer" id="" class="form-control" value="{{ $data['jurnal_umum']->nomer }}" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal*</td>
                        <td>
                            <input type="date" name="tanggal" id="" class="form-control" value="{{ date('Y-m-d') }}" value="{{ $data['jurnal_umum']->tanggal }}" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Keterangan</td>
                        <td>
                            <textarea name="keterangan" id="" cols="30" rows="5" class="form-control">{{ $data['jurnal_umum']->keterangan }}</textarea>
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
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-success btn-tambah-akun">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="rincian-akun">
                        @php $i = 0 @endphp
                        @foreach($data['jurnal_umum']->rincian as $rincian)
                        <tr class="rincian-akun-{{$i}}">
                            <td>
                                <select name="rincian[{{$i}}][coa_id]" class="form-control select2" required>
                                    <option value="{{ $rincian->coa_id }}">{{$rincian->coa->nomer_coa}} | {{$rincian->coa->nama_coa}}</option>
                                    @foreach($data['akun'] as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->nomer_coa }} | {{ $akun->nama_coa }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][debit]" id="format-debit-{{$i}}" value="{{ $rincian->debit }}" class="form-control debit" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][kredit]" id="format-kredit-{{$i}}" value="{{ $rincian->kredit }}" class="form-control kredit" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][catatan]" value="{{ $rincian->catatan }}" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-akun">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @php $i++ @endphp
                        @endforeach
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
                @if($data['jurnal_umum']->berkas()->exists())
                <table class="table table-borderless">
                    @foreach($data['jurnal_umum']->berkas as $berkas)
                    <tr>
                        <td>Berkas</td>
                        <td>
                            <a href="{{ route('bukubesar.jurnal-umum.download-berkas',$berkas->nama_berkas) }}">{{ $berkas->nama_berkas }}</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
                <p class="text-danger">Support format berkas : <b>pdf | jpeg | jpg | png | max:2MB</b></p>
                <table class="table table-borderless tabel-berkas">
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
            <button type="button" class="btn btn-outline-danger btn-hapus">Hapus</button>
        </div>
    </div>
</form>

<!-- Modal Hapus -->
<div class="modal fade" id="modal-hapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('bukubesar.jurnal-umum.destroy',$data['jurnal_umum']->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus data!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Apakah anda yakin akan menghapus data ini?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {


        let row_akun = <?php echo $data['jurnal_umum']->rincian->count() ?>;
        let row_berkas = 1;

        $('.select2').select2();

        // formatting ribuan dengan memanggil fungsi
        for (i = 0; i < row_akun; i++) {
            inputRibuan([
                '#format-debit-' + i,
                '#format-kredit-' + i,
            ]);
        }

        // hapus data
        $('.btn-hapus').click(function() {
            $('#modal-hapus').modal('show');
        });

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
        }

        // validasi debit & kredit sama
        function validasiDebitKredit() {
            const total = $('.total').val();
            const total_kredit = $('.total-kredit').val();

            if (total === total_kredit) {
                $('.btn-simpan').attr('disabled', false);
                $('#warning-balance').attr('hidden', true);
            } else {
                $('.btn-simpan').attr('disabled', true);
                $('#warning-balance').attr('hidden', false);
            }
        }

        hitungDebitKredit();
    });
</script>
@endsection