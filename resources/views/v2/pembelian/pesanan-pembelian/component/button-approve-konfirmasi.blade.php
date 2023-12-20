@if(Auth::user()->position == 'direktur')
<!-- Modal approve direktur -->
<div class="modal fade" id="modalApproveDirektur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.pesanan-pembelian.approve-pengajuan',$pesananPembelian->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Approve pengajuan! direktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Catatan</label>
                    <textarea name="catatan" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal reject direktur -->
<div class="modal fade" id="modalRejectDirektur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.pesanan-pembelian.reject-pengajuan',$pesananPembelian->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Reject pengajuan! direktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Catatan</label>
                    <textarea name="catatan" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@if(Auth::user()->position == 'komisaris')
<!-- Modal approve komisaris -->
<div class="modal fade" id="modalApproveKomisaris" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.pesanan-pembelian.approve-pengajuan',$pesananPembelian->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Approve pengajuan! komisaris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Catatan</label>
                    <textarea name="catatan" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal reject komisaris -->
<div class="modal fade" id="modalRejectKomisaris" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pembelian.pesanan-pembelian.reject-pengajuan',$pesananPembelian->id) }}" method="post" style="width: 100%;">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Reject pengajuan! komisaris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="">Catatan</label>
                    <textarea name="catatan" cols="30" rows="6" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-tambah-paket">Ok</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif