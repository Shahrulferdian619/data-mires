@if($type == 'popembelian')
<div class="card">
    <form action="{{ url('/admin/po/delete/' . $popembelian->id) }}" method="POST">
        <div class="card-body">
            <a href="/admin/po/{{ $popembelian->id }}/print" class="btn btn-outline-success">
                <i class="fa fa-print"></i>
                 Print
            </a>
            <a href="/admin/po/{{ $popembelian->id }}/print-nonttd" class="btn btn-outline-success">
                <i class="fa fa-print"></i>
                 Print non-ttd
            </a>
            <a href="/admin/po/{{ $popembelian->id }}/edit" class="btn btn-outline-primary">
                <i class="fa fa-edit"></i>
                 Edit
            </a>
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i data-feather="trash"></i> Delete
            </button>
        </div>
    </form>
</div>
@elseif($type == 'pmtpembelian')
<div class="card">
    <form action="/admin/pmtpembelian/{{ $pmtpembelian->id }}"method="POST">
    <div class="card-body">
        <a href="/admin/pmtpembelian/{{ $pmtpembelian->id }}/print" class="btn btn-outline-success">
            <i class="fa fa-print"></i>
             Print
        </a>
        <a href="/admin/pmtpembelian/{{ $pmtpembelian->id }}/print-nonttd" class="btn btn-outline-success">
            <i class="fa fa-print"></i>
             Print non-ttd
        </a>
        <a href="/admin/pmtpembelian/{{ $pmtpembelian->id }}/edit" class="btn btn-outline-primary">
            <i class="fa fa-edit"></i>
             Edit
        </a>
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
    </div>
    </form>
</div>
@elseif($type == 'faktur')
<div class="card">
    <form action="/admin/fakturpembelian/{{ $faktur->id }}"method="POST">
    <div class="card-body">
        <!-- <a href="/admin/faktur/{{ $faktur->id }}/print" class="btn btn-outline-success">
            <i class="fa fa-print"></i>
             Print
        </a> -->
        <!-- <a href="/admin/fakturpembelian/{{ $faktur->id }}/edit" class="btn btn-outline-primary">
            <i class="fa fa-edit"></i>
             Edit
        </a> -->
        @csrf
        @method('DELETE')
        <button class="btn btn-outline-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
    </div>
    </form>
</div>
@elseif ($type == 'recieve_item')
<div class="card">
    <div class="card-body">
        <form action="{{ url('admin/ri/' . $ri->id) }}" method="post">
            @csrf
            @method('delete')
            <button class="btn btn-outline-danger">
                <i data-feather="trash"></i> 
                Delete
            </button>
        </form>
        {{-- <a href="/admin/ri/{{ $ri->id }}/print" class="btn btn-outline-success">
            <i class="fa fa-print"></i>
             Print
        </a> --}}
        {{-- <a href="/admin/ri/{{ $ri->id }}/edit" class="btn btn-outline-primary">
            <i class="fa fa-edit"></i>
             Edit
        </a> --}}
    </div>
</div>
@endif