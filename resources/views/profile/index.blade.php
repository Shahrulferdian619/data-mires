@extends('layouts.vuexy')

@section('header')
Profile ( Profile )
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

    .card-body .signature_img {
    height: 100px;
    object-fit: cover;
    }
</style>

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
@if(Auth::user()->signature == null || empty(Auth::user()->signature) || Auth::user()->signature == '' )
    @php $signature = ''.url('uploads/signature/signature.png').''; @endphp
@else
    @php $signature = ''.url('uploads/signature/'.Auth::user()->signature).''; @endphp
@endif
<div class="card">
  <div class="row">
    <div class="col-md-3">
      <div class="card-body bg-transparent text-center">
        <img class="profile_img" src="{{  $profile_pic }}">
        <h4 class="mt-1">{{ Auth::user()->name }}</h4>
        <i>Signature :</i><br>
        <img class="signature_img" src="{{  $signature }}">
      </div>
    </div>
    <div class="col-md-9">
      <form action="{{ url('admin/profile/change-profile') }}" method="post">
      <div class="card-body">
        @csrf
        <label>Nama</label>
        <input id="nama" type="text" readonly required class="form-control" value="{{ Auth::user()->name }}" name="nama">
        <label>Email</label>
        <input id="email" type="email" readonly required class="form-control" value="{{ Auth::user()->email }}" name="email">
        <label>Posisi</label>
        <input type="text" readonly class="form-control" value="{{ Auth::user()->level->nama_level }}" name="level">
        <div class="mt-1 text-right">
          <div id="ubah" class="btn btn-outline-danger">Ubah</div>
          <div id="batal" hidden class="btn btn-outline-danger">Batal</div>
          <button id="submit" hidden type="submit" class="btn btn-outline-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
  </form>
</div>

<div class="card">
  <div class="card-body">
  <a href="{{ url('admin/profile/change-picture') }}" class="btn btn-outline-secondary">Ubah Foto Profile</a>
  <a href="{{ url('admin/profile/change-signature') }}" class="btn btn-outline-warning">Ubah Signature</a>
  <a href="{{ url('admin/profile/change-password') }}" class="btn btn-outline-danger">Ubah Password</a>
  </div>
</div>

@endsection


@section('myjs')
<script>
$('#ubah').on('click', function(){
  $('#nama').removeAttr('readonly');
  $('#email').removeAttr('readonly');

  $('#batal').removeAttr('hidden');
  $('#submit').removeAttr('hidden');

  $('#ubah').attr('hidden', 'true');
});

$('#batal').on('click', function(){
  $('#nama').attr('readonly', 'true');
  $('#email').attr('readonly', 'true');

  $('#batal').attr('hidden', 'true');
  $('#submit').attr('hidden', 'true');

  $('#ubah').removeAttr('hidden');
});
</script>
@endsection