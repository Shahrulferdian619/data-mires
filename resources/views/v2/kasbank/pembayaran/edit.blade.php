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

<small>Dibuat pada : {{$data['pembayaran']->created_at}}, oleh : {{ $data['pembayaran']->dibuatOleh->name }} </small>
<br>
<small>Terakhir diperbarui : {{$data['pembayaran']->updated_at}}, oleh : @if($data['pembayaran']->updated_by !== 0) {{ $data['pembayaran']->diupdateOleh->name }} @endif </small>
<form action="{{route('kasbank.pembayaran.update', $data['pembayaran']->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
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
                                <option value="{{ $data['pembayaran']->bank_id }}">{{ $data['pembayaran']->bank->nomer_coa }} | {{ $data['pembayaran']->bank->nama_coa }}</option>
                                @foreach($data['bank'] as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->nomer_coa }} | {{ $bank->nama_coa }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Nomer*</td>
                        <td>
                            <input type="text" name="nomer" id="" class="form-control" value="{{ $data['pembayaran']->nomer }}" placeholder="Ex: BBK/1001" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal*</td>
                        <td>
                            <input type="date" name="tanggal" id="" class="form-control" value="{{ $data['pembayaran']->tanggal }}" required>
                        </td>
                    </tr>

                    <tr>
                        <td>Keterangan</td>
                        <td>
                            <textarea name="keterangan" id="" cols="30" rows="5" class="form-control">{{ $data['pembayaran']->keterangan }}</textarea>
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
                Rincian Permintaan
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
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-success btn-tambah-akun">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="rincian-akun">
                        @php $i = 0 @endphp
                        @foreach($data['pembayaran']->rincianAkun as $rincian)
                        <tr class="rincian-akun-{{ $i }}">
                            <td>
                                <select name="rincian[{{ $i }}][coa_id]" id="" class="form-control select2" required>
                                    <option value="{{$rincian->coa_id}}">{{$rincian->coa->nomer_coa}} | {{$rincian->coa->nama_coa}}</option>
                                    @foreach($data['akun'] as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->nomer_coa }} | {{ $akun->nama_coa }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $i }}][nominal]" id="" value="{{$rincian->nominal}}" class="form-control rincian-nominal" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $i }}][catatan]" id="" value="{{$rincian->catatan}}" class="form-control">
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
                @if($data['pembayaran']->rincianBerkas()->exists())
                <table class="table table-borderless">
                    @foreach($data['pembayaran']->rincianBerkas as $berkas)
                    <tr>
                        <td>Berkas</td>
                        <td>
                            <a href="{{ route('kasbank.pembayaran.download-berkas',$berkas->nama_berkas) }}">{{ $berkas->nama_berkas }}</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
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
            <a href="{{ route('kasbank.pembayaran.print-pdf',$data['pembayaran']->id) }}" class="btn btn-outline-info" target="_blank">Print</a>
            <a href="{{ route('kasbank.pembayaran.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
            <button type="button" class="btn btn-outline-danger btn-modal-hapus">Hapus</button>
        </div>
    </div>
</form>

<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;" action="{{ route('kasbank.pembayaran.destroy',$data['pembayaran']->id) }}" method="post">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Apakah anda yakin menghapus data ini?</h5>
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
        let row_akun = <?php echo $data['pembayaran']->rincianAkun()->count(); ?>;
        let row_berkas = 1;

        $('.select2').select2();

        // modal hapus
        $('.btn-modal-hapus').click(function() {
            $('#modalHapus').modal('show');
        });

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
                                        <input type="text" name="rincian[${row_akun}][nominal]" id="" value="0" class="form-control rincian-nominal" required>
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
                total_nominal += parseFloat(rincian_nominal);
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