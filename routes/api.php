<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\v2\ApiController as V2ApiController;
use App\Http\Controllers\v2\Daftar\PelangganController;
use App\Http\Controllers\v2\Penjualan\PesananPenjualanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API v2
Route::prefix('v2')->group(function() {

    // get harga produk
    Route::get('getHargaProduk/{id}', [PesananPenjualanController::class, 'getHargaProduk']);
    Route::get('getDataProduk', [V2ApiController::class,'getDataProduk']);
    //Route::get('getProduk/{id}', [V2ApiController::class,'getProduk'])->name('api.get-produk');

    // get data rincian paket by paket_id
    Route::get('getRincianPaket', [V2ApiController::class,'getRincianPaket']);

    // get data pelanggan
    Route::get('getPelanggan/{id}', [PelangganController::class, 'getPelanggan']);
    // get kode pelanggan
    Route::get('getKodePelanggan/{kode_pelanggan}', [V2ApiController::class, 'getKodePelanggan']);

    // get data kota by prov id
    Route::get('getKotaByProv/{id}', [V2ApiController::class, 'getKotaByProv']);

    // controller barang crud
    Route::get('barang', [BarangController::class,'indexApi']);
    Route::post('barang/store', [BarangController::class, 'storeApi']);
    Route::post('barang/update/{id}', [BarangController::class, 'updateApi']);
    Route::get('barang/show/{id}', [BarangController::class, 'showApi']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-pmtpembelian-rinci/{id}', [APIController::class, 'getPMTPembelianRinciById']);
Route::get('get-detail-pelanggan/{id}', [APIController::class, 'getDetailPelanggan']);
Route::get('get-pie-chart-data', [APIController::class, 'pieChart']);
Route::get('get-product-by-id/{id}', [APIController::class, 'getProduct']);
