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

{{-- <form action="{{ url('admin/pmtpembelian/update/' . $pmtpembelian->id) }}" method="POST" enctype="multipart/form-data"> --}}
<form id="form-save">
<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <label>Nomer permintaan</label>
                <input type="text" class="form-control" name="nomer_pmtpembelian" value="{{ $pmtpembelian->nomer_pmtpembelian }}" required>
                
                <label>Tanggal permintaan</label>
                <input type="date" class="form-control" name="tanggal" value="{{ $pmtpembelian->tanggal  }}" required>
                
                <label>Keterangan tambahan</label>
                <textarea class="form-control" rows="4" name="keterangan" >{{ $pmtpembelian->keterangan }}</textarea>
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
                            <th>Barang <span class="text-danger">*</span></th>
                            <th width="10%">QTY <span class="text-danger">*</span></th>
                            <th width="25%">Note <span class="text-danger">*</span></th>
                            <th width="10%">#</th>
                        </tr>
                    </thead>
                    <tbody class="flag_row">
                        @foreach ($pmtpembelian->rinci as $item)
                            <tr>
                                <td>
                                    <input type="hidden" name="pmtpembelian_rinci_id[]" value="{{ $item->id }}" id="">
                                    <select class="form-control selectNya" id="barang_id" name="barang_id[]" required>
                                        <option value="">-- PRODUK --</option>
                                        @foreach($barang as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == $item->barang_id ? "selected" : "" }}>{{ $row->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" name="qty[]" id="qty" value="{{ $item->qty }}" placeholder="Masukan Qty..." required></td>
                                <td><input type="text" class="form-control" name="note[]" id="note" value="{{ $item->note }}" placeholder="Masukan Note..." required></td>
                                <td>
                                    <button onclick="destroyRinci(this)" data-id="{{ $item->id }}" style="width:100%" class="btn btn-outline-danger btn-sm" type="button"><i class="fa fa-trash"></i> Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <input type="hidden" name="pmtpembelian_rinci_id[]" value="" id="">
                                <select class="form-control selectNya" id="barang_id" name="barang_id[]">
                                    <option value="">-- PRODUK --</option>
                                    @foreach($barang as $row)
                                        <option value="{{ $row->id }}">{{ $row->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="qty[]" id="qty" value="" placeholder="Masukan Qty...">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="note[]" id="note" value="-" placeholder="Masukan Note...">
                            </td>
                            <td>
                                <button type="button" style="width:100%" class="btn btn-outline-primary btn-sm btn-item">
                                    <i class="fa fa-plus"></i>
                                    Tambah
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
                        <div class="col-md-12">
                            <span><b>* Abaikan input file jika tidak ingin mengubah file.</b></span>
                            @if (!empty($berkas))
                            <br>
                            <br>
                            <p>List file:</p>
                                <ul>
                                    <li>Berkas 1 : {{ $berkas->berkas_1 == "" ? "Tidak ada" : $berkas->berkas_1 }}</li>
                                    <li>Berkas 2 : {{ $berkas->berkas_2 == "" ? "Tidak ada" : $berkas->berkas_2 }}</li>
                                    <li>Berkas 3 : {{ $berkas->berkas_3 == "" ? "Tidak ada" : $berkas->berkas_3 }}</li>
                                    <li>Berkas 4 : {{ $berkas->berkas_4 == "" ? "Tidak ada" : $berkas->berkas_4 }}</li>
                                    <li>Berkas 5 : {{ $berkas->berkas_5 == "" ? "Tidak ada" : $berkas->berkas_5 }}</li>
                                </ul>
                            @endif
                        </div>
                        <div class="col-md-9 col-12">
                            <label>Berkas</label>
                            <input type="file" name="berkasTemp" id="berkas_1" class="form-control">
                        </div>
                        <div class="col-md-3 col-12" style="padding-top: 4%">
                            <button type="button" class="btn btn-primary btn-sm btn-tambah-berkas"><i class="fa fa-plus"></i> Tambah</button>
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
            <button class="btn btn-outline-primary" id="btnSubmit" type="submit"><i class="fa fa-save"></i> Simpan</button>
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
                '<input type="hidden" name="pmtpembelian_rinci_id[]" value="" id="">'+
                '<select class="selectNya form-control" name="barang_id[]" required>'+
                    '<option value="">-- PRODUK --</option>'+
                    @foreach($barang as $row)
                        '<option value="{{ $row->id }}">{{ $row->nama_barang }}</option>'+
                    @endforeach
                '</select>'+
            '</td>'+
            '<td>'+
                '<input type="number" class="form-control" name="qty[]" placeholder="Masukan Qty..." required>'+
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
        var formData = new FormData(document.getElementById("form-save"));

        $.each($("input[type=file]"), function(i, obj) {
                $.each(obj.files,function(j, file){
                    formData.append('berkas[]', file);
                    
                }) 
        });

        $('#btnSubmit').html('<i class="mr-1 fa fa-spinner fa-spin"></i> Loading...');
        document.getElementById("btnSubmit").disabled = true;

        $.ajax({
            type: 'post',
            url: "{{ route('admin.pmtpembelian.update', ['pmtpembelian' => $pmtpembelian->id]) }}",
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
                if (data.tidak_balance || data.sebaris || data.nomer || data.rincian) {
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
                }
                else{
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Data berhasil diubah',
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

        
    });

    //Hapus Rincian
    function destroyRinci(e){
        var id = $(e).attr('data-id');
        
        $.ajax({
            type: 'post',
            url: "{{ route('admin.pmtpembelian.destroy.rinci') }}",
            data: {
                id:id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (data) => {
                Swal.fire({
                        title: 'Sukses!',
                        text: 'Salah satu rincian berhasil dihapus',
                        icon: 'success',
                        customClass: {
                        confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    }).then(function() {
                        location.reload();
                    });
               
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
            },
        });
       
    }


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