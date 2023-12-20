<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-auto mb-1">
                <a href="/admin/so/{{ $so->id }}/edit" class="btn btn-outline-primary w-100">
                    <i class="fa fa-edit"></i>
                     Edit
                </a>
            </div>
            <div class="col-12 col-md-auto mb-1">
                <form action="{{ url('/admin/so/delete/' . $so->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Anda Yakin Untuk Menghapus Sales Order?')" class="btn btn-outline-danger w-100">
                        <i data-feather="trash"></i> Delete
                    </button>
                </form>
            </div>
            @if($acc == true)
            @if ($so->is_checked != 1)
            <div class="col-12 col-md-auto mb-1">
                <form action="{{ url('admin/so/update-checked/' . $so->id) }}" method="post">
                    @csrf
                    <button type="submit" onclick="return confirm('Anda Yakin Sudah Koreksi Pesanan?')" class="btn btn-outline-primary w-100">
                        <i data-feather="eye"></i> Tandai sudah di koreksi
                    </button>
                </form>
            </div>
            @endif
            @endif
            <div class="col-12 col-md-auto mb-1">
                <a href="{{ url('admin/so/'.$so->id.'/print-do') }}" class="btn btn-outline-warning w-100">Print Form DO</a>
            </div>
        </div>
    </div>
</div>