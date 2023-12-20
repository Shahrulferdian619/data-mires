@extends('layouts.vuexy')

@section('header')
Change Signature ( Ubah Tanda Tangan )
@endsection

@section('content')
<style>
    .card-body .signature_img {
    height: 150px;
    object-fit: cover;
}
</style>

<a href="{{ url('admin/profile') }}">
    <i class="fa fa-arrow-left m-1"></i> Kembali ke daftar
</a>
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif

@if(Auth::user()->signature == null || empty(Auth::user()->signature) || Auth::user()->signature == '' )
    @php $signature = ''.url('uploads/signature/signature.png').''; @endphp
@else
    @php $signature = ''.url('uploads/signature/'.Auth::user()->signature).''; @endphp
@endif

<div class="card">
    <div class="card-body bg-transparent text-center">
        <form action="{{ url('admin/profile/change-signature') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <img class="m-2 shadow signature_img" src="{{ $signature }}" id="previewsignature"><br>
            <input required onchange="previewFile(this);" type="file" class="form-control" name="signature">
            <small>File Harus PNG, Rasio 1:1, Rekomendasi Ukuran 500 x 500 px</small>
            <button class="btn btn-sm btn-outline-primary mt-2 btn-block" type="submit">Upload</button>
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