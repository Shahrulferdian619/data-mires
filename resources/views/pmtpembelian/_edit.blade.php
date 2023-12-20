@extends('layouts.vuexy')

@section('header')
Edit permintaan pembelian
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="{{ url('admin/pmtpembelian') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{ url('admin/pmtpembelian/update/' . $pmtpembelian->id) }}" method="POST" enctype="multipart/form-data">
<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                @csrf
                <label>Nomer permintaan</label>
                <input type="text" class="form-control" name="nomer_pmtpembelian" value="{{ $pmtpembelian->nomer_pmtpembelian }}" readonly required>
                
                <label>Tanggal permintaan</label>
                <input type="date" class="form-control" name="tanggal" value="{{ $pmtpembelian->tanggal  }}" readonly required>
                
                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" name="keterangan" >{{ $pmtpembelian->keterangan }}</textarea>
            </div>
        </div>
    </div>
</div>

    <div class="card">
        <div class="card-body">
            <table>
                <tr id="produk0">
                    <td style="width: 30%;">
                        <select class="form-control" name="barang_id" id="barang_id">
                            <option value="">-- PRODUK --</option>
                            @foreach($barang as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width: 25%;">
                        <input type="text" placeholder="Harga..." class="form-control" name="harga" id="harga">
                    </td>
                    <td style="width: 15%;">
                        <input type="text" placeholder="Qty..." class="form-control" name="qty" id="qty">
                    </td>
                    <td style="width: 25%;">
                        <input type="text" placeholder="Note..." class="form-control" name="note" id="note" value="-">
                        <input type="hidden" name="rincian_id" id="rincian_id" value="0">
                    </td>
                </tr>
            </table>

            <br>

            <div class="float-right">
                <button class="btn btn-sm btn-danger btn-next" name="add-new-product" id="tambah-produk">
                    <i data-feather="plus" class="align-middle"></i> tambah
                </button>
                <div id="ubah-area" class="d-none" >
                    <button class="btn btn-sm btn-success btn-next" name="update-product" id="ubah-produk">
                        ubah
                    </button>
                    <button class="btn btn-sm btn-danger btn-next" id="ubah-batal">
                        batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Sub total</th>
                        <th>Note</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody id="show_produk">
                    @foreach ($pmtpembelian->rinci as $item)
                        <tr>
                            <td>{{ $item->barang->nama_barang }}</td>
                            <td>{{ $item->harga }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->harga * $item->qty }}</td>
                            <td>{{ $item->note }}</td>
                            <td>
                                <button type="button" id="edit" data-id="{{ $item->id }}" class="btn btn-outline-info btn-sm">Ubah</button>
                                <a href="{{ url('admin/pmtpembelian/hapus-data-rincian/' . $item->id) }}" class="btn btn-outline-warning btn-sm">hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>

            <div class="float-right">
                <label for="" id="subtotal"></label>
                <br>
                <label for="" id="ppn_text"></label>
                <br>
                <label id="grand_total"></label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Berkas 1</label>
                            <input type="file" name="berkas_1" id="berkas_1" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 2</label>
                            <input type="file" name="berkas_2" id="berkas_2" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 3</label>
                            <input type="file" name="berkas_3" id="berkas_3" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 4</label>
                            <input type="file" name="berkas_4" id="berkas_4" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Berkas 5</label>
                            <input type="file" name="berkas_5" id="berkas_5" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <button class="btn btn-primary" name="update-pmt">
                <i class="fa fa-save"></i> Simpan
            </button>
        </div>
    </div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">


    let editBarang = document.querySelector('#edit')
    // button click action
    let areaUbah = document.querySelector('#ubah-area')
    let btnTambahProduk = document.querySelector('#tambah-produk')
    let btnUbahProduk = document.querySelector('#ubah-produk')
    let btnBatalUbah = document.querySelector('#ubah-batal')

    // input action 
    let harga = document.querySelector('#harga')
    let qty = document.querySelector('#qty')
    let note = document.querySelector('#note')
    let rincianID = document.querySelector('#rincian_id')
    let barangID = document.querySelector('#barang_id')

    editBarang.onclick = function(e){
        //  
        btnTambahProduk.classList.add('d-none')
        areaUbah.classList.remove('d-none')
        // 
        let id = e.target.getAttribute('data-id')
        const xhttp = new XMLHttpRequest()
        xhttp.onload = function(){
            let data = JSON.parse(this.responseText)
            harga.value = data.harga
            qty.value = data.qty
            note.value = data.note
            rincianID.value = data.id
            for (let i = 0; i < barangID.options.length; i++) {
                if(barangID.options[i].value == data.barang.id){
                    barangID.options[i].selected = true
                }
            }
        }
        xhttp.open('GET', '/api/get-pmtpembelian-rinci' + '/' + id)
        xhttp.send()

    }

    btnBatalUbah.onclick = function(e){
        e.preventDefault()
        areaUbah.classList.add('d-none')
        btnTambahProduk.classList.remove('d-none')

        harga.value = ''
        qty.value = ''
        note.value = '-'
        rincianID.value = 0
        barangID.options.selectedIndex = 0
    }

</script>
@endsection