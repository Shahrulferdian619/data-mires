@extends('layouts.vuexy')

@section('header')
Mutation (Mutasi)
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
    <a href="{{ url('admin/mutation-history') }}" class="btn btn-sm btn-primary">Lihat History Mutasi</a> <br><br>
    <form id="form-save" action="{{ url('admin/mutation') }}" method="POST">
        <div class="row match-height">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        @csrf
                        <label>Nomor Transaksi<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomor" name="nomer" value="{{ old('nomer') }}" required>

                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input readonly type="date" id="tanggal" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        <div class="row">
                            <div class="col-6">
                                <label for="">Gudang Asal</label>
                                <select class="select2 form-control form-select gudang_asal" data-id="1" name="gudang_asal" id="gudang_asal">
                                    <option value="0">-- Pilih Gudang --</option>
                                    @foreach($gudang as $gdg)
                                    <option value="{{ $gdg->id }}">{{ $gdg->nama_gudang }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-6">
                                <label for="">Gudang Tujuan</label>
                                <select class="select2 form-control form-select" name="gudang_tujuan" id="gudang_tujuan">
                                    <option value="0">-- Pilih Gudang --</option>
                                    @foreach($gudang as $gdg)
                                    <option value="{{ $gdg->id }}">{{ $gdg->nama_gudang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="4" name="deskripsi">{{ old('deskripsi') }}</textarea>

                    </div>
                </div>
            </div>
            <div class="col-md-12" id="formMutation">
                <div class="card" id="flag_1">
                    <div class="card-body">
                        <table id="formMutation" class="table table-bordered">
                            <tr>
                                <th>Item Yang Akan Di Mutasi</th>
                                <th>Jumlah Mutasi</th>
                                <th>#</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="barang_id[]" class="form-control barang_id" id="barang">
                                        
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="jumlah_mutasi[]" id="flag_1">
                                </td>
                                <td>
                                    <div style="width:100px" class="btn btn-sm btn-outline-primary" id="btnAdd">Tambah</div>
                                </td>
                            </tr>
                        </table>
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
        if($('#nomor').val() == '' || $('#tanggal').val() == '' || $('#gudang_asal').val() == 0 || $('#gudang_tujuan').val() == 0 || $('#jumlah_mutasi').val() == 0 || $('#jumlah_mutasi').val() == ''){
            return Swal.fire({
                title: 'Kesalahan!',
                text: 'Data Belum Lengkap!',
                icon: 'error',
                customClass: {
                confirmButton: 'btn btn-success'
                }
            });
        }
        if( $('#gudang_asal').val() ==  $('#gudang_tujuan').val()){
            return Swal.fire({
                title: 'Kesalahan!',
                text: 'Gudang Asal Dan Gudang Tujuan Tidak Boleh Sama!!',
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
        }).then(function (result) {
            if (result.value) {
                $('#form-save').submit();
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
    let array;
    $('.gudang_asal').on('change', function(el) {
        $.ajax({
            method: 'GET',
            url: '/admin/mutation/get_barang_by_gudang/' + $(this).val(),
            dataType: 'JSON',
            success: function(data){
                // console.log(data)
                let html = ''
                data.map((item, index) => {
                    html += `
                    <option value="${item.barang.id}">${item.barang.nama_barang} (Stock : ${item.qty}) </option>
                    `
                })
                array = data;
                $('.barang_id').html(html);
            }
        })
    })

    $('#formMutation').on('click','.btn-hapus', function() {
        // $(this).parent().remove();
        let id = $(this).attr('data-id');
        $('#flag_'+id).remove();
        console.log(this)
    });
    let flag = 1;
    $('#btnAdd').on('click', function(e){
        flag++;
        // console.log('hehe')
        let html = '';
        if(array != null){
            array.map((item, index) => {
            html += `
            <option value="${item.barang.id}">${item.barang.nama_barang} (Stock : ${item.qty}) </option>
            `
        })
        }

        $( "#formMutation tr:last" ).after(
            `<tr id="flag_`+flag+`">
                <td>
                    <select name="barang_id[]" class="form-control barang_id">`+html+`</select>
                </td>
                <td>
                    <input name="jumlah_mutasi[]" class="form-control">
                </td>
                <td>
                    <div style="width:100px" class="btn btn-sm btn-outline-danger" onclick="hapusitem(`+flag+`)">Hapus</div>
                </td>
            </tr>`
        );
    });

    function hapusitem(flag){
        $('#flag_'+flag).remove();
    }

</script>
@endsection