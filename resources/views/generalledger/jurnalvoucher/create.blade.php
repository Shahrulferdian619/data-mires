@extends('layouts.vuexy')

@section('mycss')
    <style>
        .select2 {
            width:100%!important;
        }
        .select2-container{
            width: 100%!important;
        }
        .select2-search--dropdown .select2-search__field {
            width: 98%;
        }

        .table td {
            padding: 0;
            vertical-align: middle;
        }
    </style>
@endsection

@section('header')
Jurnal Voucher (Jurnal Umum) Baru
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@endif

<a href="{{ route('admin.jurnal-voucher.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

    <form id="form-save">
        <div class="row match-height">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <span><b><span class="text-danger">*</span> Wajib Di isi</b></span>
                        <br>
                        @csrf
                        <label>Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nomer" value="{{ old('nomer') }}" required>

                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal" value="{{ tanggalSekarang() }}" required>
                    
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="4" name="deskripsi">{{ old('deskripsi') }}</textarea>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Rincian Jurnal Voucher</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="flag">
                                <thead>
                                    <tr>
                                        <th width="25%">Akun <span class="text-danger">*</span></th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                        <th>Memo</th>
                                        <th width="8%">#</th>
                                    </tr>
                                </thead>
                                <tbody class="flag_row">
                                    <tr>
                                        <td>
                                            
                                            <select class="form-control selectNya form-control-lg" name="coa_id[]" required>
                                                <option value="">-- PILIH AKUN --</option>
                                                @foreach($coa as $row)
                                                    <option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="debit[]" id="debit" value="{{ old('debit') }}" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="kredit[]" id="kredit" value="{{ old('kredit') }}" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="memo[]" id="memo" value="{{ old('memo') }}" >
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-item" style="width:100%">
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
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-outline-primary" id="btnSubmit" type="submit">Simpan</button>
                        <a href="{{ route('admin.jurnal-voucher.index') }}" class="btn btn-outline-danger">Batal</a>
                    </div>
                </div>
            </div>
        </div>  
    </form>
@endsection

@section('myjs')
<script type="text/javascript">

    //format rupiah
    var debit = document.getElementById('debit');
    debit.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatdebit() untuk mengubah angka yang di ketik menjadi format angka
        debit.value = formatRupiah(this.value, 'Rp. ');
    });

    var kredit = document.getElementById('kredit');
    kredit.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatkredit() untuk mengubah angka yang di ketik menjadi format angka
        kredit.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }


    //rincian jurnal voucher
    var flag = 1;
    $('.btn-item').on('click', function() {
        var newRow = 
        '<tr class="flag_'+flag+'">'+
            '<td>'+
                '<select class="form-control selectNya" name="coa_id[]" required>'+
                    '<option value="">-- PILIH AKUN --</option>'+
                    @foreach($coa as $row)
                        '<option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>'+
                    @endforeach
                '</select>'+
            '</td>'+
            '<td>'+
                '<input type="text" class="form-control" name="debit[]" id="debit_'+flag+'" value="{{ old('debit') }}" placeholder="Masukan Debit..." onKeyup = "rupiahJs(this)">'+
            '</td>'+
            '<td>'+
                '<input type="text" class="form-control" name="kredit[]" id="kredit_'+flag+'" value="{{ old('kredit') }}" placeholder="Masukan Kredit..." onKeyup = "rupiahJs(this)">'+
            '</td>'+
            '<td>'+
                '<input type="text" class="form-control" name="memo[]" id="" value="{{ old('memo') }}" placeholder="Masukan Memo...">'+
            '</td>'+
            '<td><button type="button" class="btn btn-outline-danger btn-hps-item btn-sm" style="width:100%"  data-flag="flag_'+flag+'"><i class="fa fa-trash"></i> Hapus</button></td>'+
        '</tr>';
        
        $('#flag tbody tr:last').after(newRow);

        $('.selectNya').select2();
        flag++;
    });
    $('.flag_row').on('click', '.btn-hps-item',function() {
        $('.'+$(this).data("flag")).remove();
    });

    function rupiahJs(e){
        $('#'+$(e).attr('id')).val(formatRupiah($(e).val(), 'Rp. '));
    }

    

    //submit form
    $('body').on('submit', '#form-save', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById("form-save"));

        $('#btnSubmit').html('<i class="mr-1 fa fa-spinner fa-spin"></i> Loading...');
        document.getElementById("btnSubmit").disabled = true;

        $.ajax({
            type: 'post',
            url: "{{ route('admin.jurnal-voucher.store') }}",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (data) => {
                $('#btnSubmit').html('Simpan');
                if (data.tidak_balance || data.sebaris || data.nomer) {
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
                        window.location = "{{ route('admin.jurnal-voucher.index') }}";
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

</script>
@endsection