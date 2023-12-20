@extends('layouts.vuexy')

@section('header')
Change Profile Picture ( Ubah Foto Profil )
@endsection

@section('content')
<style>
    .card-body .profile_img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin: 2px auto;
    border: 2px solid #ccc;
    border-radius: 50%;
}
</style>

<a href="{{ url('admin/profile') }}">
    <i class="fa fa-arrow-left  m-1"></i> Kembali ke daftar
</a>
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif

@if(Auth::user()->profile_picture == null || empty(Auth::user()->profile_picture) || Auth::user()->profile_picture == '' )
    @php $profile_pic = ''.url('vuexy/images/portrait/small/avatar-s-11.jpg').''; @endphp
@else
    @php $profile_pic = ''.url('uploads/profile/'.Auth::user()->profile_picture).''; @endphp
@endif

<div class="card">
    <div class="card-body bg-transparent text-center">
        <form action="{{ url('admin/profile/change-picture') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <img class="m-2 shadow profile_img" src="{{ $profile_pic }}" id="previewprofile"><br>
            <input required onchange="previewFile(this);" type="file" class="form-control" name="profile">
            <small>File Harus Gambar, Rasio 1:1, Rekomendasi Ukuran 500 x 500 px</small>
            <button class="btn btn-sm btn-outline-primary mt-2 btn-block" type="submit">Upload</button>
        </form>
    </div>
</div>

@endsection


@section('myjs')
<script>

function previewFile(input){
    var file = $("input[name=profile]").get(0).files[0];
    if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#previewprofile").attr("src", reader.result);
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection