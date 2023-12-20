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

<a href="{{ route('pembelian.penerimaan-barang.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{route('pembelian.penerimaan-barang.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Data Penerimaan Barang -->
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Penerimaan Barang
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Pilih pesanan pembelian (PO)</label>
                    <select name="pesanan_pembelian_id" id="pilih-po" class="form-control select2" onchange="pilihPO()">
                        <option value="" disabled selected>-- PILIH PO --</option>
                        @foreach($data['pesananPembelian'] as $po)
                        <option value="{{ $po->id }}">{{ $po->nomer_pesanan_pembelian }}</option>
                        @endforeach
                    </select>

                    <label for="">Nomer penerimaan barang*</label>
                    <input type="text" name="nomer_penerimaan_barang" class="form-control" required>

                    <label for="">Tanggal*</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" cols="30" rows="6" class="form-control" placeholder="kosongkan bila tidak perlu"></textarea>
                </div>
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
                        <tr>
                            <th>item*</th>
                            <th>deskripsi item</th>
                            <th style="width: 100px;">qty*</th>
                            <th style="width: 200px;">catatan</th>
                            <th style="width: 50px;">#</th>
                        </tr>
                    </thead>
                    <tbody id="rincian-item">

                    </tbody>
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
                <table class="table table-borderless" id="tabel-berkas">
                    <tr class="berkas-0">
                        <td>Berkas</td>
                        <td>
                            <input type="file" name="berkas[0][nama_berkas]" class="form-control">
                        </td>
                        <td>
                            <button type="button" onclick="tambahBerkas()" class="btn btn-outline-success btn-tambah-berkas">
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
            <a href="{{ route('pembelian.penerimaan-barang.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>

<!-- Modal PO -->
<div class="modal fade" id="modalPurchaseOrder" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">INFORMASI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>pilih</th>
                                <th>nomer</th>
                                <th>tanggal</th>
                                <th>supplier</th>
                                <th>keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="rincianPO">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary" onclick="tambahPO()">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('custom_js')
<script type="text/javascript">
    let indexBerkas = 1;

    function pilihPO() {
        let po_id = $('#pilih-po').val();

        // get data PO rinci
        $.ajax({
            method: "GET",
            url: "/v2/pembelian/pesanan-pembelian/getDetil/" + po_id,
            dataType: "JSON",
            success: function(data) {
                // hapus rincian item sebelumnya
                $('#rincian-item').find('tr').remove();

                let rincian = data.rincian_item;
                let rincianHtml = '';
                for (i = 0; i < rincian.length; i++) {
                    rincianHtml += `<tr>
                                        <td>
                                            <select name="rincian[${i}][item_id]" id="" class="form-control bg-light">
                                                <option value="${rincian[i].item_id}">${rincian[i].item.nama_barang}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="rincian[${i}][deskripsi_item]" class="form-control bg-light" value="${rincian[i].deskripsi_item}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="rincian[${i}][kuantitas]" class="form-control" value="${rincian[i].kuantitas - rincian[i].kuantitas_diterima}">
                                        </td>
                                        <td>
                                            <input type="text" name="rincian[${i}][catatan]" class="form-control" value="${rincian[i].catatan}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-hapus-item" onclick="hapusItem(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                }

                //console.log(rincianHtml);
                $('#rincian-item').append(rincianHtml);

            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function tambahBerkas() {
        const rowBerkas = `<tr class="berkas-${indexBerkas}">
                                <td>Berkas</td>
                                <td>
                                    <input type="file" name="berkas[${indexBerkas}][nama_berkas]" class="form-control">
                                </td>
                                <td>
                                    <button type="button" onclick="hapusBerkas(this)" class="btn btn-outline-danger btn-tambah-berkas">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;

        $('#tabel-berkas').append(rowBerkas);
        indexBerkas++;
    }

    function hapusBerkas(button) {
        button.closest('tr').remove();
    }

    function hapusItem(button) { // menghapus baris rincian item
        button.closest('tr').remove();
    }

    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection