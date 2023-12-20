@extends('layouts.vuexy')

@section('header')
Change Password ( Ubah Kata Sandi )
@endsection

@section('content')

<a href="{{ url('admin/profile') }}">
    <i class="fa fa-arrow-left  m-1"></i> Kembali ke daftar
</a>
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif
@if (session()->has('error'))
<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error !</h4>
    <div class="alert-body">
        <ul>
            <li>{{ session('error') }}</li>
        </ul>
    </div>
</div>
@endif

<div class="card col-6">
    <div class="card-body bg-transparent">
        <form action="{{ url('admin/profile/change-password') }}" method="POST" >
            @csrf
            <label for="old_password">Password Sekarang</label>
            <input required type="password" class="form-control" name="old_password">
            <label for="password">Password Baru</label>
            <input required type="password" class="form-control" name="password">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input required type="password" class="form-control" name="password_confirmation">
            <button type="submit" class="mt-1 btn btn-outline-primary">Simpan</button>
        </form>
    </div>
</div>

@endsection


@section('myjs')
<script>

function previewFile(input){
    var file = $("input[name=signature]").get(0).files[0];
    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#previewsignature").attr("src", reader.result);
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection