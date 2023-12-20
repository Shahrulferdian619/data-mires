@extends('layouts.vuexy')

@section('header')
Create Customer Receipt (Pembayaran Pelanggan)
@endsection

@section('content')
    @if($errors->all())
        @include('layouts.validation')
    @endif
    <form action="{{ url('admin/cr') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="cr_nomer" class="form-label">Nomor Pembayaran</label>
                            <input type="text" name="cr_nomer" id="cr_nomer" class="form-control">
                        </div>
                        <div class="mb-1" >
                            <label for="cr_tanggal" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" name="cr_tanggal" id="cr_tanggal" value="{{ date('Y-m-d') }}" class="form-control flatpickr-basic flatpickr-input">
                        </div>
                        <div class="mb-1">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-1" >
                            <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                            <select class="select2 form-select form-control" name="id_pelanggan" id="id_pelanggan">
                                <option value="0">-- Pilih Customer --</option>
                                @foreach ($customer as $item)
                                    <option value="{{$item->id}}" >{{ $item->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1" >
                            <label for="si_penjualan_id" class="form-label">Pilih Tagihan</label>
                            <select class="select2 form-select form-control" name="si_penjualan_id" id="si_penjualan_id">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th class="text-center" >Nama Barang</th>
                                    <th class="text-center" >Jumlah Barang</th>
                                    <th class="text-center" >Diskon Barang</th>
                                    <th class="text-center" >Harga Barang</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" >
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" colspan="3" >Total Harga</th>
                                    <th class="text-right" id="grandTotal">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="3" >Pembayaran Terakhir</th>
                                    <th class="text-right" id="lastPayment">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="3" >Bayar</th>
                                    <th class="text-right" >
                                        <input type="number" name="payment" id="payment" value="0" class="form-control form-control-sm">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_1" class="form-label">Berkas 1</label>
                                    <input class="form-control" type="file" name="berkas_1" id="berkas_1" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_2" class="form-label">Berkas 2</label>
                                    <input class="form-control" type="file" name="berkas_2" id="berkas_2" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_3" class="form-label">Berkas 3</label>
                                    <input class="form-control" type="file" name="berkas_3" id="berkas_3" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_4" class="form-label">Berkas 4</label>
                                    <input class="form-control" type="file" name="berkas_4" id="berkas_4" />
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <div>
                                    <label for="berkas_5" class="form-label">Berkas 5</label>
                                    <input class="form-control" type="file" name="berkas_5" id="berkas_5" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('admin/cr') }}" class="btn btn-danger">Kembali</a>
                        <button class="btn btn-primary">Buat Data</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>

        let url = '<?= url('/') ?>'
        let pelanggan = document.querySelector('#id_pelanggan')
        let invoice = document.querySelector('#si_penjualan_id')
        let tableBody = document.querySelector('#table-body')

        getInvoice(pelanggan.value);

        pelanggan.onchange = function(e){
            getInvoice(e.target.value)
        }
        invoice.onchange = function (e) {
            getDetailInvoice(e.target.value)
        }

        function getInvoice(idpelanggan){
            let newURL = url + '/admin/cr/get-invoice-by-pelanggan/' + idpelanggan
            getData(newURL, function (result) {
                let html = '<option value="0">-- Pilih Invoice --</option>'
                result.map((item) => {
                    html += `
                        <option value="${item.id}" >${item.nomer_invoice}</option>
                    `
                })
                invoice.innerHTML = html
                getDetailInvoice(invoice.value)
            })
        }

        function getDetailInvoice(idinvoice){
            let newURL = url + '/admin/cr/get-invoice-detail/' + idinvoice
            getData(newURL, function (result) {
                let html = ''
                let grandTotal = 0
                result.detail.map((item) => {
                    html += `
                        <tr>
                            <td>${item.barang.nama_barang}</td>
                            <td class="text-right" >${item.qty}</td>
                            <td class="text-right" >${item.dsc}%</td>
                            <td class="text-right" >Rp. ${formatRupiah(item.harga.toString())}</td>
                        </tr>
                    `
                    grandTotal += (item.harga - (item.harga * (item.dsc / 100))) * item.qty
                })
                if(result.ppn == 1){
                    grandTotal = grandTotal + (grandTotal * (10 / 100))
                }
                document.querySelector('#grandTotal').innerHTML = 'Rp. ' + formatRupiah(grandTotal.toString())
                document.querySelector('#lastPayment').innerHTML = 'Rp. ' + formatRupiah(result.lastpayment.toString())
                document.querySelector('#payment').value = grandTotal - result.lastpayment
                tableBody.innerHTML = html
            })
        }

        function getData(newURL, callback){
            let xhttp = new XMLHttpRequest()
            xhttp.onload = function(){
                let data = JSON.parse(this.responseText)
                callback(data)
            }
            xhttp.open('GET', newURL)
            xhttp.send()
        }

        function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split           = number_string.split(','),
            sisa             = split[0].length % 3,
            rupiah             = split[0].substr(0, sisa),
            ribuan             = split[0].substr(sisa).match(/\d{3}/gi);
        
            // tambahkan titik jika yang di input sudah menjadi angka satuan ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
        
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

    </script>
@endsection