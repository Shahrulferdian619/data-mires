@extends('layouts.vuexy')

@section('header')
Bank Book (Buku Bank)
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif
<div class="card">
    <div class="card-header with-border">
        <a data-toggle="modal" data-target="#modal-filter" class="btn btn-outline-primary">
            <i data-feather="filter"></i>
             Filter
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="datatables-new table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nomer</th>
                        <th>Tanggal</th>
                        <th>Memo</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Saldo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
     <!-- Modal -->
     <div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modal-title"
     aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p>* Kosongkan jika filter tidak berfungsi</p>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-12 col-12">
                            <label for="coa_id">Bank</label>
                            <select class="select2 form-control" id="coa_id" name="coa_id">
                                <option value="">--Pilih Satu--</option>
                                @foreach ($akun as $item)
                                    <option value="{{ $item->coa_id }}">[{{ $item->coa->tipeCoa->tipecoa }}] - {{ $item->coa->nama_coa}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="dari_tanggal">Dari Tanggal</label>
                            <input id="dari_tanggal" type="date" name="dari_tanggal"
                                class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="sampai_tanggal">Sampai Tanggal</label>
                            <input id="sampai_tanggal" type="date" name="sampai_tanggal"
                                class="form-control">
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary" id="btn-filter">Filter</button>
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Kembali</button>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        //proses cari
        $('#btn-filter').click(function() {
            var dari_tanggal = $('#dari_tanggal').val();
            var sampai_tanggal = $('#sampai_tanggal').val();
            var coa_id = $('#coa_id').val();

            $('.datatables-new').DataTable().destroy();
            loadData(dari_tanggal, sampai_tanggal, coa_id);
            $('#modal-filter').modal('hide');
            toastr['success']('👋 Sukses melakukan filter, Data sedang diproses....', 'Success!', {
                closeButton: true,
                tapToDismiss: true,
            });
        });


        loadData();
    })

    function loadData(dari_tanggal = '', sampai_tanggal = '', coa_id = '') {
            $('.datatables-new').DataTable({
                ordering:false,
                processing: true,
                serverSide: true,
                paging: true,
                searching: true,
                order: [],
				aaSorting: [],
                ajax: {
                    url: "{{ route('admin.buku-bank.get') }}",
                    type: 'GET',
                    data:{
                        dari_tanggal:dari_tanggal,
                        sampai_tanggal:sampai_tanggal,
                        coa_id:coa_id,
                    }
                },
                columns: [
                    
                    {
                        data: 'nomer',
                        name: 'nomer'
                    },
                    {
                        data: 'tanggal_indonesia',
                        name: 'tanggal_indonesia'
                    },
                    {
                        data: 'memo',
                        name: 'memo'
                    },
                    {
                        data: 'debit',
                        name: 'debit'
                    },
                    {
                        data: 'kredit',
                        name: 'kredit'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                   
                ],
                "lengthMenu": [10, 25, 50, 100],
                "pageLength": 10
            });
        }

</script>
@endsection