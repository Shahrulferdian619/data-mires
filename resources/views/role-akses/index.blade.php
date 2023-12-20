@extends('layouts.vuexy')

@section('header')
Role Access Management ( Manajemen Akses )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table1 table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td> 
                            <a href="{{ url('admin/role-akses/' . $user->id) }}" class="badge badge-light-secondary">
                                <i data-feather="eye"></i>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table1').DataTable()
    })
</script>
@endsection