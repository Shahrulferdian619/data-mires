<div class="card">
    <form action="{{ url('/admin/do/delete/' . $do->id) }}" method="POST">
        <div class="card-body">
            <a href="/admin/do/{{ $do->id }}/surat-jalan" class="btn btn-outline-success">
                <i class="fa fa-print"></i>
                 Surat Jalan
            </a>
            <a href="/admin/do/{{ $do->id }}/edit" class="btn btn-outline-primary">
                <i class="fa fa-edit"></i>
                 Edit
            </a>
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Anda Yakin Untuk Menghapus Delivery Order?')" class="btn btn-outline-danger">
                <i data-feather="trash"></i> Delete
            </button>
        </div>
    </form>
</div>