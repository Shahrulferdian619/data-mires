@extends('layouts.vuexy')

@section('header')
Stock Adjusment (Penyesuaian Stock)
@endsection

@section('content')
@if($errors->all())
@include('layouts.validation')
@endif

<a href="{{ url('admin/list-inventory/all') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>
@if($errors->all())
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
            @if(!empty(session('error')))
            <li>{{ session('error') }}</li>
            @endif
        </ul>
    </div>
</div>
@endif
@if(session()->has('error'))
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session()->get('error') }}</li>
        </ul>
    </div>
</div>
@endif

<form id="form-save" action="{{ url('admin/stock-opname') }}" method="POST">
    <div class="row match-height">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <label>Nomor Transaksi<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nomor" name="nomer" value="{{ old('nomer') }}" required>

                    <label>Tanggal <span class="text-danger">*</span></label>
                    <input readonly type="date" id="tanggal" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>

                    <label>Pilih Gudang <span class="text-danger">*</span></label>
                    <select class="select2 form-select form-control" name="gudang_id" id="gudang_id">
                        <option value="0">-- Pilih Gudang --</option>
                        @foreach($gudang as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_gudang }}</option>
                        @endforeach
                    </select>

                    <label>Deskripsi</label>
                    <textarea class="form-control" rows="4" name="deskripsi">{{ old('deskripsi') }}</textarea>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Rincian Barang</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- <table class="table" id="flag">
                                <thead>
                                    <tr>
                                        <th width="25%">Lokasi Gudang</th>
                                        <th>Jumlah Barang (Sistem)</th>
                                        <th>Jumlah Barang (Riil) <small class="text-danger"></small> </th>
                                    </tr>
                                </thead>
                                <tbody id="adjusment">
                                    
                                </tbody>
                            </table> -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang Riil</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="form">

                                <tr>
                                    <td>
                                        <select name="barang_id[]" class="form-control barang_id" id="barang">
                                            @foreach($barang as $barang1)
                                            <option value="{{ $barang1->id }}">{{ $barang1->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="jumlah_opname[]" id="flag_1">
                                    </td>
                                    <td>
                                        <div style="width:100px" class="btn btn-sm btn-outline-primary" id="btnAdd">Tambah</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-outline-primary" id="btnSimpan" type="submit">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('myjs')
<script>
    $('#btnSimpan').on('click', function(e) {
        e.preventDefault();
        if ($('#nomor').val() == '' || $('#tanggal').val() == '' || $('#gudang_id').val() == 0) {
            return Swal.fire({
                title: 'Batal',
                text: 'Data Belum Lengkap!',
                icon: 'error',
                customClass: {
                    confirmButton: 'btn btn-success'
                }
            });
        }
        Swal.fire({
            title: 'Apakah Anda yakin ingin menyimpan data?',
            text: "Dengan menyimpan data, anda telah yakin tidak ada kesalahan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Saya yakin!',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $('#form-save').submit();
            } else if (data == 'LAGI') {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil dibuat',
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                }).then(function() {
                    window.location = "{{ url('admin/list-inventory') }}";
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

    $('#barang_id').on('change', function(el) {
        $.ajax({
            method: 'GET',
            url: '/admin/get_barang_by_gudang/' + $('#barang_id').val(),
            dataType: 'JSON',
            success: function(data) {
                console.log(data)
                let html = ''
                data.map((item, index) => {
                    html += `
                    <tr>
                        <td>
                            <input disabled type="text" class="form-control" value="${item.gudang}">
                            <input hidden type="text" name="id[{${item.gudang_id}}]" class="form-control" value="${item.gudang_id}">
                            <input hidden name="gudang_id[${item.gudang_id}]" type="text" class="form-control" value="${item.gudang_id}">
                        </td>
                        <td><input readonly type="text" name="jumlah[${item.gudang_id}]" class="form-control" value="${item.jumlah_barang}"></td>
                        <td><input type="number" name="jumlah_adjusment[${item.gudang_id}]" value="${item.jumlah_barang}" class="form-control"></td>
                    </tr>
                    `
                })
                $('#adjusment').html(html)
            }
        })
    })

    let flag = 1;
    $('#btnAdd').on('click', function(e) {
        flag++;

        let option = '';

        $("#form tr:last").after(
            '<tr id="flag_' + flag + '">'+
                '<td>'+
                    '<select name="barang_id[]" class="form-control barang_id">'+
                    @foreach($barang as $barang2)
                        '<option value="{{ $barang2->id }}">{{ $barang2->nama_barang }}</option>'+
                    @endforeach
                    +'</select>'+
                '</td>'+
                '<td>'+
                    '<input type="number" name="jumlah_opname[]" class="form-control">'+
                '</td>'+
                '<td>'+
                    '<div style="width:100px" class="btn btn-sm btn-outline-danger" onclick="hapusitem(' + flag + ')">Hapus</div>'+
                '</td>'+
            '</tr>'
        );
    });

    function hapusitem(flag) {
        $('#flag_' + flag).remove();
    }
</script>
@endsection