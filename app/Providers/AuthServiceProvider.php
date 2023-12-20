<?php

namespace App\Providers;

//use App\Policies\PmtpembelianPolicy;
use App\Models\User;
use App\Models\Roleakses;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

        // Modul General Ledger
        'App\Models\Coa' => 'App\Policies\Generalledger\CoaPolicy',
        'App\Models\JurnalVoucher' => 'App\Policies\Generalledger\JvPolicy',

        // Modul HRGA
        'App\Models\AssetType' => 'App\Policies\Hrga\CategoryAssetPolicy',
        'App\Models\Employees' => 'App\Policies\Hrga\EmployeePolicy',
        'App\Models\Assets' => 'App\Policies\Hrga\AssetPolicy',

        // Modul CashBank
        'App\Models\BukuBank' => 'App\Policies\Cashbank\CashBankPolicy',
        'App\Models\BukuBankRinci' => 'App\Policies\Cashbank\BukuBankPolicy',

        // Modul Purchasing
        'App\Models\Pmtpembelian' => 'App\Policies\Purchasing\PmtpembelianPolicy',
        'App\Models\Popembelian' => 'App\Policies\Purchasing\PopembelianPolicy',
        'App\Models\Ri' => 'App\Policies\Purchasing\RiPolicy',
        'App\Models\fakturpembelian' => 'App\Policies\Purchasing\FakturpembelianPolicy',
        'App\Models\Payment' => 'App\Policies\Purchasing\PaymentPolicy',
               
        //Modul Inventory
        'App\Models\TransaksiBarang' => 'App\Policies\Inventory\InventoryPolicy',

        // Modul Category
        'App\Models\TipeSupplier' => 'App\Policies\Kategori\TipeSupplierPolicy',
        'App\Models\Kategoribarang' => 'App\Policies\Kategori\KategoriBarangPolicy',
        'App\Models\TipePelanggan' => 'App\Policies\Kategori\TipePelangganPolicy',
        
        // Modul Master
        'App\Models\Barang' => 'App\Policies\Master\BarangPolicy',
        'App\Models\Packet' => 'App\Policies\Master\PacketPolicy',
        'App\Models\Gudang' => 'App\Policies\Master\GudangPolicy',
        'App\Models\Pelanggan' => 'App\Policies\Master\PelangganPolicy',
        'App\Models\Supplier' => 'App\Policies\Master\SupplierPolicy',
        'App\Models\Sales' => 'App\Policies\Master\SalesPolicy',

        // Modul Sales
        'App\Models\Penjualan_SO' => 'App\Policies\Sales\SalesOrderPolicy',
        'App\Models\Penjualan_DO' => 'App\Policies\Sales\DeliveryOrderPolicy',
        'App\Models\Penjualan_Invoice' => 'App\Policies\Sales\SalesInvoicePolicy',
        'App\Models\PenjualanCR' => 'App\Policies\Sales\CustomerReceiptPolicy',

        // Modul Role akses
        'App\Models\Roleakses' => 'App\Policies\RoleAkses\RoleAksesPolicy',

        // Versi 2

        // Penjualan
        'App\Models\v2\Penjualan\Pesanan' => 'App\Policies\v2\Penjualan\PesananPenjualanPolicy',
        'App\Models\v2\Penjualan\Konsinyasi' => 'App\Policies\v2\Penjualan\KonsinyasiPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    
        //Bypass Super Admin
        Gate::before(function ($user, $ability) {
            if ($user->id == 1) {
                return true;
            }
        });

        //ListInventory
        Gate::define('index-inventory', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_li' , 'user_id' => auth()->id()])->where('can_index', 1)->first() != null)
                return true;
        });
        Gate::define('read-inventory', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_li' , 'user_id' => auth()->id()])->where('can_read', 1)->first() != null)
                return true;
        });
        Gate::define('create-inventory', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_li' , 'user_id' => auth()->id()])->where('can_create', 1)->first() != null)
                return true;
        });

        //MutationInventory
        Gate::define('index-mutation', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_mt' , 'user_id' => auth()->id()])->where('can_index', 1)->first() != null)
                return true;
        });

        Gate::define('create-mutation', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_mt' , 'user_id' => auth()->id()])->where('can_create', 1)->first() != null)
                return true;
        });

        //Stock Opname
        Gate::define('index-stock', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_st' , 'user_id' => auth()->id()])->where('can_index', 1)->first() != null)
                return true;
        });

        Gate::define('read-stock', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_st' , 'user_id' => auth()->id()])->where('can_read', 1)->first() != null)
                return true;
        });

        Gate::define('create-stock', function() {
            if(Roleakses::where(['nama_controller' => 'inventory_st' , 'user_id' => auth()->id()])->where('can_create', 1)->first() != null)
                return true;
        });
    }
}