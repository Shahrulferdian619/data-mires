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
    <ul>
        <li>Merevisi data tidak akan menghapus data lama</li>
        <li>Nomer yang direvisi akan diubah menjadi _cancel</li>
    </ul>
</div>

<a href="{{ route('pembelian.pesanan-pembelian.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pembelian.pesanan-pembelian.create-revisi', $pesananPembelian->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <!-- Data Pesanan -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Pesanan Pembelian
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderless table-striped">
                    <tr>
                        <td style="width: 300px;">Pilih permintaan pembelian*</td>
                        <td>
                            <select name="permintaan_pembelian_id" class="form-control select2 permintaan-pembelian" required>
                                <option value="{{ $pesananPembelian->permintaan_pembelian_id }}">{{ $pesananPembelian->permintaanPembelian->nomer_permintaan_pembelian }}</option>
                                @foreach($data['permintaanPembelian'] as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->nomer_permintaan_pembelian }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Supplier*</td>
                        <td>
                            <select name="supplier_id" class="form-control select2" required>
                                <option value="{{ $pesananPembelian->supplier_id }}">{{ $pesananPembelian->supplier->kode }} | {{ $pesananPembelian->supplier->nama }}</option>
                                @foreach($data['supplier'] as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->kode }} | {{ $supplier->nama }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Nomer pesanan pembelian*</td>
                        <td>
                            <input type="text" name="nomer_pesanan_pembelian" class="form-control" value="{{ $pesananPembelian->nomer_pesanan_pembelian }}" placeholder="Ex: PO/I/2023/00001" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal*</td>
                        <td>
                            <input type="date" name="tanggal" class="form-control" value="{{ $pesananPembelian->tanggal }}" required>
                        </td>
                    </tr>
                    <tr>
                        <td>PPn 11%*</td>
                        <td>
                            <select name="ppn" class="form-control select2 ppn">
                                <option value="{{ $pesananPembelian->ppn }}">
                                    @if($pesananPembelian->ppn == 0)
                                    Tidak
                                    @elseif($pesananPembelian->ppn == 1)
                                    Ya
                                    @else
                                    Include
                                    @endif
                                </option>
                                <option value="1">Ya</option>
                                <option value="2">Include</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>
                            <textarea name="keterangan" cols="30" rows="5" class="form-control" placeholder="Opsional">{{ $pesananPembelian->keterangan }}</textarea>
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
                            <th style="width: 275px;">item*</th>
                            <th style="width: 150px;">deskripsi item</th>
                            <th style="width: 100px;">qty*</th>
                            <th style="width: 120px;">harga*</th>
                            <th style="width: 85px;">dsc(%)</th>
                            <th style="width: 120px;">dsc(Rp)</th>
                            <th style="width: 150px;">subtotal</th>
                            <th style="width: 150px">catatan</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-item">
                        @php $i = 0 @endphp
                        @foreach($data['rincianItem'] as $rincian)
                        <tr>
                            <td>
                                <select name="rincian[{{$i}}][item_id]" class="form-control item" readonly required>
                                    <option value="{{ $rincian->item_id }}">{{ $rincian->item->nama_barang }}</option>
                                </select>
                            </td>
                            <td>
                                <textarea name="rincian[{{$i}}][deskripsi_item]" cols="30" rows="3" class="form-control">{{ $rincian->deskripsi_item }}</textarea>
                            </td>
                            <td>
                                <input type="number" name="rincian[{{$i}}][kuantitas]" min="1" class="form-control kuantitas" value="{{ $rincian->kuantitas }}" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][harga]" min="0" class="form-control harga" value="{{ $rincian->harga }}" required>
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][diskon_persen]" min="0" step="0.01" class="form-control diskon-persen" value="{{ $rincian->diskon_persen }}">
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][diskon_nominal]" min="0" class="form-control diskon-nominal" value="{{ $rincian->diskon_nominal }}">
                            </td>
                            <td>
                                <input type="text" name="rincian[{{$i}}][subtotal]" class="form-control subtotal bg-light" readonly>
                            </td>
                            <td>
                                <textarea name="rincian[{{$i}}][catatan]" cols="30" rows="3" class="form-control">{{ $rincian->catatan }}</textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger btn-hapus-item">
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
                        <td style="width: 300px;">Total</td>
                        <td>
                            <input type="text" name="total" class="form-control bg-light total" value="0" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Biaya kirim</td>
                        <td>
                            <input type="number" name="biaya_kirim" class="form-control biaya-kirim" value="{{ $pesananPembelian->biaya_kirim }}" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td>Dsc global(%)</td>
                        <td>
                            <input type="number" name="diskon_persen_global" class="form-control diskon-persen-global" min="0" step="0.01" value="{{ $pesananPembelian->diskon_persen_global }}">
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
                @if($pesananPembelian->rincianBerkas()->exists())
                <table class="table table-borderless">
                    @foreach($pesananPembelian->rincianBerkas as $rincian)
                    <tr>
                        <td>Berkas</td>
                        <td>
                            <a href="{{ route('pembelian.pesanan-pembelian.download-berkas',$rincian->nama_berkas) }}">{{ $rincian->nama_berkas }}</a>
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
            <a href="{{ route('pembelian.pesanan-pembelian.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

@endsection

@section('custom_js')
<script type="text/javascript">
    $(document).ready(function() {

        let rowBerkas = 1; //inisialisasi awal untuk menambah baris berkas

        // deklarasi variabel
        const $tabelRincianItem = $('#rincian-item');

        $('.select2').select2();

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

        // proses select permintaan pembelian
        $('.permintaan-pembelian').on('change', function(e) {
            const permintaan_id = $(this).val();

            $.ajax({
                type: "get",
                url: "{{ route('pembelian.permintaan-pembelian.get-detil') }}",
                data: {
                    "permintaan_id": permintaan_id
                },
                dataType: "json",
                success: function(response) {
                    $('#rincian-item').find('tr').remove();

                    let data = response.rincian_permintaan;
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
                                                <input type="number" name="rincian[${i}][kuantitas]" min="1" class="form-control kuantitas" value="${data[i].kuantitas - data[i].kuantitas_diproses}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="rincian[${i}][harga]" min="0" class="form-control harga" value="${data[i].harga === null ? "0" : data[i].harga}" required>
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

        // mengganti ppn
        $(document).on('change', '.ppn', function() {
            hitungGrandtotal();
        });

        // fungsi untuk merubah persen diskon item menjadi rupiah
        function hitungDiskonItemPersen() {
            $tabelRincianItem.find('tr').each(function() {
                const $row = $(this);
                const harga = $row.find('.harga').val();
                const diskon_persen = $row.find('.diskon-persen').val();
                const diskon_nominal = harga * diskon_persen / 100;

                // fill diskon nominal
                $row.find('.diskon-nominal').val(formatRupiah(diskon_nominal));
            });

            hitungSubtotal();
        }

        // fungsi untuk merubah nominal diskon item menjadi persen
        function hitungDiskonItemNominal() {
            $tabelRincianItem.find('tr').each(function() {
                const $row = $(this);
                const harga = $row.find('.harga').val();
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
            const diskon_nominal_global = formatDouble(total) * diskon_persen_global / 100;
            let total_setelah_diskon = 0;

            // fill diskon nominal global
            $('.diskon-nominal-global').val(formatRupiah(diskon_nominal_global));

            // fill total setelah diskon
            total_setelah_diskon = formatDouble(total) - diskon_nominal_global;
            $('.total-setelah-diskon').val(formatRupiah(total_setelah_diskon));

            hitungGrandtotal();
        }

        // hitung ulang grandtotal ketika ada perubahan input biaya kirim
        $(document).on('input', '.biaya-kirim', function() {
            hitungGrandtotal();
        });

        // fungsi untuk menghitung subtotal
        function hitungSubtotal() {
            let total = 0;

            $tabelRincianItem.find('tr').each(function() {
                const $row = $(this);
                const kuantitas = $row.find('.kuantitas').val();
                const harga = $row.find('.harga').val();
                const diskon_nominal = $row.find('.diskon-nominal').val();
                const subtotal = (harga - formatDouble(diskon_nominal)) * kuantitas; //hitung subtotal
                total += subtotal;

                // fill subtotal
                $row.find('.subtotal').val(formatRupiah(subtotal));
            });

            // fill total
            $('.total').val(formatRupiah(total));

            hitungDiskonPersenGlobal();
            hitungGrandtotal();
        }

        // menghitung semua grandtotal
        function hitungGrandtotal() {
            const ppn = $('.ppn').val();
            let total_setelah_diskon = $('.total-setelah-diskon').val();
            let nilai_ppn = 0;
            let grandtotal = 0;
            let biaya_kirim = $('.biaya-kirim').val();

            grandtotal = formatDouble(total_setelah_diskon) + parseFloat(biaya_kirim);

            if (ppn == 1) {
                nilai_ppn = formatDouble(total_setelah_diskon) * 11 / 100;
                grandtotal += nilai_ppn;
            } else if (ppn == 2) {
                nilai_ppn = formatDouble(total_setelah_diskon) - (formatDouble(total_setelah_diskon) / 1.11);
            }
            // fill nilai ppn
            $('.nilai-ppn').val(formatRupiah(nilai_ppn));

            // fill grandtotal
            $('.grandtotal').val(formatRupiah(grandtotal));
        }

        function formatRupiah(nominal) {
            return nominal.toLocaleString('id-ID');
        }

        // merubah ke format double dari rupiah
        function formatDouble(nominal) {
            // Menghapus karakter non-numerik (spasi, Rp, titik, koma ribuan)
            var numberString = nominal.replace(/[^\d,.-]/g, '');

            // Mengganti koma ribuan dengan tanda kosong
            numberString = numberString.replace(/\./g, '');

            // Mengganti koma desimal dengan titik desimal
            numberString = numberString.replace(",", ".");

            // Mengonversi string menjadi tipe data double
            var result = parseFloat(numberString);

            return result;
        }

        hitungSubtotal();
    });
</script>
@endsection