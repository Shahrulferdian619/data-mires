@extends('v2.layout.vuexy')

@section('custom_style')
<style>
    .table td {
        padding: 0.2rem;
        vertical-align: middle;
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

<a href="{{ route('penerimaan-penjualan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('penerimaan-penjualan.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Data Pelanggan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Penerimaan Penjualan
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Bank penerima</label>
                    <select name="akun_bank_id" id="" class="form-control select2" required>
                        <option value="">PILIH BANK</option>
                        @foreach($data['akun_bank'] as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->nomer_coa }} | {{ $bank->nama_coa }}</option>
                        @endforeach
                    </select>

                    <label for="">Nomer bukti</label>
                    <input name="nomer_bukti" type="text" class="form-control" required>

                    <label for="">Tanggal bayar</label>
                    <input name="tanggal" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" cols="30" rows="6" class="form-control" placeholder="opsional"></textarea>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Data rincian invoice -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Invoice
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabel_rincian_invoice">
                    <thead>
                        <tr class="table-info">
                            <th>invoice</th>
                            <th>tgl</th>
                            <th>nominal</th>
                            <th>terhutang</th>
                            <th style="width: 150px;">potongan</th>
                            <th style="width: 150px;">bayar</th>
                            <th style="width: 150px;">pembayaran</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody class="rincian-pembayaran">
                        @php $i=0 @endphp
                        @foreach($data['invoice'] as $inv)
                        @if($inv->grandtotal_setelah_diskon - $inv->sudah_terbayar != 0)
                        <tr class="rincian-{{$i}}">
                            <td>
                                <input type="text" name="rincian[{{ $i }}][penjualan_invoice_id]" value="{{ $inv->id }}" class="form-control" hidden>
                                {{ $inv->nomer_invoice_penjualan }} | {{ $inv->nomer_ref }}
                            </td>
                            <td>{{ date('d-m-Y',strtotime($inv->tanggal)) }}</td>
                            <td style="text-align: right;">{{ number_format($inv->grandtotal_setelah_diskon,2) }}</td>
                            <td style="text-align: right;">{{ number_format($inv->grandtotal_setelah_diskon - $inv->sudah_terbayar,2) }}</td>
                            <td>
                                <input name="rincian[{{ $i }}][potongan]" type="number" class="form-control potongan" value="0">
                                <select name="rincian[{{ $i }}][akun_potongan_id]" class="form-control select2">
                                    <option value="247">Disc Penjualan</option>
                                    @foreach($data['akun_potongan'] as $potongan)
                                    <option value="{{$potongan->id}}">{{ $potongan->nomer_coa }} | {{ $potongan->nama_coa }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input name="rincian[{{ $i }}][bayar]" type="number" min="0" class="form-control bayar" value="{{ $inv->grandtotal_setelah_diskon - $inv->sudah_terbayar }}">
                            </td>
                            <td>
                                <input name="rincian[{{ $i }}][nominal_pembayaran]" type="text" class="form-control nominal-pembayaran bg-light" readonly>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-hapus-rincian" type="button">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                        @php $i++ @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align: right;">Total</td>
                            <td>
                                <input type="number" class="form-control total bg-light" readonly>
                                <input name="jumlah_pembayaran" type="text" class="form-control jumlah-pembayaran" hidden>
                            </td>
                            <td></td>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Berkas pendukung</td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-success btn-tambah-berkas">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="tabel-berkas">
                        <tr>
                            <td>
                                <input type="file" name="berkas" class="form-control">
                            </td>
                            <td style="text-align: center;">
                                <button class="btn btn-sm btn-danger btn-hapus-berkas">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('penerimaan-penjualan.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

<!-- Modal detil invoice -->
<div class="modal fade" id="modalDetilInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Detil Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <td>Nomer invoice</td>
                            <td>
                                <input type="text" class="form-control nomer-invoice" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>
                                <input type="text" class="form-control tanggal" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomer ref</td>
                            <td>
                                <input type="text" class="form-control nomer-ref" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Market</td>
                            <td>
                                <input type="text" class="form-control market" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td>
                                <input type="text" class="form-control pelanggan" disabled>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary btn-tambah-paket">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {

        $tabelRincianPembayaran = $('.rincian-pembayaran');

        $('.select2').select2();

        // fungsi untuk menghapus rincian pembayaran
        $(document).on('click', '.btn-hapus-rincian', function() {
            $(this).closest('tr').remove();
            hitungTotal();
        });

        // hitung ulang total ketika ada perubahan input bayar
        $(document).on('input', '.bayar, .potongan', function() {
            hitungTotal();
        });

        function hitungTotal() {
            // panggil fungsi autofill nominal pembayaran
            hitungSubNominalPembayaran();

            $tabelRincianPembayaran.find('tr').each(function() {
                const $row = $(this);
                const bayar = parseFloat($row.find('.bayar').val());
            });
        }

        function hitungSubNominalPembayaran() {
            let nominal_pembayaran = 0;
            let total = 0;

            $tabelRincianPembayaran.find('tr').each(function() {
                const $row = $(this);
                const potongan = $row.find('.potongan').val();
                const bayar = $row.find('.bayar').val();
                nominal_pembayaran = bayar - potongan;

                // autofill pembayaran
                $row.find('.nominal-pembayaran').val(nominal_pembayaran);

                // sum total
                total += nominal_pembayaran;
            });

            $('.total').val(total);
            $('.jumlah-pembayaran').val(total);
        }

        function formatRupiah(nominal) {
            return nominal.toLocaleString('id-ID');
        }

        hitungTotal();
    });

    // fungsi untuk menampilkan detil invoice
    function detilInvoice(invoiceId) {
        $.ajax({
            method: "GET",
            url: "{{ route('penerimaan-penjualan.get-detil-invoice') }}",
            data: {
                "id": invoiceId
            },
            dataType: "JSON",
            success: function(data) {
                //console.log(data);
                $('.nomer-invoice').val(data.nomer_invoice_penjualan);
                $('.tanggal').val(data.tanggal);
                $('.nomer-ref').val(data.nomer_ref);
                $('.market').val(data.jenis_penjualan);
                $('.pelanggan').val(data.pelanggan.nama_pelanggan);
            }
        });

        $('#modalDetilInvoice').modal('show');
    }
</script>
@endsection