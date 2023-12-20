@extends('layouts.vuexy')
@section('mycss')
    <style>
        .table td {
            padding: 0;
            vertical-align: middle;
        }
    </style>
@endsection

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

{{-- <form action="{{ url('admin/pmtpembelian') }}" enctype="multipart/form-data" method="POST"> --}}
<form id="form-save">
    <div class="row match-height">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <input type="hidden" value="{{ $type }}" name="type" id="">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="revisi_check" id="rev_check">
                        <label class="form-check-label" for="rev_check">
                            Pembuatan Revisi Permintaan
                        </label>
                    </div><br>
                    <div id="revisi_form">
                    </div>
                    <label>Nomer permintaan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nomer_pmtpembelian" readonly value="{{ $pmt_number }}">
                    <label>Tanggal permintaan<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal" value="{{ session()->has('pmtpembelian') ? session()->get('pmtpembelian')['tanggal'] : date('Y-m-d') }}">
                    
                    <label>Keterangan tambahan</label>
                    <textarea class="form-control" rows="4" name="keterangan">{{ session()->has('pmtpembelian') ? session()->get('pmtpembelian')['keterangan'] : '' }}</textarea>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Rincian</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="flag">
                    <thead>
                        <tr>
                            <th>Item <span class="text-danger">*</span></th>
                            @if($type == 4)
                            <th width="30%">Description Of Goods<span class="text-danger">*</span></th>
                            @endif
                            <th width="10%">QTY <span class="text-danger">*</span></th>
                            <th width="25%">Note <span class="text-danger">*</span></th>
                            <th width="15%">#</th>
                        </tr>
                    </thead>
                    <tbody class="flag_row">
                        <tr>
                            <td>
                                <select class="form-control selectNya" id="barang_id" name="barang_id[]" required>
                                    <option value="">-- Item --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            @if($type == 4)
                            <td>
                                <input type="text" class="form-control" name="description[]" id="description" placeholder="Masukan Deskripsi..." required>
                            </td>
                            @endif
                            <td>
                                <input type="number" class="form-control" <?php if($type != 1){ echo 'value="1"'; } ?> name="qty[]" id="qty" value="" placeholder="Masukan Qty..." required>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="note[]" id="note" value="-" placeholder="Masukan Note..." required>
                            </td>
                            <td>
                                <button type="button" style="width:100%" class="btn btn-outline-primary form-control btn-item">
                                    <i class="fa fa-plus"></i>Tambah
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9 col-12">
                            <label>Berkas</label>
                            <input type="file" name="berkasTemp" id="berkas_1" class="form-control">
                        </div>
                        <div class="col-md-3 col-12">
                            <label>Tambah Berkas</label>
                            <button type="button" class="btn form-control btn-outline-primary btn-tambah-berkas"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="tambah-berkas" style="width:100%">

                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <button class="btn btn-outline-secondary" id="btnSubmitAgain" data-target="lagi" type="submit">Simpan & Baru</button>
            <button class="btn btn-outline-primary" id="btnSubmit" data-target="simpan" type="submit"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
</form>
@endsection

@section('myjs')
<script type="text/javascript">
    //table
    var flagNya = 1;
    $('.btn-item').on('click', function() {
        var newRow = 
        '<tr class="flag_'+flagNya+'">'+
            '<td>'+
                '<select class="selectNya form-control" name="barang_id[]" required>'+
                    '<option value="">-- Item --</option>'+
                    @foreach($barangs as $barang)
                        '<option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>'+
                    @endforeach
                '</select>'+
            '</td>'+
            @if($type == 4)
            '<td>'+
                '<input type="text" class="form-control" name="description[]" id="description" placeholder="Masukan Deskripsi..." required>'+
            '</td>'+
            @endif
            '<td>'+
            
                '<input type="number" <?php if($type != 1){ echo 'value="1"'; } ?>  class="form-control" name="qty[]" placeholder="Masukan Qty..." required>'+
            '</td>'+

            '<td>'+
                '<input type="text" class="form-control" name="note[]" value="-" placeholder="Masukan Note..." required>'+
            '</td>'+
            '<td><button type="button" class="btn btn-outline-danger btn-hps-item btn-sm" style="width:100%"  data-flag="flag_'+flagNya+'"><i class="fa fa-trash"></i> Hapus</button></td>'+
        '</tr>';
        
        $('#flag tbody tr:last').after(newRow);
        $('.selectNya').select2();

        flagNya++;
    });
    $('.flag_row').on('click', '.btn-hps-item',function() {
        $('.'+$(this).data("flag")).remove();
    });

    //berkas
    var flag = 2;
    $('.btn-tambah-berkas').on('click', function() {
        var count = $("input:file").length;
        var berkas = count + 1;
        if(count >= 5){
            alert('Maaf! Berkas tidak boleh lebih dari 5');
        }else{
            $( ".tambah-berkas" ).append(
                '<div class="flag_'+flag+'">' +
                    '<div class="row">'+
                        '<div class="col-md-9 col-12" style="padding-left: 4%">'+
                            '<label>Berkas</label>'+
                            '<input type="file" name="berkasTemp" id="berkas_'+flag+'" class="form-control" style="width:98%">'+
                        '</div>'+
                        '<div class="col-md-3 col-12" style="padding-top: 4%">'+
                            '<button type="button" class="btn btn-danger btn-sm btn-hapus-berkas" data-id="flag_'+flag+'"><i class="fa fa-trash"></i> Hapus</button>'+
                        '</div>'+
                    '</div>'+
                '</div>'   
            );
            flag++;
        }

    });
    $('.tambah-berkas').on('click','.btn-hapus-berkas',function() {
        // $(this).parent().remove();
        $('.'+$(this).data("id")).remove();
    });
    //sampai sini

    //submit form
    $('body').on('submit', '#form-save', function(e) {
        e.preventDefault();
        let target = e.originalEvent.submitter.getAttribute('data-target');
        Swal.fire({
            title: 'Apakah Anda yakin ingin menyimpan data?',
            text: "Dengan menyimpan data, berkas yang di upload tidak bisa di hapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Saya yakin!',
            customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                var formData = new FormData(document.getElementById("form-save"));

                $.each($("input[type=file]"), function(i, obj) {
                        $.each(obj.files,function(j, file){
                            formData.append('berkas[]', file);
                            
                        }) 
                });

                
                formData.append('target', target);

                $('#btnSubmit').html('<i class="mr-1 fa fa-spinner fa-spin"></i> Loading...');
                document.getElementById("btnSubmit").disabled = true;
                $.ajax({
                    type: 'post',
                    url: "{{ route('admin.pmtpembelian.store') }}",
                    enctype: 'multipart/form-data',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (data) => {
                        $('#btnSubmit').html('Simpan');
                        if (data.nomer) {
                            Swal.fire({
                                title: 'Error!',
                                text: data.errors,
                                icon: 'error',
                                customClass: {
                                confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                            $('#btnSubmit').html('Simpan');
                            document.getElementById("btnSubmit").disabled = false;
                        }else if(data == 'LAGI'){
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil dibuat',
                                icon: 'success',
                                customClass: {
                                confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(function() {
                                window.location = "{{ route('admin.pmtpembelian.create') }}";
                            });
                        }else{
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil dibuat',
                                icon: 'success',
                                customClass: {
                                confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(function() {
                                window.location = "{{ route('admin.pmtpembelian.index') }}";
                            });
                        }
                    },
                    error: function(data) {
                        Swal.fire({
                            title: 'Error!',
                            text: "Error pada server, Silahkan hubungi Administrator!",
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                        $('#btnSubmit').html('Simpan');

                        document.getElementById("btnSubmit").disabled = false;
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Batal',
                    text: 'Data tidak tersimpan',
                    icon: 'error',
                    customClass: {
                    confirmButton: 'btn btn-success'
                    }
                });
            
            }
        });
    });

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