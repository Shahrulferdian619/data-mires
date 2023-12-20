<li class="menu-header small text-uppercase">
    <span class="menu-header-text">Menu Utama</span>
</li>

<li class="menu-item {{ (request()->is('v2/beranda*')) ? 'active' : '' }}">
    <a href="{{ route('beranda.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Dashboard">Beranda</div>
    </a>
</li>

<li class="menu-item {{ (request()->is('admin/jurnal-voucher*')) ? 'active' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-book"></i>
        <div data-i18n="Buku Besar">Buku Besar</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                <div data-i18n="Jurnal Umum">Jurnal Umum</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                <div data-i18n="Daftar COA ">Daftar COA </div>
            </a>
        </li>
    </ul>
</li>

<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-building-bank"></i>
        <div data-i18n="Kas Bank">Kas Bank</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                <div data-i18n="Penerimaan">Penerimaan</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
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

<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-truck-delivery"></i>
        Pembelian
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                Permintaan Pembelian
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Pesanan Pembelian
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Penerimaan Barang
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Faktur Pembelian
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Pembayaran Pembelian
            </a>
        </li>
    </ul>
</li>

<li class="menu-item {{ (request()->is('v2/penjualan/*')) ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
        Penjualan
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ (request()->is('v2/penjualan/pesanan*')) ? 'active' : '' }}">
            <a href="/admin/so" class="menu-link">
                Pesanan Penjualan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/pengiriman*')) ? 'active' : '' }}">
            <a href="/admin/do" class="menu-link">
                Pengiriman Barang
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/invoice*')) ? 'active' : '' }}">
            <a href="#" class="menu-link">
                Faktur Penjualan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/penerimaan*')) ? 'active' : '' }}">
            <a href="#" class="menu-link">
                Penerimaan Penjualan
            </a>
        </li>
        <li class="menu-item {{ (request()->is('v2/penjualan/konsinyasi*')) ? 'active' : '' }}">
            <a href="{{ route('konsinyasi.index') }}" class="menu-link">
                Permintaan Konsinyasi
            </a>
        </li>
    </ul>
</li>

<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-building-warehouse"></i>
        Persediaan
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                Daftar Barang
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Penyesuaian Persediaan
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Pindah Barang
            </a>
        </li>
    </ul>
</li>

<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-report"></i>
        Laporan
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

<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-category"></i>
        Kategori
    </a>
    <ul class="menu-sub">
        <li class="menu-item">
            <a href="#" class="menu-link">
                Barang
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
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

<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-database"></i>
        Data
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
        <li class="menu-item">
            <a href="#" class="menu-link">
                Gudang
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Pelanggan
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                Supplier
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
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
        <div data-i18n="Dashboard">Profile</div>
    </a>
</li>
