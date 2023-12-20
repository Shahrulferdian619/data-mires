@extends('layouts.vuexy')

@section('header')
Permintaan Pembelian Baru
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="/admin/pmtpembelian">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{ url('admin/pmtpembelian') }}" enctype="multipart/form-data" method="POST">
<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                @csrf
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="revisi_check" id="rev_check">
                    <label class="form-check-label" for="rev_check">
                        Pembuatan Revisi Permintaan
                    </label>
                </div><br>
                <div id="revisi_form">
                </div>
                <label>Nomer permintaan<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nomer_pmtpembelian" value="{{ session()->has('pmtpembelian') ? session()->get('pmtpembelian')['nomer_pmtpembelian'] : '' }}">
                
                <label>Tanggal permintaan<span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="tanggal" value="{{ session()->has('pmtpembelian') ? session()->get('pmtpembelian')['tanggal'] : date('Y-m-d') }}">
                
                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" name="keterangan">{{ session()->has('pmtpembelian') ? session()->get('pmtpembelian')['keterangan'] : '' }}</textarea>

            </div>
        </div>
    </div>
</div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <tr id="produk0">
                        <td style="width: 30%;">
                            <select class="form-control" id="barang_id" name="barang_id">
                                <option value="">-- PRODUK --</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td hidden style="width: 25%;">
                            <input type="text" hidden placeholder="Harga..." class="form-control" name="harga" value="0" id="harga">
                        </td>
                        <td style="width: 15%;">
                            <input type="text" placeholder="Qty..." class="form-control" name="qty" id="qty">
                        </td>
                        <td style="width: 25%;">
                            <input type="text" placeholder="Note..." class="form-control" name="note" id="note" value="-">
                        </td>
                    </tr>
                </table>
            </div>
                <br>
                <div class="float-right">
                    {{-- <button class="btn btn-sm btn-danger btn-next" id="tambah-produk"> --}}
                    <button class="btn btn-sm btn-danger btn-next" name="tambah_rinci">
                        <i data-feather="plus" class="align-middle"></i> tambah
                    </button>
                </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Note</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    @php
                        $grand_total = 0;
                    @endphp
                    <tbody id="show_produk">
                        @if (session()->has('pmtpembelian_rinci'))
                            @foreach (session()->get('pmtpembelian_rinci') as $key => $value)
                            <tr>
                                <td>{{ $value['nama_barang'] }}</td>
                                <td>{{ $value['qty'] }}</td>
                                <td>{{ $value['note'] }}</td>
                                <td>
                                    <a href="{{ url('admin/pmtpembelian/hapus-rincian/' . $key) }}" class="btn btn-outline-warning btn-sm">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
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
            @if (session()->has('pmtpembelian_rinci'))
                @if (count(session()->get('pmtpembelian_rinci')) > 0)    
                <button class="btn btn-primary" name="tambah_pmtpembelian">
                    <i class="fa fa-save"></i> Simpan
                </button>
                @endif
            @endif
        </div>
    </div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">

    function call_ppn()
    {
        get_temp_produk()
    }

    function get_temp_produk()
    {
        $.ajax({
            url: "/admin/pmtpembelian/get-cart-produk",
            method: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data.data)
                let html = '';
                let i;
                let subtotal = 0;
                let grand_total = 0;

                for(i = 0; i < data.data.carts.length; i++) {
                    html += '<tr>' + 
                            '<td>' + data.data.carts[i].nama_barang + '</td>' + 
                            '<td>' + numeral(data.data.carts[i].harga).format() + '</td>' + 
                            '<td>' + data.data.carts[i].qty + '</td>' + 
                            '<td>' + numeral((data.data.carts[i].qty * data.data.carts[i].harga)).format() + '</td>' + 
                            '<td>' + data.data.carts[i].note + '</td>' + 
                            '<td>' + 
                            '<button type="button" class="btn btn-outline-warning btn-sm" onclick="del_produk_temp('+data.data.carts[i].id+')">hapus</button>'
                            '</td>' + 
                            '</tr>';

                    subtotal += (data.data.carts[i].qty * data.data.carts[i].harga)
                }
                $('#show_produk').html(html);

                //subtotal
                $('#subtotal').text('Sub Total : ' + numeral(subtotal).format())

                //ppn 10%
                if($('#ppn').val() == 0) {
                    grand_total = subtotal
                    $('#ppn_text').text('PPn 10% : ')
                    $('#grand_total').text('Grand Total : ' + numeral(grand_total).format())
                } else {
                    grand_total = (subtotal * 10 / 100) + subtotal
                    ppn = subtotal * 10 /100
                    $('#ppn_text').text('PPn 10% : ' + numeral(ppn).format())
                    $('#grand_total').text('Grand Total : ' + numeral(grand_total).format())
                }
            }
        })
    }

    function del_produk_temp(id)
    {
        $.ajax({
            url: "/admin/pmtpembelian/del-produk/" + id,
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                get_temp_produk()
            },
            error: function(e) {

            }
        })
    }
    $(document).ready(function() {
        // get_temp_produk()
        $('#tambah-produk').on('click', function(el) {
            el.preventDefault()

            if($('#barang_id').val() == "" || $('#barang').val() == "" || $('#qty').val() == "") {
                alert("Produk, harga, dan qty harus diisi...")
            } else {
                $.ajax({
                    url: "/admin/pmtpembelian/tambah-rinci",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        barang_id: $('#barang_id').val(),
                        harga: $('#harga').val(),
                        qty: $('#qty').val(),
                        note: $('#note').val(),
                    },
                    success: function(res) {
                        get_temp_produk()
                        $('#harga').val('')
                        $('#qty').val('')
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            }
        })












        

        let html_rev = `<label>Reverensi No. PMT</label>
                            <select name="pmt_id" class="form-control">
                                <option value="0">-- Pilih Referensi --</option>
                                <?php foreach($pmt as $val){
                                    if(!empty(session()->get('pmtpembelian')['reff_pmt'])){
                                        if(session()->get('pmtpembelian')['reff_pmt'] == $val->id){
                                            echo '<option value="'.$val->id.'" selected>'.$val->nomer_pmtpembelian.'</option>';
                                        }else{
                                            echo '<option value="'.$val->id.'">'.$val->nomer_pmtpembelian.'</option>';
                                        } 
                                    }else{
                                        echo '<option value="'.$val->id.'">'.$val->nomer_pmtpembelian.'</option>';
                                    }
                                    
                                }?>
                            </select>`;
        $('#rev_check').on('click', function(){
            if($('#rev_check').attr('checked') != 'checked'){
                $('#rev_check').attr('checked', true);
                $('#revisi_form').html(html_rev);
            }else{
                $('#rev_check').attr('checked', false);
                $('#revisi_form').empty();
            }
        })
        @if(!empty(session()->get('pmtpembelian')['reff_pmt']))
            $('#rev_check').attr('checked', true);
            $('#revisi_form').html(html_rev);
        @endif

        
    })
</script>
@endsection