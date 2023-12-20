@extends('layouts.vuexy')

@section('header')
Create Warehouse ( Tambah Data Gudang )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

{{-- <form action="{{ url('admin/gudang') }}" enctype="multipart/form-data" method="POST"> --}}
<form id="form-save">
<div class="col-md-6">
   <div class="card">
        <div class="card-body">
            @csrf
            <label>Kode Gudang<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="kode_gudang" placeholder="Masukkan Kode Gudang">
            <label>Nama Gudang<span class="text-danger"><i>*</i></span></label>
            <input type="text" class="form-control" name="nama_gudang" placeholder="Masukkan Nama Gudang">
            <label>Deskripsi Gudang</label>
            <textarea class="form-control" rows="4" name="deskripsi_gudang" placeholder="Masukkan Deskripsi Gudang"></textarea>
            <label>Nama Penanggung Jawab Gudang</label>
            <input type="text" class="form-control" name="nama_penanggungjawab" placeholder="Masukkan Nama Pennggung Jawab Gudang">
        </div>
    </div> 
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
                <button class="btn btn-outline-secondary" id="btnSubmitAgain" data-target="lagi" type="submit">Simpan & Baru</button>
                <button class="btn btn-outline-primary" id="btnSubmit" data-target="simpan" type="submit">Simpan</button>
                <a href="{{ route('admin.gudang.index') }}" class="btn btn-outline-danger">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection

@section('myjs')
<script>
    //submit form
    $('body').on('submit', '#form-save', function(e) {
        e.preventDefault();
        let target = e.originalEvent.submitter.getAttribute('data-target');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menambahkan data Gudang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            customClass: {
            confirmButton: 'btn btn-outline-primary',
            cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                var formData = new FormData(document.getElementById("form-save"));

                
                formData.append('target', target);

                $('#btnSubmit').html('<i class="mr-1 fa fa-spinner fa-spin"></i> Loading...');
                document.getElementById("btnSubmit").disabled = true;
                $.ajax({
                    type: 'post',
                    url: "{{ route('admin.gudang.store') }}",
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
                                confirmButton: 'btn btn-outline-primary'
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
                                confirmButton: 'btn btn-outline-primary'
                                },
                                buttonsStyling: false
                            }).then(function() {
                                window.location = "{{ route('admin.gudang.create') }}";
                            });
                        }else{
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil dibuat',
                                icon: 'success',
                                customClass: {
                                confirmButton: 'btn btn-outline-primary'
                                },
                                buttonsStyling: false
                            }).then(function() {
                                window.location = "{{ route('admin.gudang.index') }}";
                            });
                        }
                    },
                    error: function(data) {
                        Swal.fire({
                            title: 'Error!',
                            text: "Error pada server, Silahkan hubungi Administrator!",
                            icon: 'error',
                            customClass: {
                            confirmButton: 'btn btn-outline-primary'
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
                    confirmButton: 'btn btn-outline-success'
                    }
                });
            
            }
        });
    });
</script>
@endsection