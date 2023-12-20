<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Menu Utama</span>
</li>

<!-- Beranda -->
<li class="menu-item {{ (request()->is('v2/beranda*')) ? 'active' : '' }}">
    <a href="{{ route('beranda.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Dashboard">Beranda</div>
    </a>
</li>

<!-- Buku Besar -->
<li class="menu-item {{ (request()->is('v2/bukubesar*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-book"></i>
        <div data-i18n="Buku Besar">Buku Besar</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/bukubesar/laporan*')) ? 'active open' : '' }}">
            <a href="{{ route('bukubesar.laporan.index') }}" class="menu-link">
                <div data-i18n="Laporan">Laporan</div>
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/bukubesar/jurnal-umum*')) ? 'active open' : '' }}">
            <a href="{{ route('bukubesar.jurnal-umum.index') }}" class="menu-link">
                <div data-i18n="Jurnal Umum">Jurnal Umum</div>
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/bukubesar/coa*')) ? 'active open' : '' }}">
            <a href="{{ route('bukubesar.coa.index') }}" class="menu-link">
                <div data-i18n="Daftar COA ">Daftar COA </div>
            </a>
        </li>
    </ul>
</li>

<!-- Kasbank -->
<li class="menu-item {{ (request()->is('v2/kasbank*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-building-bank"></i>
        <div data-i18n="Kas Bank">Kas Bank</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/kasbank/penerimaan*')) ? 'active open' : '' }}">
            <a href="{{ route('kasbank.penerimaan.index') }}" class="menu-link">
                <div data-i18n="Penerimaan">Penerimaan</div>
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/kasbank/pembayaran*')) ? 'active open' : '' }}">
            <a href="{{ route('kasbank.pembayaran.index') }}" class="menu-link">
                <div data-i18n="Pembayaran">Pembayaran</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Buku Bank
            </a>
        </li>
    </ul>
</li>

<!-- Pembelian -->
<li class="menu-item {{ (request()->is('v2/pembelian*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-truck-delivery"></i>
        <div data-i18n="Pembelian">Pembelian</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/pembelian/permintaan-pembelian*')) ? 'active' : '' }}">
            <a href="{{ route('pembelian.permintaan-pembelian.index') }}" class="menu-link">
                Permintaan Pembelian
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/pembelian/pesanan-pembelian*')) ? 'active' : '' }}">
            <a href="{{ route('pembelian.pesanan-pembelian.index') }}" class="menu-link">
                Pesanan Pembelian
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/pembelian/penerimaan-barang*')) ? 'active' : '' }}">
            <a href="{{ route('pembelian.penerimaan-barang.index') }}" class="menu-link">
                Penerimaan Barang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/pembelian/invoice-pembelian*')) ? 'active' : '' }}">
            <a href="{{ route('pembelian.invoice-pembelian.index') }}" class="menu-link">
                Invoice Pembelian
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Pembayaran Pembelian
            </a>
        </li>
    </ul>
</li>

<!-- Penjualan -->
<li class="menu-item {{ (request()->is('v2/penjualan*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
        <div data-i18n="Penjualan">Penjualan</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/penjualan/pesanan*')) ? 'active' : '' }}">
            <a href="{{ route('pesanan-penjualan.index') }}" class="menu-link">
                Pesanan Penjualan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/pengiriman*')) ? 'active' : '' }}">
            <a href="{{ route('pengiriman-penjualan.index') }}" class="menu-link">
                Pengiriman Barang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/invoice-penjualan*')) ? 'active' : '' }}">
            <a href="{{ route('invoice-penjualan.index') }}" class="menu-link">
                Invoice Penjualan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/penerimaan*')) ? 'active' : '' }}">
            <a href="{{ route('penerimaan-penjualan.index') }}" class="menu-link">
                Penerimaan Penjualan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/konsinyasi*')) ? 'active' : '' }}">
            <a href="{{ route('konsinyasi.index') }}" class="menu-link">
                Permintaan Konsinyasi
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/permintaan-tester*')) ? 'active' : '' }}">
            <a href="{{ route('permintaan-tester.index') }}" class="menu-link">
                Permintaan Tester
            </a>
        </li>
    </ul>
</li>

<!-- Persediaan -->
<li class="menu-item {{ (request()->is('v2/persediaan*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
        <div data-i18n="Persediaan">Persediaan</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/persediaan/stok*')) ? 'active' : '' }}">
            <a href="{{ route('persediaan.stok-gudang') }}" class="menu-link">
                Stok Barang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/persediaan/daftar-barang*')) ? 'active' : '' }}">
            <a href="#" class="menu-link">
                Daftar Barang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/persediaan/penyesuaian-persediaan*')) ? 'active' : '' }}">
            <a href="#" class="menu-link">
                Stok Opname
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/persediaan/pindah-stok*')) ? 'active' : '' }}">
            <a href="{{ route('pindah-stok.index') }}" class="menu-link">
                Pindah Stok
            </a>
        </li>
    </ul>
</li>

<!-- Produksi -->
<li class="menu-item {{ (request()->is('v2/produksi*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
        <div data-i18n="Persediaan">Produksi</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/produksi/semi-index*')) ? 'active' : '' }}">
            <a href="{{ route('produksi.semi-index') }}" class="menu-link">
                Semi Produksi
            </a>
        </li>
    </ul>
</li>

<!-- Laporan -->
<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-report"></i>
        <div data-i18n="Laporan">Laporan</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                Keuangan
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Buku Besar
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Kas Bank
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Penjualan
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Pembelian
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Persediaan
            </a>
        </li>
    </ul>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Master</span>
</li>

<!-- Kategori -->
<li class="menu-item {{ (request()->is('v2/master/kategori*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-category"></i>
        <div data-i18n="Kategori">Kategori</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                Barang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/master/kategori/pelanggan*')) ? 'active open' : '' }}">
            <a href="{{ route('master-kategori.pelanggan.index') }}" class="menu-link">
                Pelanggan
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Supplier
            </a>
        </li>
    </ul>
</li>

<!-- Data -->
<li class="menu-item {{ (request()->is('v2/master/data*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-database"></i>
        <div data-i18n="Data">Data</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                Semua Barang
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Katalog Produk
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Paket Produk
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/master/data/gudang*')) ? 'active open' : '' }}">
            <a href="{{ route('master-data.gudang.index') }}" class="menu-link">
                Gudang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/master/data/pelanggan*')) ? 'active open' : '' }}">
            <a href="{{ route('master-data.pelanggan.index') }}" class="menu-link">
                Pelanggan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/master/data/supplier*')) ? 'active open' : '' }}">
            <a href="{{ route('master-data.supplier.index') }}" class="menu-link">
                Supplier
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/master/data/sales*')) ? 'active open' : '' }}">
            <a href="{{ route('master-data.sales.index') }}" class="menu-link">
                Sales
            </a>
        </li>
    </ul>
</li>

<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Pengaturan</span>
</li>

<li class="menu-item">
    <a href="#" class="menu-link">
        <i class="menu-icon tf-icons ti ti-user"></i>
        <div data-i18n="Profile">Profile</div>
    </a>
</li>