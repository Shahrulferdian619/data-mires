<?php

use App\Http\Controllers\{
    //Setting
    ProfileController,

    //Dashboard
    DashboardController,

    //Gudang, Barang
    PacketController,
    BarangController,
    GudangController,
    KategoriBarangController,

    //Pelanggan
    PelangganController,
    TipePelangganController,

    // Role Akses
    RoleAksesController,

    //Supplier
    TipeSupplierController,
    SupplierController,

    //Pembelian
    PmtPembelianController,
    PoController,
    RiController,
    FakturPembelianController,
    PaymentController,

    //Manage user
    ManageUserController,

    //Sales
    SalesController,
};

//General Ledger
use App\Http\Controllers\GeneralLedger\{CoaController, JurnalVoucherController};

// Penjualan
use App\Http\Controllers\Penjualan\{
    CrController,
    DoController,
    SiController,
    SoController,
};

// Hrga
use App\Http\Controllers\Hrga\{
    EmployeeController,
    AssetsController,
    AssetTypeController,
};

// Kas Bank
use App\Http\Controllers\Kasbank\{BukuBankController, PembayaranController, PenerimaanController};

// Inventory
use App\Http\Controllers\Inventory\{
    InventoryController,
    StockOpnameController,
    MutationController
};
use App\Http\Controllers\Purchasing\PurchaseOrderController;
use App\Http\Controllers\Purchasing\PurchaseRequestController;
use App\Http\Controllers\Report\BukuBesarController;
use App\Http\Controllers\Report\KasBankController;
use App\Http\Controllers\Report\PurchaseController;
use App\Http\Controllers\Report\SalesController as ReportSalesController;

// controller v2
use App\Http\Controllers\v2\ApiController;
use App\Http\Controllers\v2\BerandaController;
use App\Http\Controllers\v2\BukuBesar\CoaController as BukuBesarCoaController;
use App\Http\Controllers\v2\BukuBesar\JurnalUmumController;
use App\Http\Controllers\v2\BukuBesar\LaporanController;
use App\Http\Controllers\v2\Daftar\KategoriPelangganController as MasterKategoriPelangganController;
use App\Http\Controllers\v2\KasBank\PembayaranController as KasBankPembayaranController;
use App\Http\Controllers\v2\KasBank\PenerimaanController as KasBankPenerimaanController;
use App\Http\Controllers\v2\Master\Data\GudangController as DataGudangController;
use App\Http\Controllers\v2\Master\Data\PelangganController as DataPelangganController;
use App\Http\Controllers\v2\Master\Data\SalesController as DataSalesController;
use App\Http\Controllers\v2\Master\Data\SupplierController as DataSupplierController;
use App\Http\Controllers\v2\Pembelian\InvoicePembelianController;
use App\Http\Controllers\v2\Pembelian\PenerimaanBarangController;
use App\Http\Controllers\v2\Pembelian\PermintaanPembelianController;
use App\Http\Controllers\v2\Pembelian\PesananPembelianController;
use App\Http\Controllers\v2\Penjualan\InvoiceKonsinyasiController;
use App\Http\Controllers\v2\Penjualan\InvoicePenjualanController;
use App\Http\Controllers\v2\Penjualan\KonsinyasiController;
use App\Http\Controllers\v2\Penjualan\PenerimaanPenjualanController;
use App\Http\Controllers\v2\Penjualan\PengirimanPenjualanController;
use App\Http\Controllers\v2\Penjualan\PermintaanTesterController;
use App\Http\Controllers\v2\Penjualan\PesananPenjualanController;
use App\Http\Controllers\v2\Persediaan\PindahStokController;
use App\Http\Controllers\v2\Persediaan\StokGudangController;
use App\Http\Controllers\v2\produksi\SemiProduksiController;
use App\Models\v2\Master\KategoriPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('beranda.index'));    
})->middleware('auth');

// == versi 2.0.0 == //
Route::prefix('v2')->middleware(['auth'])->group(function () {

    // Beranda
    Route::get('beranda', [BerandaController::class, 'index'])->name('beranda.index');

    // buku besar
    Route::prefix('bukubesar')->name('bukubesar.')->group(function () {
        // Laporan
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

        // Jurnal umum
        Route::get('jurnal-umum/download-berkas/{nama_berkas}', [JurnalUmumController::class, 'downloadBerkas'])->name('jurnal-umum.download-berkas');
        Route::resource('jurnal-umum', JurnalUmumController::class);

        // COa
        Route::resource('coa', BukuBesarCoaController::class);
    });

    // Kasbank
    Route::prefix('kasbank')->name('kasbank.')->group(function () {

        // Pembayaran
        Route::get('pembayaran/{id}/printPdf', [KasBankPembayaranController::class, 'printPdf'])->name('pembayaran.print-pdf');
        Route::get('pembayaran/{berkas}/downloadBerkas', [KasBankPembayaranController::class, 'downloadBerkas'])->name('pembayaran.download-berkas');
        Route::resource('pembayaran', KasBankPembayaranController::class);

        // Penerimaan
        Route::get('penerimaan/{berkas}/downloadBerkas', [KasBankPenerimaanController::class, 'downloadBerkas'])->name('penerimaan.download-berkas');
        Route::resource('penerimaan', KasBankPenerimaanController::class);
    });

    // Modul Sales
    Route::prefix('penjualan')->group(function () {

        // Konsinyasi
        Route::get('konsinyasi/downloadBerkas/{berkas}', [KonsinyasiController::class, 'downloadBerkas'])->name('konsinyasi.download-berkas');
        Route::get('konsinyasi/print/{konsinyasi}', [KonsinyasiController::class, 'print'])->name('konsinyasi.print');
        Route::get('konsinyasi/print-sj/{konsinyasi}', [KonsinyasiController::class, 'printSj'])->name('konsinyasi.print-sj');
        Route::post('konsinyasi/prosesKirim/{konsinyasi}', [KonsinyasiController::class, 'prosesKirim'])->name('konsinyasi.proses-kirim');
        Route::resource('konsinyasi', KonsinyasiController::class);
        Route::post('kosinyasi/gudang-baru', [KonsinyasiController::class, 'storeGudangBaru'])->name('konsinyasi.gudang-baru');
        Route::post('konsinyasi/pelanggan-baru', [KonsinyasiController::class, 'storePelangganBaru'])->name('konsinyasi.pelanggan-baru');
        Route::post('konsinyasi/downloadExcel/', [KonsinyasiController::class, 'downloadExcel'])->name('konsinyasi.download-excel');
        // End Konsinyasi

        // Penjualan //
        Route::post('pesanan-penjualan/pelangganBaru', [PesananPenjualanController::class, 'storePelangganBaru'])->name('pesanan-penjualan.pelanggan-baru');
        Route::get('pesanan-penjualan/downloadBerkas/{berkas}', [PesananPenjualanController::class, 'downloadBerkas'])->name('pesanan-penjualan.download-berkas');
        Route::get('pesanan-penjualan/print/{pesanan_penjualan}', [PesananPenjualanController::class, 'print'])->name('pesanan-penjualan.print');
        Route::resource('pesanan-penjualan', PesananPenjualanController::class);
        Route::post('pesanan-penjualan/downloadExcel/', [PesananPenjualanController::class, 'downloadExcel'])->name('pesanan-penjualan.download-excel');

        Route::get('pengiriman-penjualan', [PengirimanPenjualanController::class, 'index'])->name('pengiriman-penjualan.index');
        Route::get('pengiriman-penjualan/show/{pengiriman_penjualan}', [PengirimanPenjualanController::class, 'show'])->name('pengiriman-penjualan.show');
        Route::post('pengiriman-penjualan/prosesKirim/', [PengirimanPenjualanController::class, 'prosesKirim'])->name('pengiriman-penjualan.proses-kirim');
        Route::get('pengiriman-penjualan/print-sj/{pengiriman_penjualan}', [PengirimanPenjualanController::class, 'printSj'])->name('pengiriman-penjualan.print-sj');
        Route::post('pengiriman-penjualan/downloadExcel/', [PengirimanPenjualanController::class, 'downloadExcel'])->name('pengiriman-penjualan.download-excel');

        // Permintaan Tester
        Route::get('permintaan-tester', [PermintaanTesterController::class, 'index'])->name('permintaan-tester.index');
        Route::get('permintaan-tester/create', [PermintaanTesterController::class, 'create'])->name('permintaan-tester.create');
        Route::post('permintaan-tester', [PermintaanTesterController::class, 'store'])->name('permintaan-tester.store');
        Route::get('permintaan-tester/show/{tester}', [PermintaanTesterController::class, 'show'])->name('permintaan-tester.show');
        Route::get('permintaan-tester/{tester}/edit', [PermintaanTesterController::class, 'edit'])->name('permintaan-tester.edit');
        Route::put('permintaan-tester/{tester}', [PermintaanTesterController::class, 'update'])->name('permintaan-tester.update');
        Route::delete('permintaan-tester/{tester}', [PermintaanTesterController::class, 'destroy'])->name('permintaan-tester.destroy');
        Route::get('permintaan-tester/print/{tester}', [PermintaanTesterController::class, 'print'])->name('permintaan-tester.print');
        Route::post('permintaan-tester/prosesKirim/{tester}', [PermintaanTesterController::class, 'prosesKirim'])->name('permintaan-tester.proses-kirim');
        Route::get('permintaan-tester/print-sj/{tester}', [PermintaanTesterController::class, 'printSj'])->name('permintaan-tester.print-sj');

        // Invoice Penjualan
        Route::get('invoice-penjualan/download-berkas/{nama_berkas}', [InvoicePenjualanController::class, 'downloadBerkas'])->name('invoice-penjualan.download-berkas');
        Route::get('invoice-penjualan/{invoice_penjualan}/print', [InvoicePenjualanController::class, 'print'])->name('invoice-penjualan.print');
        Route::post('invoice-penjualan/generateInvoiceManual', [InvoicePenjualanController::class, 'generateInvoiceManual'])->name('invoice-penjualan.generate-invoice-manual');
        Route::resource('invoice-penjualan', InvoicePenjualanController::class);

        // Penjualan Konsinyasi
        Route::resource('invoice-konsinyasi', InvoiceKonsinyasiController::class);

        // Penerimaan Penjualan
        Route::get('penerimaan-penjualan/getDetilInvoice', [PenerimaanPenjualanController::class, 'getDetilInvoice'])->name('penerimaan-penjualan.get-detil-invoice');
        Route::resource('penerimaan-penjualan', PenerimaanPenjualanController::class);
        // End Penjualan //
    });

    // Modul Persediaan
    Route::prefix('persediaan')->group(function () {
        Route::get('stok', [StokGudangController::class, 'index'])->name('persediaan.stok-gudang');
        Route::post('downloadExcel', [StokGudangController::class, 'downloadExcel'])->name('persediaan.download-excel');

        // pindah stok
        Route::get('pindah-stok/{pindah_stok}/print', [PindahStokController::class, 'print'])->name('pindah-stok.print');
        Route::get('pindah-stok/{pindah_stok}/print-sj', [PindahStokController::class, 'printSj'])->name('pindah-stok.print-sj');
        Route::post('pindah-stok/proses-kirim/{pindah_stok}', [PindahStokController::class, 'prosesKirim'])->name('pindah-stok.proses-kirim');
        Route::get('pindah-stok/{pindah_stok}/print', [PindahStokController::class, 'print'])->name('pindah-stok.print');
        Route::resource('pindah-stok', PindahStokController::class);
    });

    // Modul pembelian
    Route::prefix('pembelian')->name('pembelian.')->group(function () {

        // modul permintaan pembelian
        Route::prefix('permintaan-pembelian')->name('permintaan-pembelian.')->group(function () {
            Route::get('getDetil', [PermintaanPembelianController::class, 'getDetil'])->name('get-detil');
            Route::get('download-berkas/{nama_berkas}', [PermintaanPembelianController::class, 'downloadBerkas'])->name('download-berkas');
            Route::put('approve-direktur/{id}', [PermintaanPembelianController::class, 'approveDirektur'])->name('approve-direktur');
            Route::put('reject-direktur/{id}', [PermintaanPembelianController::class, 'rejectDirektur'])->name('reject-direktur');
            Route::get('{id}/print-pdf', [PermintaanPembelianController::class, 'printPdf'])->name('print-pdf');
            Route::get('{permintaan_pembelian}/revisi', [PermintaanPembelianController::class, 'revisi'])->name('revisi');
            Route::patch('revisi/{permintaan_pembelian}', [PermintaanPembelianController::class, 'prosesRevisi'])->name('proses-revisi');
        });
        Route::resource('permintaan-pembelian', PermintaanPembelianController::class);

        // modul pesanan pembelian
        Route::prefix('pesanan-pembelian')->name('pesanan-pembelian.')->group(function () {
            Route::get('download-berkas/{nama_berkas}', [PesananPembelianController::class, 'downloadBerkas'])->name('download-berkas');
            Route::put('approve-pengajuan/{id}', [PesananPembelianController::class, 'approvePengajuan'])->name('approve-pengajuan');
            Route::put('reject-pengajuan/{id}', [PesananPembelianController::class, 'rejectPengajuan'])->name('reject-pengajuan');
            Route::get('{id}/print-pdf', [PesananPembelianController::class, 'printPdf'])->name('print-pdf');
            Route::get('{pesanan_pembelian}/revisi-pengajuan', [PesananPembelianController::class, 'revisiPengajuan'])->name('revisi-pengajuan');
            Route::patch('revisi-pengajuan/{pesanan_pembelian}', [PesananPembelianController::class, 'createRevisi'])->name('create-revisi');
            Route::get('{id}/print-ttd', [PesananPembelianController::class, 'printTtd'])->name('print-ttd');
            Route::get('{id}/print-nonttd', [PesananPembelianController::class, 'printNonTtd'])->name('print-nonttd');
            Route::get('getDetil/{id}', [PesananPembelianController::class, 'getDetil'])->name('get-detil');
        });
        Route::resource('pesanan-pembelian', PesananPembelianController::class);

        // modul penerimaan barang
        Route::prefix('penerimaan-barang')->name('penerimaan-barang.')->group(function () {
            Route::get('{nama_berkas}/download-berkas', [PenerimaanBarangController::class, 'downloadBerkas'])->name('download-berkas');
        });
        Route::resource('penerimaan-barang', PenerimaanBarangController::class);

        // modul invoice pembelian
        Route::prefix('invoice-pembelian')->name('invoice-pembelian.')->group(function () {
            Route::get('{nama_berkas}/download-berkas', [InvoicePembelianController::class, 'downloadBerkas'])->name('download-berkas');
        });
        Route::resource('invoice-pembelian', InvoicePembelianController::class);
    });

    // Modul Master Kategori
    Route::prefix('master/kategori')->name('master-kategori.')->group(function () {

        // Modul Kategori Pelanggan
        Route::resource('pelanggan', MasterKategoriPelangganController::class);

    });

    // Modul Produksi
    Route::prefix('produksi')->name('produksi.')->group(function (){

        // semi produksi
        Route::get('semi-index',[SemiProduksiController::class, 'index'])->name('semi-index');
        Route::get('semi-create',[SemiProduksiController::class, 'create'])->name('semi-create');
        Route::get('semi-show/{id}',[SemiProduksiController::class, 'show'])->name('semi-show');
        Route::get('semi-edit/{id}',[SemiProduksiController::class, 'edit'])->name('semi-edit');
        Route::patch('semi-update/{id}',[SemiProduksiController::class, 'update'])->name('semi-update');
        Route::post('semi-store',[SemiProduksiController::class, 'store'])->name('semi-store');
    });

    // Modul master Data
    Route::prefix('master/data')->name('master-data.')->group(function () {

        // Modul supplier
        Route::resource('supplier', DataSupplierController::class);

        // Modeul Sales
        Route::resource('sales', DataSalesController::class);

        // Modul pelanggan
        Route::resource('pelanggan', DataPelangganController::class);

        // Modul gudang
        Route::resource('gudang', DataGudangController::class);
    });

    // Api Global //
    // Insert data pelanggan
    Route::post('storePelanggan', [ApiController::class, 'storePelangganBaru'])->name('api.store-pelanggan');

    // Get data produk
    Route::get('getDataProduk/{id}', [ApiController::class, 'getProduk'])->name('api.get-data-produk');
});

// == MODUL PURCHASING == //
Route::middleware(['auth'])->prefix('purchasing')->group(function () {

    // == Purchase Request == //
    Route::prefix('purchase_request')->group(function () {
        Route::get('', [PurchaseRequestController::class, 'index'])->name('purchase_request.index');
    });

    // == Purchase Order == //
    Route::prefix('purchase_order')->group(function () {
        Route::get('', [PurchaseOrderController::class, 'index'])->name('purchase_order.index');
    });
});

// == MODUL SALES == //
Route::middleware(['auth'])->prefix('sales')->group(function () {

    // Sales Order //
    Route::prefix('so')->group(function () {
        Route::get('{id}/edit', [SoController::class, 'edit_v2'])->name('so.edit_v2');
        Route::post('', [SoController::class, 'update_v2'])->name('so.update_v2');

        // print_do
        Route::get('{id}/print_do', [SoController::class, 'print_do'])->name('so.print_do');
        // print_so
        Route::get('{id}/print_so', [SoController::class, 'print_so'])->name('so.print_so');
    });

    // Delivery Order //
    Route::prefix('do')->group(function () {
        Route::get('', [DoController::class, 'index'])->name('do.index');
        Route::get('{id}', [DoController::class, 'show'])->name('do.show');

        // print surat jalan
        Route::get('{id}/print_sj', [DoController::class, 'print_sj'])->name('do.print_sj');
    });
});

// Middleware Auth dan //Set prefix url to admin/  dan name to admin.
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('filterrevenue/{month}/{year}', [DashboardController::class, 'filterrevenue']);
    // Setting
    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile/change-profile', [ProfileController::class, 'updateProfile']);

    Route::get('profile/change-picture', [ProfileController::class, 'changePicture']);
    Route::post('profile/change-picture', [ProfileController::class, 'updatePicture']);

    Route::get('profile/change-password', [ProfileController::class, 'changePassword']);
    Route::post('profile/change-password', [ProfileController::class, 'updatePassword']);

    Route::get('profile/change-signature', [ProfileController::class, 'changeSignature']);
    Route::post('profile/change-signature', [ProfileController::class, 'updateSignature']);

    // Role Management
    route::get('role-akses', [RoleAksesController::class, 'index']);
    route::get('role-akses/{id}', [RoleAksesController::class, 'show'])->whereNumber('id');
    route::post('role-akses/{id}', [RoleAksesController::class, 'store_update'])->whereNumber('id');

    // Supplier
    Route::resource('tipesupplier', TipeSupplierController::class);
    Route::get('tipesupplier/export/PDF', [TipeSupplierController::class, 'exportPDF'])->name('tipesupplier.exportPDF');
    Route::get('tipesupplier/print/PDF', [TipeSupplierController::class, 'printPDF'])->name('tipesupplier.printPDF');
    Route::resource('supplier', SupplierController::class);
    Route::get('supplier/export/PDF', [SupplierController::class, 'exportPDF'])->name('supplier.exportPDF');
    Route::get('supplier/print/PDF', [SupplierController::class, 'printPDF'])->name('supplier.printPDF');

    // Pelanggan
    Route::resource('tipepelanggan', TipePelangganController::class);
    Route::get('tipepelanggan/export/PDF', [TipePelangganController::class, 'exportPDF'])->name('tipepelanggan.exportPDF');
    Route::get('tipepelanggan/print/PDF', [TipePelangganController::class, 'printPDF'])->name('tipepelanggan.printPDF');
    Route::resource('pelanggan', PelangganController::class);
    Route::get('pelanggan/export/PDF', [PelangganController::class, 'exportPDF'])->name('pelanggan.exportPDF');
    Route::get('pelanggan/print/PDF', [PelangganController::class, 'printPDF'])->name('pelanggan.printPDF');
    Route::get('pelanggan/get-city/{id}', [PelangganController::class, 'getCity']);

    // Dashboard 
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::post('dashboard/get-omset', [DashboardController::class, 'getOmset'])->name('dashboard.omset');

    // Faktur Pembelian (Tagihan)
    Route::get('fakturpembelian/rincian/{id}', [FakturPembelianController::class, 'get_penerimaan_rinci']);
    Route::get('fakturpembelian/penerimaan/{id}', [FakturPembelianController::class, 'get_penerimaan']);
    Route::get('fakturpembelian/get-data-rinci/{supplier}/{basedon}', [FakturPembelianController::class, 'get_rinci'])->whereNumber(['supplier', 'basedon']);
    Route::get('fakturpembelian/hapus-pilihan/{basedon}/{index}', [FakturPembelianController::class, 'hapus_index_pilihan'])->whereNumber(['basedon', 'index']);
    Route::get('fakturpembelian/hapus-semua-pilihan', [FakturPembelianController::class, 'hapus_semua_pilihan']);
    Route::resource('fakturpembelian', FakturPembelianController::class);
    Route::get('fakturpembelian/export/PDF', [FakturPembelianController::class, 'exportPDF'])->name('fakturpembelian.exportPDF');
    Route::get('fakturpembelian/print/PDF', [FakturPembelianController::class, 'printPDF'])->name('fakturpembelian.printPDF');
    // Approve Faktur
    Route::post('fakturpembelian/approval/{id}', [FakturPembelianController::class, 'approval']);

    // Permintaan Pembelian
    Route::get('harga-produk/{id}', [PmtPembelianController::class, 'get_harga_produk']);
    Route::post('pmtpembelian/store-produk', [PmtPembelianController::class, 'store_produk']);
    Route::get('pmtpembelian/get-cart-produk', [PmtPembelianController::class, 'get_produk_temp']);
    Route::get('pmtpembelian/rincian/{id}', [PmtPembelianController::class, 'get_pmtpembelian_rinci_id']);
    Route::get('pmtpembelian/hapus-rincian/{key}', [PmtPembelianController::class, 'hapus_rincian'])->whereNumber('key');
    Route::get('pmtpembelian/hapus-data-rincian/{id}', [PmtPembelianController::class, 'hapus_data_rincian'])->whereNumber('id');
    Route::resource('pmtpembelian', PmtPembelianController::class);
    Route::get('pmtpembelian/create/{tipe}', [PmtPembelianController::class, 'create']);
    Route::get('pmtpembelian/{id}/print', [PmtPembelianController::class, 'print'])->name('pmtpembelian.print');
    Route::get('pmtpembelian/{id}/print-nonttd', [PmtPembelianController::class, 'printNonTtd'])->name('pmtpembelian.print-nonttd');
    Route::get('pmtpembelian/export/PDF', [PmtPembelianController::class, 'exportPDF'])->name('pmtpembelian.exportPDF');
    Route::get('pmtpembelian/print/PDF', [PmtPembelianController::class, 'printPDF'])->name('pmtpembelian.printPDF');
    // approve pmt
    Route::post('pmtpembelian/update/{id}', [PmtPembelianController::class, 'update'])->whereNumber('id');
    Route::post('pmtpembelian/approval/{id}', [PmtPembelianController::class, 'approval']);
    Route::post('pmtpembelian/del-produk/{id}', [PmtPembelianController::class, 'del_produk_temp']);
    Route::post('pmtpembelian/destroy-rinci', [PmtPembelianController::class, 'destroyRinci'])->name('pmtpembelian.destroy.rinci');

    // Pesanan Pembelian (PO)
    Route::get('po/create', [PoController::class, 'create']);
    Route::post('po/store', [PoController::class, 'store']);
    Route::get('po/cancel', [PoController::class, 'cancel']);
    Route::get('po/belum-diproses/{id_supplier}', [PoController::class, 'belum_diterima']); //permintaan pembelian belum diterima
    Route::get('po/rincian/{id}', [PoController::class, 'get_po_rinci_by_id']);
    Route::resource('po', PoController::class);
    Route::get('po/{id}/print', [PoController::class, 'print']);
    Route::get('po/{id}/print-nonttd', [PoController::class, 'printNonTtd']);
    Route::delete('po/delete/{popembelian}', [PoController::class, 'destroy'])->whereNumber('popembelian');
    Route::get('po/export/PDF', [PoController::class, 'exportPDF'])->name('popembelian.exportPDF');
    Route::get('po/print/PDF', [PoController::class, 'printPDF'])->name('popembelian.printPDF');

    // approve po
    Route::post('popembelian/approval/{id}', [PoController::class, 'approval']);

    // Penerimaan Barang (RI)
    Route::get('ri/create', [RiController::class, 'create']);
    Route::post('ri/store', [RiController::class, 'store']);
    Route::get('ri/cancel', [RiController::class, 'cancel']);
    Route::resource('ri', RiController::class);
    Route::get('ri/export/PDF', [RiController::class, 'exportPDF'])->name('ri.exportPDF');
    Route::get('ri/print/PDF', [RiController::class, 'printPDF'])->name('ri.printPDF');
    // Route::get('ri/batal-pembelian-rinci', [RiController::class, 'cancel_pmtpembelian_rinci']);

    // Pembayaran
    Route::resource('pembayaranpembelian', PaymentController::class);
    Route::get('pembayaranpembelian/faktur/{id}', [PaymentController::class, 'get_faktur_by_supplier']);
    Route::get('pembayaranpembelian/export/PDF', [PaymentController::class, 'exportPDF'])->name('pembayaranpembelian.exportPDF');
    Route::get('pembayaranpembelian/print/PDF', [PaymentController::class, 'printPDF'])->name('pembayaranpembelian.printPDF');

    // Manage user
    Route::get('manageuser', [ManageUserController::class, 'index'])->name('manageuser.index');
    Route::get('manageuser/create', [ManageUserController::class, 'create'])->name('manageuser.create');

    // Pesanan Penjualan (SO)
    Route::get('so', [SoController::class, 'index'])->name('so.index');
    Route::get('so/{so}', [SoController::class, 'show'])->whereNumber('so');
    Route::get('so/{so}/print-do', [SoController::class, 'printdo'])->whereNumber('so');
    Route::get('so/{so}/edit', [SoController::class, 'edit'])->whereNumber('so');
    Route::post('so/{so}/edit', [SoController::class, 'update'])->whereNumber('so');
    Route::post('so/update-checked/{so}', [SoController::class, 'updateChecked'])->whereNumber('so');
    Route::delete('so/delete/{so}', [SoController::class, 'destroy'])->whereNumber('so');
    Route::get('so/create', [SoController::class, 'create']);
    Route::get('so/hapus-cart/{key}', [SoController::class, 'deleteCart'])->whereNumber('key');
    Route::post('so', [SoController::class, 'store'])->name('so.store');
    Route::post('so/destroy-rinci', [SoController::class, 'destroyRinci'])->name('so.destroy.rinci');
    Route::get('so/export/PDF', [SoController::class, 'exportPDF'])->name('so.exportPDF');
    Route::get('so/print/PDF', [SoController::class, 'printPDF'])->name('so.printPDF');

    Route::get('so/get-paket/{id}', [SoController::class, 'getPacket']);

    // Pesanan Penjualan (DO)
    // Route::get('do', [DoController::class, 'index']);
    Route::get('do', [DoController::class, 'index'])->name('do.index');
    Route::get('do/create', [DoController::class, 'create']);
    Route::get('do/{do}', [DoController::class, 'show'])->whereNumber('do');
    Route::get('do/{do}/surat-jalan', [DoController::class, 'print'])->whereNumber('do');
    Route::get('do/{do}/edit', [DoController::class, 'edit'])->whereNumber('do');
    Route::post('do/{do}/edit', [DoController::class, 'update'])->whereNumber('do');
    Route::delete('do/delete/{do}', [DoController::class, 'destroy'])->whereNumber('do');
    Route::get('do/get_so/{id}', [DoController::class, 'get_so']);
    Route::get('do/get_so_rinci/{id}', [DoController::class, 'get_so_rinci']);
    Route::post('do/store/', [DoController::class, 'store']);
    Route::get('do/export/PDF', [DoController::class, 'exportPDF'])->name('do.exportPDF');
    Route::get('do/print/PDF', [DoController::class, 'printPDF'])->name('do.printPDF');

    // Invoice Penjualan (SI)
    // Route::get('si', [SiController::class, 'index']);
    Route::get('si', [SiController::class, 'index'])->name('si.index');
    Route::get('si/create', [SiController::class, 'create']);
    Route::get('si/{si}', [SiController::class, 'show'])->whereNumber('si');
    Route::post('si/{si}', [SiController::class, 'update'])->whereNumber('si');
    Route::delete('si/{si}', [SiController::class, 'destroy'])->whereNumber('si');
    Route::get('si/{si}/edit', [SiController::class, 'edit'])->whereNumber('si');
    Route::get('si/{si}/print', [SiController::class, 'print'])->whereNumber('si');
    Route::get('si/get_so/{id}', [SiController::class, 'get_so']);
    Route::get('si/get_so_rinci/{id}', [SiController::class, 'get_so_rinci']);
    Route::post('si/store/', [SiController::class, 'store']);
    Route::get('si/export/PDF', [SiController::class, 'exportPDF'])->name('si.exportPDF');
    Route::get('si/print/PDF', [SiController::class, 'printPDF'])->name('si.printPDF');

    // Pesanan Penjualan (CR)
    // Route::get('cr', [CrController::class, 'index']);
    Route::get('cr', [CrController::class, 'index'])->name('cr.index');
    Route::get('cr/{cr}', [CrController::class, 'show'])->whereNumber('cr');
    Route::get('cr/get-invoice/{idPelanggan}', [CrController::class, 'getInvoice'])->whereNumber('idPelanggan');
    Route::get('cr/create', [CrController::class, 'create']);
    Route::post('cr', [CrController::class, 'store']);
    Route::get('cr/export/PDF', [CrController::class, 'exportPDF'])->name('cr.exportPDF');
    Route::get('cr/print/PDF', [CrController::class, 'printPDF'])->name('cr.printPDF');

    //Hrga Employee
    // Route::resource('employee', EmployeeController::class);
    Route::resource('employee', EmployeeController::class);
    Route::get('employee/export/PDF', [EmployeeController::class, 'exportPDF'])->name('employee.exportPDF');
    Route::get('employee/print/PDF', [EmployeeController::class, 'printPDF'])->name('employee.printPDF');

    //Hrga Asset
    Route::resource('asset', AssetsController::class);
    Route::get('asset/export/PDF', [AssetsController::class, 'exportPDF'])->name('asset.exportPDF');
    Route::get('asset/print/PDF', [AssetsController::class, 'printPDF'])->name('asset.printPDF');

    //Hrga Tipe Asset
    Route::resource('tipeasset', AssetTypeController::class);
    Route::get('tipeasset/export/PDF', [AssetTypeController::class, 'exportPDF'])->name('tipeasset.exportPDF');
    Route::get('tipeasset/print/PDF', [AssetTypeController::class, 'printPDF'])->name('tipeasset.printPDF');

    Route::get('report-purchase', [PurchaseController::class, 'index']);
    Route::post('report-purchase/order-per-vendor', [PurchaseController::class, 'orderPerVendor']);
    Route::post('report-purchase/order-per-item', [PurchaseController::class, 'orderPerItem']);
    Route::post('report-purchase/payment', [PurchaseController::class, 'payment']);

    // Sales
    Route::get('report-sales', [ReportSalesController::class, 'index']);
    Route::post('report-sales/sales-per-customer', [ReportSalesController::class, 'salesPerCustomer']);
    Route::post('report-sales/sales-per-item', [ReportSalesController::class, 'salesPerItem']);
    Route::post('report-sales/payment', [ReportSalesController::class, 'payment']);

    // Barang
    Route::resource('kategoribarang', KategoriBarangController::class);
    Route::post('kategoribarang/importExcel', [KategoriBarangController::class, 'importExcel'])->name('kategoribarang.importExcel');
    Route::get('kategoribarang/exportExcel/{type}', [KategoriBarangController::class, 'exportExcel'])->name('kategoribarang.exportExcel');
    Route::get('kategoribarang/export/PDF', [KategoriBarangController::class, 'exportPDF'])->name('kategoribarang.exportPDF');
    Route::get('kategoribarang/print/PDF', [KategoriBarangController::class, 'printPDF'])->name('kategoribarang.printPDF');
    Route::resource('barang', BarangController::class);
    Route::get('catalog', [BarangController::class, 'index'])->name('catalog');
    Route::get('barang/export/PDF', [BarangController::class, 'exportPDF'])->name('barang.exportPDF');
    Route::get('barang/print/PDF', [BarangController::class, 'printPDF'])->name('barang.printPDF');

    // Packet
    Route::resource('packet', PacketController::class);

    // Gudang
    Route::resource('gudang', GudangController::class);
    Route::get('gudang/export/PDF', [GudangController::class, 'exportPDF'])->name('gudang.exportPDF');
    Route::get('gudang/print/PDF', [GudangController::class, 'printPDF'])->name('gudang.printPDF');

    //General Ledger / COA
    Route::resource('coa', CoaController::class);
    Route::get('coa/export/PDF', [CoaController::class, 'exportPDF'])->name('coa.exportPDF');
    Route::get('coa/print/PDF', [CoaController::class, 'printPDF'])->name('coa.printPDF');

    //Sales
    Route::resource('sales', SalesController::class);
    Route::get('sales/export/PDF', [SalesController::class, 'exportPDF'])->name('sales.exportPDF');
    Route::get('sales/print/PDF', [SalesController::class, 'printPDF'])->name('sales.printPDF');

    //General Ledger / Jurnal Voucher
    Route::resource('jurnal-voucher', JurnalVoucherController::class);
    Route::get('jurnal-voucher/show/{nomer}',  [JurnalVoucherController::class, 'showBukuBank']);
    Route::get('jurnal-voucher/export/PDF', [JurnalVoucherController::class, 'exportPDF'])->name('jurnal-voucher.exportPDF');
    Route::get('jurnal-voucher/print/PDF', [JurnalVoucherController::class, 'printPDF'])->name('jurnal-voucher.printPDF');

    //General Ledger / Hapus jurnal voucher rinci
    Route::post('jurnal-voucher-rinci/destroy', [JurnalVoucherController::class, 'destroyJurnalVoucherRinci'])->name('jurnal-voucher-rinci.destroy');

    //Buku Bank / Penerimaan
    Route::resource('penerimaan', PenerimaanController::class);
    Route::get('penerimaan/show/{nomer}',  [PenerimaanController::class, 'showBukuBank']);
    Route::post('penerimaan/export-excel/{nomer}',  [PenerimaanController::class, 'exportExcelByNomer']);
    Route::post('penerimaan/export-excel-all',  [PenerimaanController::class, 'exportExcelAll']);
    Route::post('penerimaan/export-pdf/{nomer}',  [PenerimaanController::class, 'exportPDFByNomer']);
    Route::get('penerimaan/{id}/print',  [PenerimaanController::class, 'printBukuBank']);
    Route::get('penerimaan/export/PDF', [PenerimaanController::class, 'exportPDF'])->name('penerimaan.exportPDF');
    Route::get('penerimaan/print/PDF', [PenerimaanController::class, 'printPDF'])->name('penerimaan.printPDF');

    //Buku Bank / Pembayaran
    Route::resource('pembayaran', PembayaranController::class);
    Route::get('pembayaran/show/{nomer}',  [PembayaranController::class, 'showBukuBank']);
    Route::post('pembayaran/export-excel/{nomer}',  [PembayaranController::class, 'exportExcelByNomer']);
    Route::post('pembayaran/export-excel-all',  [PembayaranController::class, 'exportExcelAll']);
    Route::post('pembayaran/export-pdf/{nomer}',  [PembayaranController::class, 'exportPDFByNomer']);
    Route::get('pembayaran/{id}/print',  [PenerimaanController::class, 'printBukuBank']);
    Route::get('pembayaran/export/PDF', [PembayaranController::class, 'exportPDF'])->name('pembayaran.exportPDF');
    Route::get('pembayaran/print/PDF', [PembayaranController::class, 'printPDF'])->name('pembayaran.printPDF');

    //Buku Bank / Hapus buku bank rinci
    Route::post('buku-bank-rinci/destroy', [PenerimaanController::class, 'destroyBukuBankRinci'])->name('buku-bank-rinci.destroy');

    // Iventory
    // Route::get('list-inventory', [InventoryController::class, 'index']);
    Route::get('list-inventory/{type}', [InventoryController::class, 'index']);
    Route::get('list-inventory/show/{id}', [InventoryController::class, 'show'])->whereNumber('id');
    Route::post('list-inventory/menu/stock-in', [InventoryController::class, 'store']);
    Route::get('list-inventory/menu/stock-in', [InventoryController::class, 'itemIn']);
    Route::get('stock-opname', [StockOpnameController::class, 'index']);
    Route::post('stock-opname', [StockOpnameController::class, 'store']);
    Route::get('mutation-inventory', [MutationController::class, 'index']);
    Route::get('mutation-history', [MutationController::class, 'history']);
    Route::post('mutation', [MutationController::class, 'store']);
    Route::get('list-inventory/export/PDF', [InventoryController::class, 'exportPDF'])->name('list-inventory.exportPDF');
    Route::get('list-inventory/print/PDF', [InventoryController::class, 'printPDF'])->name('list-inventory.printPDF');
    Route::get('get_barang_by_gudang/{id}', [StockOpnameController::class, 'get_barang_by_gudang'])->whereNumber('id');
    Route::get('mutation/get_barang_by_gudang/{id}', [MutationController::class, 'get_barang_by_gudang'])->whereNumber('id');

    // BUKU BANK
    Route::get('buku-bank', [BukuBankController::class, 'index'])->name('buku-bank.index');
    Route::get('buku-bank/get-data', [BukuBankController::class, 'getData'])->name('buku-bank.get');



    // ROUTE REPORTING

    // Buku Besar
    Route::get('report-buku-besar', [BukuBesarController::class, 'index'])->name('report.buku-besar.index');
    Route::post('report-buku-besar-rinci', [BukuBesarController::class, 'bukuBesarRinci'])->name('report.buku-besar.rinci');

    // KasBank
    Route::get('report-kas-bank', [KasBankController::class, 'index'])->name('report.kas-bank.index');
    Route::post('report-arus-kas', [KasBankController::class, 'arusKas'])->name('report.kas-bank.aruskas');
    Route::post('report-buku-bank', [KasBankController::class, 'bukuBank'])->name('report.kas-bank.bukubank');

    Route::get('qrcode', function () {
        return view('qrcode');
    });
});

Route::get('tiktok/', function (Request $request) {
    return $request;
});

Route::get('shopee/', function (Request $request) {
    return $request;
});

require __DIR__ . '/auth.php';
