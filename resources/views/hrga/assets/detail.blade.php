@extends('layouts.vuexy')

@section('header')
Detail Asset Category ( Detail Asset )
@endsection

@section('content')

<a href="/admin/asset">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>
<hr>

<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body">
            <label>Kategori Asset </label>
            <input type="text" class="form-control" readonly value="{{ $asset->tipe->tipe_asset }}">
            
            <label>Nama Asset</label>
            <input type="text" class="form-control" readonly value="{{ $asset->nama_asset }}" >

            <label>Tanggal Perolehan Asset</label>
            <input type="date" class="form-control" readonly value="{{ $asset->tanggal_perolehan }}">

            <label>Harga Perolehan Asset</label>
            <input type="text" class="form-control" readonly value="{{ rupiah($asset->harga_perolehan) }}">

            <label>Kuantitas</label>
            <input type="text" class="form-control" name="kuantitas" readonly value="{{ $asset->kuantitas }}">

            <label>Keterangan</label>
            <textarea class="form-control" rows="4" readonly >{{ $asset->Keterangan }}</textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-center" id="svg-container">{!! QrCode::generate($asset->nama_asset.'-qty'.$asset->kuantitas.'-prc'.$asset->harga_perolehan); !!}</div>
                <br>
                <div onclick="download()" class="btn btn-sm btn-primary">Download QrCode</div>
                <br><br>
                <p>Result : {{ $asset->nama_asset.'-qty'.$asset->kuantitas.'-prc'.$asset->harga_perolehan }}</p>
                <table class="table table-bordered">
                    <tr class="bg-light">
                        <th>Kode</th>
                        <th>Arti</th>
                    </tr>
                    <tr>
                        <td>"{{ $asset->nama_asset }}"</td>
                        <td>Nama Asset</td>
                    </tr>
                    <tr>
                        <td>"qty{{ $asset->kuantitas }}"</td>
                        <td>Kuantitas = {{ $asset->kuantitas }}</td>
                    </tr>
                    <tr>
                        <td>"prc{{ $asset->harga_perolehan }}"</td>
                        <td>Harga = {{ rupiah($asset->harga_perolehan) }}</td>
                    </tr>
                    <tr>
                        <td>"-"</td>
                        <td>Separator</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <form action="/admin/asset/{{ $asset->id }}" method="POST">
        <div class="card-body">
            <a href="/admin/asset/{{ $asset->id }}/edit" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div>

<script>
function download(){

    img = new Image(),
    serializer = new XMLSerializer(),
    svgStr = serializer.serializeToString(document.getElementById('svg-container').firstElementChild);

    img.src = 'data:image/svg+xml;base64,'+window.btoa(svgStr);

    // You could also use the actual string without base64 encoding it:
    //img.src = "data:image/svg+xml;utf8," + svgStr;

    var canvas = document.createElement("canvas");

    var w=800;
    var h=800;

    canvas.width = w;
    canvas.height = h;
    canvas.getContext("2d").drawImage(img,0,0,w,h);

    var imgURL = canvas.toDataURL("image/png");


    var dlLink = document.createElement('a');
    dlLink.download = "{{ $asset->nama_asset.'-qty'.$asset->kuantitas.'-prc'.$asset->harga_perolehan }}";
    dlLink.href = imgURL;
    dlLink.dataset.downloadurl = ["image/png", dlLink.download, dlLink.href].join(':');

    document.body.appendChild(dlLink);
    dlLink.click();
    document.body.removeChild(dlLink);
}

</script>
@endsection