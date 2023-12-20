@extends('layouts.vuexy')

@section('header')
Report Purchase | Order Per Item
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12 mb-1">
        <a href="{{ url('admin/report-purchase') }}"><i data-feather='arrow-left-circle'></i> Kembali ke daftar laporan</a>
    </div>
    <div class="col-lg-12 col-12">
        <div class="card">
            <div class="card-body">   
                <div class="table-responsive">
                    <table class="table-dashboard table table-condensed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                {{-- <th>Total kuantitas Barang</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $item)
                            <tr>
                                <td> {{ $item->barang->kode_barang }} </td>
                                <td> {{ $item->barang->nama_barang }} </td>
                                {{-- <td> {{ number_format($item['qty'], 0, ',', '.') }} </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-dashboard').DataTable()
    })
    $('#btn-modal').on('click', function(){
        $('#filter').modal('show')
    })
</script>
@endsection