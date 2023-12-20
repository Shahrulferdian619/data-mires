@extends('layouts.vuexy')

@section('header')
Packet ( Paket Produk )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif

<div class="card">
    <div class="card-body">
    <a href="{{ url('admin/packet/create') }}" type="button" class="btn btn-outline-primary waves-effect waves-float waves-light">
        <!-- <i class="fa fa-plus mr-1"></i> -->
        <i data-feather="plus"></i>
        Baru
    </a>
        <table class="table table-hover table-bordered" id="table-packet">
            <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Nama Paket</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($packet as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->packet_name }}</td>
                    <td>
                        <a href="/admin/packet/{{ $item->id }}" class="badge badge-light-secondary">
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
        $('#table-packet').DataTable()
    })
</script>
@endsection