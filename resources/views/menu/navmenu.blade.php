<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

    <li class=" nav-item"><a class="d-flex align-items-center" href="index.html"><i data-feather="home"></i></i><span class="menu-title text-truncate" data-i18n="Dashboards">DASHBOARD</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/dashboard*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('/admin/dashboard') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Index</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="briefcase"></i><span class="menu-title text-truncate" data-i18n="Invoice">GENERAL LEDGER</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/jurnal-voucher*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.jurnal-voucher.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Journal Voucher</span></a>
            </li>
            <li class="{{ (request()->is('admin/coa*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.coa.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">List Account (COA)</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="book-open"></i><span class="menu-title text-truncate" data-i18n="Invoice">CASH BANK</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/penerimaan*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.penerimaan.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Deposit</span></a>
            </li>
            <li class="{{ (request()->is('admin/pembayaran')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.pembayaran.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Payment</span></a>
            </li>
            <li class="{{ (request()->is('admin/buku-bank*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.buku-bank.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Bank Book</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="truck"></i><span class="menu-title text-truncate" data-i18n="Invoice">PURCHASING</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/pmtpembelian*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/pmtpembelian') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Purchase Request</span></a>
            </li>
            <li class="{{ (request()->is('admin/po*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/po') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Purchase Order</span></a>
            </li>
            <li class="{{ (request()->is('admin/ri*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/ri') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Receive Item</span></a>
            </li>
            <li class="{{ (request()->is('admin/fakturpembelian*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/fakturpembelian') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Purchase Invoice</span></a>
            </li>
            <li class="{{ (request()->is('admin/pembayaranpembelian*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/pembayaranpembelian') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Purchase Payment</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="shopping-cart"></i><span class="menu-title text-truncate" data-i18n="Invoice">SALES</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/so*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/so') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Sales Order</span></a>
            </li>
            <li class="{{ (request()->is('admin/do*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/do') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Delivery Order</span></a>
            </li>
            <li class="{{ (request()->is('admin/si*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/si') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Sales Invoice</span></a>
            </li>
            <li class="{{ (request()->is('admin/cr*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/cr') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Customer Receipt</span></a>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="archive"></i><span class="menu-title text-truncate" data-i18n="Invoice">INVENTORY</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/list-inventory*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/list-inventory/all') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">List Inventory</span></a>
            </li>
            <li  class="{{ (request()->is('admin/stock-opname*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/stock-opname') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Stock Adjusment</span></a>
            </li>
            <li  class="{{ (request()->is('admin/mutation-inventory*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/mutation-inventory') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Mutation</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="Invoice">CATEGORY</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/kategoribarang*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/kategoribarang') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Items</span></a>
            </li>
            <li class="{{ (request()->is('admin/tipepelanggan*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/tipepelanggan') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Customers</span></a>
            </li>
            <li class="{{ (request()->is('admin/tipesupplier*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/tipesupplier') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Suppliers</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Invoice">HR & GA</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/employee*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/employee') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Employees</span></a>
            </li>
            <li class="{{ (request()->is('admin/asset*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/asset') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Asset</span></a>
            </li>
            <li class="{{ (request()->is('admin/tipeasset*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/tipeasset') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Category Asset</span></a>
            </li>
            <!-- <li class="{{ (request()->is('admin/absen*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/tipeasset') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Absent</span></a>
            </li> -->
        </ul>
    </li> 
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="folder"></i><span class="menu-title text-truncate" data-i18n="Invoice">MASTER</span></a>
        <ul class="menu-content">
            <li class="{{ (request()->is('admin/barang*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/barang') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">All Items</span></a>
            </li>
            <li class="{{ (request()->is('admin/catalog*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/catalog') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Catalog Items</span></a>
            </li>
            <li class="{{ (request()->is('admin/packet*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/packet') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Packet</span></a>
            </li>
            <li class="{{ (request()->is('admin/gudang*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/gudang') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Warehouses</span></a>
            </li>
            <li class="{{ (request()->is('admin/pelanggan*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/pelanggan') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Customers</span></a>
            </li>
            <li class="{{ (request()->is('admin/supplier*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/supplier') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Suppliers</span></a>
            </li>
            <li class="{{ (request()->is('admin/sales*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ url('admin/sales') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Sales</span></a>
            </li>
        </ul>
    </li>
    <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file"></i><span class="menu-title text-truncate" data-i18n="Invoice">REPORTS</span></a>
        <ul class="menu-content">
            <li><a class="d-flex align-items-center" href="#"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Financial Statements</span></a>
            </li>
            <li class="{{ (request()->is('admin/report-buku-besar*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.report.buku-besar.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">General Ledger</span></a>
            </li>
            <li class="{{ (request()->is('admin/report-kas-bank*')) ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('admin.report.kas-bank.index') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Cash & Bank</span></a>
            </li>
            <li class="{{ (request()->is('admin/report-sales*')) ? 'active' : '' }}" ><a class="d-flex align-items-center" href="{{ url('admin/report-sales') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Sales Reports</span></a>
            </li>
            <li class="{{ (request()->is('admin/report-purchase*')) ? 'active' : '' }}" ><a class="d-flex align-items-center" href="{{ url('admin/report-purchase') }}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Purchase Reports</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="#"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">Inventory</span></a>
            </li>
        </ul>
    </li>
    <li class="navigation-header text-truncate">
        <span>Settings</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle>
            <circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle>
        </svg>
    </li>
    <li class="nav-item {{ (request()->is('admin/role-akses*')) ? 'active' : '' }}" >
        <a href="{{ url('admin/role-akses') }}" class="d-flex align-items-center">
        <i data-feather="settings"></i><span class="menu-title text-truncate">ROLE MANAGEMENT</span></a>
    </li>
    
    <li class="nav-item {{ (request()->is('admin/profile*')) ? 'active' : '' }}" >
        <a href="{{ url('admin/profile') }}" class="d-flex align-items-center">
        <i data-feather="user"></i> <span class="menu-title text-truncate">PROFILE</span></a>
    </li>
</ul>