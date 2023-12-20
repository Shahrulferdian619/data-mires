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
Jurnal Voucher (Jurnal Umum) Ubah
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
 @elseif(session('success'))
    @include('layouts.success')
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
                        <input type="hidden" name="_method" value="PUT">
                        <label>Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nomer" value="{{ $jurnalVoucher->nomer }}" required>

                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal" value="{{ $jurnalVoucher->tanggal }}" required>
                    
                        <label>Deskripsi</label>
                        <textarea class="form-control" rows="4" name="deskripsi">{{ $jurnalVoucher->deskripsi }}</textarea>

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
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="flag_row">
                                    @foreach ($jurnalVoucherRinci as $item)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="jurnal_voucher_rinci_id[]" value="{{ $item->id }}">
                                                <select class="form-control selectNya" name="coa_id[]" required>
                                                    <option value="">-- PILIH AKUN --</option>
                                                    @foreach($coa as $row)
                                                        <option value="{{ $row->id }}" {{ $row->id == $item->coa_id ? "selected" : "" }}>[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control debit" name="debit[]" id="debit_{{ $item->id }}" value="{{ $item->tipe == "D" ? $item->nominal : "" }}" placeholder="Masukan Debit..." onKeyup = "rupiahJs(this)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control kredit" name="kredit[]" id="kredit_{{ $item->id }}" value="{{ $item->tipe == "K" ? $item->nominal : "" }}" placeholder="Masukan Kredit..." onKeyup = "rupiahJs(this)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="memo[]" id="" value="{{ $item->memo }}" placeholder="Masukan Memo...">
                                            </td>
                                            <td>
                                                <button onclick="destroyRinci(this)" data-id="{{ $item->id }}" class="btn btn-outline-danger btn-sm" type="button" style="width:100%"><i class="fa fa-trash"></i> Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>
                                            <input type="hidden" name="jurnal_voucher_rinci_id[]" value="">
                                            <select class="form-control selectNya" name="coa_id[]">
                                                <option value="">-- PILIH AKUN --</option>
                                                @foreach($coa as $row)
                                                    <option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control debit" name="debit[]" id="debit" value="{{ old('debit') }}" placeholder="Masukan Debit...">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control kredit" name="kredit[]" id="kredit" value="{{ old('kredit') }}" placeholder="Masukan Kredit...">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="memo[]" id="memo" value="{{ old('memo') }}" placeholder="Masukan Memo...">
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
                        <button type="button" class="btn btn-outline-danger" id="batalSimpan">Batal</button>
                    </div>
                </div>
            </div>
        </div>  
    </form>
@endsection

@section('myjs')
<script type="text/javascript">
    //batal simpan
    $('#batalSimpan').on('click', function() {
        var totalDebit = 0;
        var countDebit = 0;
        $(".debit").each(function(){
            var removeRp = $(this).val().replace('Rp. ', '');
            var val = removeRp.replace('.', '');
            totalDebit += +val;

            countDebit++;
            
        });
        
        var totalKredit = 0;
        $(".kredit").each(function(){
            var removeRp = $(this).val().replace('Rp. ', '');
            var val = removeRp.replace('.', '');
            totalKredit += +val;
            
        });

        var cekBalance = false;
        for (let index = 0; index < countDebit; index++) {
            if($('.debit').eq(index).val() != "" && $('.kredit').eq(index).val() != ""){
                cekBalance = true;
            }
            
        }

        if(totalDebit != totalKredit){
            Swal.fire({
                title: 'Error!',
                text: "Error! Debit dan Kredit tidak balance.",
                icon: 'error',
                customClass: {
                confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        }
        else if(cekBalance == true){
            Swal.fire({
                title: 'Error!',
                text: "Error! Debit dan Kredit tidak boleh diisi sebaris.",
                icon: 'error',
                customClass: {
                confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        }else{
            location.href = "{{ route('admin.jurnal-voucher.index') }}";
        }
       
    });
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
                '<input type="hidden" name="jurnal_voucher_rinci_id[]" value="">'+
                '<select class="form-control selectNya" name="coa_id[]" required>'+
                    '<option value="">-- PILIH AKUN --</option>'+
                    @foreach($coa as $row)
                        '<option value="{{ $row->id }}">[{{ $row->nomer_coa }}] - {{ $row->nama_coa }}</option>'+
                    @endforeach
                '</select>'+
            '</td>'+
            '<td>'+
                '<input type="text" class="form-control debit" name="debit[]" id="debit_'+flag+'" value="{{ old('debit') }}" placeholder="Masukan Debit..." onKeyup = "rupiahJs(this)">'+
            '</td>'+
            '<td>'+
                '<input type="text" class="form-control kredit" name="kredit[]" id="kredit_'+flag+'" value="{{ old('kredit') }}" placeholder="Masukan Kredit..." onKeyup = "rupiahJs(this)">'+
            '</td>'+
            '<td>'+
                '<input type="text" class="form-control" name="memo[]" id="" value="{{ old('memo') }}" placeholder="Masukan Memo...">'+
            '</td>'+
            '<td><button type="button" class="btn btn-outline-danger btn-hps-item btn-sm" style="width:100%" data-flag="flag_'+flag+'"><i class="fa fa-trash"></i> Hapus</button></td>'+
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
            url: "{{ route('admin.jurnal-voucher.update', ['jurnal_voucher' => $jurnalVoucher->id]) }}",
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

     //Hapus Rincian
    function destroyRinci(e){
        var id = $(e).attr('data-id');
        // confirm('Yakin Akan Menghapus?');
        
        $.ajax({
            type: 'post',
            url: "{{ route('admin.jurnal-voucher-rinci.destroy') }}",
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

</script>
@endsection