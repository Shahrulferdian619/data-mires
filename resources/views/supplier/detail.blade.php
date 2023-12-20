@extends('layouts.vuexy')

@section('content')
@if (session()->has('fail'))
    @include('layouts.fail')
@endif
<a href="/admin/supplier">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 300px;">Kode supplier</td>
                    <td>{{ $supplier->kode_supplier }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Nama Supplier</td>
                    <td>{{ $supplier->nama_supplier }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Tipe Supplier</td>
                    <td>{{ $supplier->tipesupplier->tipesupplier }}</td>
                </tr>
                
                <tr>
                    <td style="width: 300px;">PIC supplier</td>
                    <td>{{ $supplier->pic }}</td>
                </tr>
                
                <tr>
                    <td style="width: 300px;">Rekening supplier</td>
                    <td>{{ $supplier->nomer_rekenin }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Handphone supplier</td>
                    <td>{{ $supplier->handphone_supplier }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Email supplier 1</td>
                    <td>{{ $supplier->email_supplier }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Negara</td>
                    <td>{{ $supplier->negara }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Provinsi</td>
                    <td>{{ $supplier->provinsi }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kota</td>
                    <td>{{ $supplier->kota }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Kecamatan</td>
                    <td>{{ $supplier->kecamatan }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Detail Alamat</td>
                    <td>{{ $supplier->detail_alamat }}</td>
                </tr>
                <tr>
                    <td style="width: 300px;">Deskirpsi supplier</td>
                    <td>{{ $supplier->deskripsi_supplier }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <form action="/admin/supplier/{{ $supplier->id }}"method="POST">
        <div class="card-body">
            <a href="/admin/supplier/{{$supplier->id}}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        
        </div>
    </form>
</div>

@endsection