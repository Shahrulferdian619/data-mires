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

<a href="{{ route('pembelian.invoice-pembelian.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pembelian.invoice-pembelian.update',$data['invoicePembelian']->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Pesanan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Invoice Pembelian
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <tr>
                        <td style="width: 300px;">Pilih pesanan pembelian*</td>
                        <td>
                            <select name="pesanan_pembelian_id" class="form-control select2 pesanan-pembelian" required>
                                <option value="{{ $data['invoicePembelian']->pesanan_pembelian_id }}">{{ $data['invoicePembelian']->pesananPembelian->nomer_pesanan_pembelian }}</option>
                                @foreach($data['pesananPembelian'] as $po)
                                <option value="{{ $po->id }}">{{ $po->nomer_pesanan_pembelian }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Supplier*</td>
                        <td>
                            <select name="supplier_id" class="form-control select2" required>
                                <option value="{{ $data['invoicePembelian']->supplier_id }}">{{ $data['invoicePembelian']->supplier->kode }} | {{ $data['invoicePembelian']->supplier->nama }}</option>
                                @foreach($data['supplier'] as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->kode }} | {{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Nomer pesanan pembelian (PO)</td>
                        <td>
                            <input type="text" class="form-control bg-light" value="{{ $data['invoicePembelian']->pesananPembelian->nomer_pesanan_pembelian }}" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Nomer invoice pembelian*</td>
                        <td>
                            <input type="text" name="nomer_invoice_pembelian" class="form-control" value="{{ $data['invoicePembelian']->nomer_invoice_pembelian }}" placeholder="Ex: INV/1000" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal*</td>
                        <td>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </td>
                    </tr>
                    <tr>
                        <td>PPn 11%*</td>
                        <td>
                            <select name="ppn" class="form-control select2 ppn">
                                <option value="{{ $data['invoicePembelian']->ppn }}" selected>@if($data['invoicePembelian']->ppn == 0)Tidak @elseif($data['invoicePembelian']->ppn == 1)Ya @elseInclude @endif</option>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                                <option value="2">Include</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>
                            <textarea name="keterangan" cols="30" rows="5" class="form-control" placeholder="Opsional">{{ $data['invoicePembelian']->keterangan }}</textarea>
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
                Rincian Item
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th style="width: 275px;">item*</th>
                            <th style="width: 150px;">deskripsi item</th>
                            <th style="width: 100px;">qty*</th>
                            <th style="width: 150px;">harga*</th>
                            <th style="width: 85px;">dsc(%)</th>
                            <th style="width: 120px;">dsc(Rp)</th>
                            <th style="width: 150px;">subtotal</th>
                            <th style="width: 150px">catatan</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-item">
                        @php $index = 0 @endphp
                        @foreach($data['invoicePembelian']->rincianItem as $rincian)
                        <tr>
                            <td>
                                <select name="rincian[{{ $index }}][item_id]" id="" class="form-control">
                                    <option value="{{ $rincian->item_id }}">{{ $rincian->item->kode_barang }} | {{ $rincian->item->nama_barang }}</option>
                                </select>
                            </td>
                            <td>
                                <textarea name="rincian[{{ $index }}][deskripsi_item]" cols="30" rows="3" class="form-control">{{ $rincian->deskripsi_item }}</textarea>
                            </td>
                            <td>
                                <input type="number" name="rincian[{{ $index }}][kuantitas]" min="1" class="form-control kuantitas" value="{{ $rincian->kuantitas }}" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $index }}][harga]" min="0" class="form-control harga" id="harga-{{ $index }}" value="{{ $rincian->harga }}" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $index }}][diskon_persen]" min="0" step="0.01" class="form-control diskon-persen" value="{{ $rincian->diskon_persen }}">
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $index }}][diskon_nominal]" min="0" class="form-control diskon-nominal" value="0">
                            </td>
                            <td>
                                <input type="text" name="rincian[{{ $index }}][subtotal]" class="form-control subtotal bg-light" readonly>
                            </td>
                            <td>
                                <textarea name="rincian[{{ $index }}][catatan]" cols="30" rows="3" class="form-control">{{ $rincian->catatan }}</textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-item">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @php $index++ @endphp
                        @endforeach
                    </tbody>
                </table>

                <br>

                <table class="table table-bordered">
                    <tr>
                        <td style="width: 300px;">Total</td>
                        <td>
                            <input type="text" name="total" class="form-control bg-light total" value="0" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Biaya kirim</td>
                        <td>
                            <input type="text" name="biaya_kirim" class="form-control biaya-kirim" id="biaya-kirim" value="{{ $data['invoicePembelian']->biaya_kirim }}">
                        </td>
                    </tr>
                    <tr>
                        <td>Dsc global(%)</td>
                        <td>
                            <input type="number" name="diskon_persen_global" class="form-control diskon-persen-global" min="0" step="0.01" value="{{ $data['invoicePembelian']->diskon_persen_global }}">
                        </td>
                    </tr>
                    <tr>
                        <td>Dsc global(Rp)</td>
                        <td>
                            <input type="text" name="diskon_nominal_global" class="form-control diskon-nominal-global bg-light" value="0" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Total setelah dsc</td>
                        <td>
                            <input type="text" name="total_setelah_diskon" class="form-control total-setelah-diskon bg-light" value="0" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Nilai PPn</td>
                        <td>
                            <input type="text" name="nilai_ppn" class="form-control nilai-ppn bg-light" value="0" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="pajaklain1_keterangan" class="form-control" value="{{ $data['invoicePembelian']->pajaklain1_keterangan }}" placeholder="Pajak lain 1 (masukkan persen)">
                        </td>
                        <td>
                            <input type="number" name="pajaklain1_persen" class="form-control pajaklain1-persen" min="0" step="0.01" value="{{ $data['invoicePembelian']->pajaklain1_persen }}">
                            <input type="text" name="pajaklain1_nominal" class="form-control bg-light pajaklain1-nominal" value="0" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Grandtotal</td>
                        <td>
                            <input type="text" name="grandtotal" class="form-control grandtotal bg-light" value="0" readonly>
                        </td>
                    </tr>
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
                @if($data['invoicePembelian']->rincianBerkas()->exists())
                <table class="table table-borderless">
                    @foreach($data['invoicePembelian']->rincianBerkas as $berkas)
                    <tr>
                        <td>Berkas</td>
                        <td>
                            <a href="{{ route('pembelian.invoice-pembelian.download-berkas',$berkas->nama_berkas) }}">{{ $berkas->nama_berkas }}</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
                @endif
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
            <a href="{{ route('pembelian.invoice-pembelian.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
            <button type="button" class="btn btn-outline-danger" onclick="hapusInvoice()">Hapus</button>
        </div>
    </div>
</form>

<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.invoice-pembelian.destroy',$data['invoicePembelian']->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('delete')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Hapus data!</h5>
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

        let rowItem = <?php echo $data['invoicePembelian']->rincianItem->count() ?>;
        let rowBerkas = 1; //inisialisasi awal untuk menambah baris berkas

        // deklarasi variabel
        const $tabelRincianItem = $('#rincian-item');

        $('.select2').select2();

        inputRibuan([
            '#biaya-kirim'
        ]);

        // format ribuan
        for (index = 0; index < rowItem; index++) {
            inputRibuan([
                '#harga-' + index,
            ]);
        }
        // eof format ribuan

        // menambah baris berkas
        $('.btn-tambah-berkas').click(function() {
            let berkas_html = `<tr>
                                    <td>Berkas</td>
                                    <td>
                                        <input type="file" name="berkas[${rowBerkas}][nama_berkas]" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-hapus-berkas">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
            rowBerkas++;
            $('.tabel-berkas').append(berkas_html);
        });

        // menghapus baris berkas
        $(document).on('click', '.btn-hapus-berkas', function() {
            $(this).closest('tr').remove();
        });

        // hapus baris rincian item
        $(document).on('click', '.btn-hapus-item', function() {
            $(this).closest('tr').remove();
        });

        // proses select pesanan pembelian
        $('.pesanan-pembelian').on('change', function(e) {
            const po_id = $(this).val();

            $.ajax({
                type: "get",
                url: "/v2/pembelian/pesanan-pembelian/getDetil/" + po_id,
                dataType: "json",
                success: function(response) {
                    $('#rincian-item').find('tr').remove();

                    let data = response.rincian_item;
                    //console.log(data);
                    // fill rincian item
                    for (i = 0; i < data.length; i++) {
                        let item_html = `<tr>
                                            <td>
                                                <select name="rincian[${i}][item_id]" class="form-control item" readonly required>
                                                    <option value="${data[i].item_id}">${data[i].item.nama_barang}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea name="rincian[${i}][deskripsi_item]" cols="30" rows="3" class="form-control">${data[i].deskripsi_item === null ? "" : data[i].deskripsi_item}</textarea>
                                            </td>
                                            <td>
                                                <input type="number" name="rincian[${i}][kuantitas]" min="1" class="form-control kuantitas" value="${data[i].kuantitas}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="rincian[${i}][harga]" min="0" class="form-control harga" id="harga-${i}" value="${data[i].harga === null ? "0" : data[i].harga}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="rincian[${i}][diskon_persen]" min="0" step="0.01" class="form-control diskon-persen" value="0">
                                            </td>
                                            <td>
                                                <input type="text" name="rincian[${i}][diskon_nominal]" min="0" class="form-control diskon-nominal" value="0">
                                            </td>
                                            <td>
                                                <input type="text" name="rincian[${i}][subtotal]" class="form-control subtotal bg-light" readonly>
                                            </td>
                                            <td>
                                                <textarea name="rincian[${i}][catatan]" cols="30" rows="3" class="form-control">${data[i].catatan === null ? "" : data[i].catatan}</textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-item">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`;
                        $('#rincian-item').append(item_html);

                    }

                    // format ribuan
                    for (index = 0; index < data.length; index++) {
                        inputRibuan([
                            '#harga-' + index,
                        ]);
                    }
                    // eof format ribuan

                    hitungSubtotal();
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON); // tampilkan error ke console log
                }
            });
        });

        // menghitung ulang subtotal apabila ada perubahan input pada rincian item
        $(document).on('input', '.harga, .kuantitas', function() {
            hitungDiskonItemPersen();
        });

        // hitung ulang diskon nominal, bila ada perubahan input persen
        $(document).on('input', '.diskon-persen', function() {
            hitungDiskonItemPersen();
        });

        // hitung ulang diskon persen, bila ada perubahan input nominal
        $(document).on('input', '.diskon-nominal', function() {
            hitungDiskonItemNominal();
        });

        // hitung ulang diskon global persen ketika ada perubahan input
        $(document).on('input', '.diskon-persen-global', function() {
            hitungDiskonPersenGlobal();
        });

        // hitung ulang grandtotal ketika ada perubahan input biaya kirim
        $(document).on('input', '.biaya-kirim', function() {
            hitungGrandtotal();
        });

        // menghitung presentase pajaklain1
        $(document).on('input', '.pajaklain1-persen', function() {
            hitungPajaklain1();
        });

        // mengganti ppn
        $(document).on('change', '.ppn', function() {
            hitungGrandtotal();
        });

        // fungsi untuk merubah persen diskon item menjadi rupiah
        function hitungDiskonItemPersen() {
            $tabelRincianItem.find('tr').each(function() {
                const $row = $(this);
                const harga = convertDouble($row.find('.harga').val());
                const diskon_persen = $row.find('.diskon-persen').val();
                const diskon_nominal = harga * diskon_persen / 100;

                // fill diskon nominal
                $row.find('.diskon-nominal').val(formatRibuan(diskon_nominal));
            });

            hitungSubtotal();
        }

        // fungsi untuk merubah nominal diskon item menjadi persen
        function hitungDiskonItemNominal() {
            $tabelRincianItem.find('tr').each(function() {
                const $row = $(this);
                const harga = convertDouble($row.find('.harga').val());
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const diskon_persen = diskon_nominal / harga * 100;

                // fill diskon persen
                $row.find('.diskon-persen').val(diskon_persen.toFixed(2));
            });

            hitungSubtotal();
        }

        // menghitung diskon persen menjadi rupiah
        function hitungDiskonPersenGlobal() {
            const total = $('.total').val();
            const diskon_persen_global = $('.diskon-persen-global').val();
            const diskon_nominal_global = convertDouble(total) * diskon_persen_global / 100;
            let total_setelah_diskon = 0;

            // fill diskon nominal global
            $('.diskon-nominal-global').val(formatRibuan(diskon_nominal_global));

            // fill total setelah diskon
            total_setelah_diskon = convertDouble(total) - diskon_nominal_global;
            $('.total-setelah-diskon').val(formatRibuan(total_setelah_diskon));

            hitungGrandtotal();
        }

        // fungsi untuk menghitung subtotal
        function hitungSubtotal() {
            let total = 0;

            $tabelRincianItem.find('tr').each(function() {
                const $row = $(this);
                const kuantitas = $row.find('.kuantitas').val();
                const harga = convertDouble($row.find('.harga').val());
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const subtotal = (harga - convertDouble(diskon_nominal)) * kuantitas; //hitung subtotal
                total += subtotal;

                // fill subtotal
                $row.find('.subtotal').val(formatRibuan(subtotal));
            });

            // fill total
            $('.total').val(formatRibuan(total));

            hitungDiskonPersenGlobal();
            hitungPajaklain1();
            hitungGrandtotal();
        }

        // menghitung pajaklain1
        function hitungPajaklain1() {
            let total_setelah_diskon = $('.total-setelah-diskon').val();
            let pajaklain1_persen = $('.pajaklain1-persen').val();

            pajaklain1_nominal = convertDouble(total_setelah_diskon) * pajaklain1_persen / 100;
            $('.pajaklain1-nominal').val(formatRibuan(pajaklain1_nominal));

            hitungGrandtotal();
        }

        // menghitung semua grandtotal
        function hitungGrandtotal() {
            const ppn = $('.ppn').val();
            let total_setelah_diskon = $('.total-setelah-diskon').val();
            let nilai_ppn = 0;
            let grandtotal = 0;
            let biaya_kirim = $('.biaya-kirim').val();

            let pajaklain1_nominal = $('.pajaklain1-nominal').val();

            //console.log(convertDouble(biaya_kirim) + convertDouble(total_setelah_diskon))
            grandtotal = convertDouble(total_setelah_diskon) + convertDouble(biaya_kirim) + convertDouble(pajaklain1_nominal);

            if (ppn == 1) {
                nilai_ppn = convertDouble(total_setelah_diskon) * 11 / 100;
                grandtotal += nilai_ppn;
            } else if (ppn == 2) {
                nilai_ppn = convertDouble(total_setelah_diskon) - (convertDouble(total_setelah_diskon) / 1.11);
            }

            // fill nilai ppn
            $('.nilai-ppn').val(formatRibuan(nilai_ppn));

            // fill grandtotal
            $('.grandtotal').val(formatRibuan(grandtotal));
        }

        hitungDiskonItemPersen();
        hitungSubtotal();
    });

    function hapusInvoice() {
        $('#modalHapus').modal('show');
    }
</script>
@endsection