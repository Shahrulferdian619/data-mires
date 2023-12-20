<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="/template/vuexy/assets/" data-template="vertical-menu-template-starter">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') Sistem Informasi | Mires Mahisa</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/vuexy/images/ico/favicon_mires.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/template/vuexy/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="/template/vuexy/assets/vendor/libs/select2/select2.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="/template/vuexy/assets/vendor/js/helpers.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/template/vuexy/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/template/vuexy/assets/js/config.js"></script>

    <script src="/template/vuexy/assets/js/easy-number-separator.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.9.0/autoNumeric.min.js" integrity="sha512-8gzQcg9kbS4PKtpwg52pfVLjkqSYAc5IWHnd0eLGgxlcAnqYPcVLCh9pgQErFthJvmmxFNvbCAGF6vuHtGfZsQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script src="/js/custom.js"></script>
    
    @yield('custom_style')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                @include('v2.layout.component.brand')

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Page -->
                    @include('v2.layout.component.sidemenu')
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                                <i class="ti ti-sm"></i>
                            </a>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            @include('v2.layout.component.profile')
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <p class="bg-label-success" style="font-size: 20px;">
                            <a href="{{ url('/admin/dashboard') }}">Kembali ke sistem versi 1</a>
                        </p>
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    © 2021 - <a href="#"><b>PT. Mires Mahisa Globalindo</b></a>
                                </div>
                                <div>
                                    <a href="#" class="footer-link me-4">ver. 2.0.0</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="/template/vuexy/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/template/vuexy/assets/vendor/libs/popper/popper.js"></script>
    <script src="/template/vuexy/assets/vendor/js/bootstrap.js"></script>
    <script src="/template/vuexy/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/template/vuexy/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="/template/vuexy/assets/vendor/libs/hammer/hammer.js"></script>

    <script src="/template/vuexy/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/template/vuexy/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="/template/vuexy/assets/vendor/libs/select2/select2.js"></script>

    <!-- Main JS -->
    <script src="/template/vuexy/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="/template/vuexy/assets/js/tables-datatables-advanced.js"></script>
    @yield('custom_js')
</body>

</html>