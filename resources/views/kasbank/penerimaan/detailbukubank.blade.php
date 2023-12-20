@extends('layouts.vuexy')

@section('header')
Deposit (Penerimaan)
@endsection

@section('content')


<a href="{{ route('admin.buku-bank.index') }}">
    <i class="fa fa-arrow-left"></i> Kembali ke daftar
</a>

<hr>
<div class="card">
    <div class="card-body">
        <table class=" table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td style="width: 270px;">Diterima ke</td>
                    <td>[{{ $debit->coa->nomer_coa }}] - {{ $debit->coa->nama_coa}}</td>
                </tr>
                <tr>
                    <td style="width: 270px;">Nomer</td>
                    <td>{{ $bukuBank->nomer }}</td>
                </tr>
                <tr>
                    <td style="width: 270px;">Tanggal</td>
                    <td>{{ tanggal($bukuBank->tanggal) }}</td>
                </tr>
                <tr>
                    <td style="width: 270px;">Deskripsi</td>
                    <td>{{ $bukuBank->deskripsi }}</td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header with-border">
        <h3>Rincian Penerimaan</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="datatables-new table table-condensed table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Akun</th>
                        <th>Nominal</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bukuBankRinci as $row)
                        <tr>
                            <td>[{{ $row->coa->nomer_coa }}] - {{ $row->coa->nama_coa}}</td>
                            <td>{{ rupiah($row->nominal) }}</td>
                            <td>{{ $row->memo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.penerimaan.destroy', ['penerimaan' => $bukuBank->id]) }}" method="POST">
        <div class="card-body">
            <a href="{{ route('admin.penerimaan.edit', ['penerimaan' => $bukuBank->id]) }}" class="btn btn-outline-warning" type="submit">Edit</a>
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Yakin Akan Menghapus?')" class="btn btn-outline-danger" type="submit">Hapus</button>
        </div>
    </form>
</div> 

@endsection


@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.datatables-new').DataTable(
			{
				paging: true, 
				searching: true,
				order: [],
				aaSorting: [],
			}
        );
    })
</script>
@endsection