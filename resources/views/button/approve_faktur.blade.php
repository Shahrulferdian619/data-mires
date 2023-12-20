<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-outline-success" onclick="approve(1)"><i class="fa fa-check"></i> Approve</button>
        <button type="button" class="btn btn-outline-danger" onclick="approve(2)"><i class="fa fa-times"></i> Reject</button>
    </div>
</div>


<div class="modal fade" id="approveKeterangan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <form action="{{ url('admin/fakturpembelian/approval/'.$faktur->id) }}" method="POST">
                @csrf
                <div class="modal-header">Owner Approval</div>
                <div class="modal-body">
                    <h3 class="text-center m-1">Anda Yakin <span id="msg"></span> Faktur?</h3>
                    <small class="text-center">Keterangan :</small>
                    <input type="text" name="approve_komisaris" hidden id="approval">
                    <textarea class="form-control" name="note_komisaris" id="" rows="3"></textarea>
                </div>
                <div class="modal-footer mb-2">
                    <button type="submit" class="btn btn-primary">Ya!</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function approve(appr){
        if(appr == 1){
            $('#approval').val(1);
            $('#msg').text('Approve');
            $('#msg').attr('class', 'text-success');
        }else if(appr == 2){
            $('#approval').val(2);
            $('#msg').text('Reject');
            $('#msg').attr('class', 'text-danger');
        }
        $('#approveKeterangan').modal('show');
    }
</script>