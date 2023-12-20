<!-- Modal tambah paket -->
<div class="modal fade" id="modalPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form style="width: 100%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Tambah Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="">Paket produk</label>
                            <select name="paket_id" class="form-control">
                                <option value="">-- PILIH PAKET --</option>
                                @foreach($paket as $row)
                                <option value="{{ $row->id }}">{{ $row->packet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Dsc %</label>
                            <input name="diskon_persen_paket" type="number" class="form-control" value="0">
                        </div>
                        <div class="col-md-2">
                            <label for="">Qty</label>
                            <input name="qty_paket" type="number" class="form-control" value="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary btn-tambah-paket">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>