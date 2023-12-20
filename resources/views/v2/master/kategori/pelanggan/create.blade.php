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

<a href="{{ route('master-kategori.pelanggan.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>

<form action="{{ route('master-kategori.pelanggan.store') }}" method="post">
    @csrf
    <div class="card">
        <div class="card-header pb-3">
            <h5 class="m-0 me-2 card-title">
                Data Kategori Pelanggan
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kategori Pelanggan<span class="text-danger">*</span></th>
                            <th style="width: 50px;">
                                <button type="button" class="btn btn-sm btn-success btn-plus">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="kategori-0">
                            <td>
                                <input type="text" name="kategoriPelanggan[0][kategori_pelanggan]" class="form-control" required>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-danger btn-trash" disabled>
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br>                                                                        

    <!-- Button Submit -->
    <div class="card">
        <div class="card-footer">
            <button class="btn btn-outline-primary btn-simpan" type="submit">Simpan</button>
            <a href="{{ route('master-kategori.pelanggan.index') }}" class="btn btn-outline-warning">
                Batal
            </a>
        </div>
    </div>
</form>
@endsection

@section('custom_js')
    <script type="text/javascript">
        $(document).ready(function() {
            const tbody = $('tbody');
            const btnPlus = $('.btn-plus');

            removeBtn()
            let rowIndex = 1;

            // fungsi untuk menambahkan baris input tipe supplier
            btnPlus.on('click', function(e) {
                let tblRow =  `<tr class="kategori-${rowIndex}">
                                    <td>
                                        <input type="text" name="kategoriPelanggan[${rowIndex}][kategori_pelanggan]" class="form-control" required>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-sm btn-danger btn-trash">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;

                tbody.append(tblRow);
                ++rowIndex;
                removeBtn()
            })

            // fungsi untuk menghapus baris input tipe supplier
            $(document).on('click', '.btn-trash', function(e) {
                $(this).closest('tr').remove();
                removeBtn()
            });

        });
        
        function removeBtn(){
            if ($('tbody')[0].rows.length == 1) {
                $('.btn-trash')[0].disabled = true
            }else{
                $('.btn-trash')[0].disabled = false
            }
        }
    </script>
@endsection