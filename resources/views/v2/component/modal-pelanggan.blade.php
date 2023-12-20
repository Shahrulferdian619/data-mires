<!-- Modal tambah pelanggan baru direct -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('api.store-pelanggan') }}" method="post" style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Pelanggan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Nama</label>
                    <input name="nama_pelanggan" type="text" class="form-control" required>

                    <label for="">No. Handphone</label>
                    <input name="handphone_pelanggan" type="text" class="form-control" required>

                    <label for="">Provinsi</label>
                    <select name="provinsi" class="form-control provinsi" required>
                        <option value="">-- PILIH PROVINSI --</option>
                        @foreach($provinsi as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>

                    <label for="">Kota</label>
                    <select name="kota" class="form-control kota" required>
                        <option value="">-- PILIH KOTA --</option>
                    </select>

                    <label for="">Detil alamat</label>
                    <textarea name="detail_alamat" cols="30" rows="5" class="form-control" required>-</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-simpan-pelanggan">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>